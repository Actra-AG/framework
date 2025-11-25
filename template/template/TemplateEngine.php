<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\template;

use ArrayObject;
use Exception;
use framework\template\customtags\CheckboxOptionsTag;
use framework\template\customtags\CheckboxTag;
use framework\template\customtags\DateTag;
use framework\template\customtags\ElseifTag;
use framework\template\customtags\ElseTag;
use framework\template\customtags\For2Tag;
use framework\template\customtags\ForgroupTag;
use framework\template\customtags\FormAddRemoveTag;
use framework\template\customtags\FormComponentTag;
use framework\template\customtags\ForTag;
use framework\template\customtags\IfTag;
use framework\template\customtags\LangTag;
use framework\template\customtags\LoadSubTplTag;
use framework\template\customtags\OptionsTag;
use framework\template\customtags\OptionTag;
use framework\template\customtags\PrintTag;
use framework\template\customtags\RadioOptionsTag;
use framework\template\customtags\RadioTag;
use framework\template\customtags\SnippetTag;
use framework\template\customtags\TextTag;
use framework\template\htmlparser\CDataSectionNode;
use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\HtmlDoc;
use framework\template\htmlparser\TextNode;
use ReflectionProperty;
use Throwable;

class TemplateEngine
{
    public const int ERR_MISSING_TEMPLATE_VARIABLE = 1;
    protected(set) ?TemplateTag $lastTplTag = null;
    protected ?HtmlDoc $htmlDoc = null;
    protected ArrayObject $dataPool;
    protected ArrayObject $dataTable;
    protected array $customTags = [];
    protected ?TemplateCacheEntry $cached = null;
    protected string $currentTemplateFile = '';
    protected array $getterMethodPrefixes = ['get', 'is', 'has'];

    /**
     * @param TemplateCacheStrategy $templateCacheInterface The template cache object
     * @param string $tplNsPrefix The prefix for custom tags in the template file
     * @param array $customTags Additional custom tags to be loaded
     */
    public function __construct(
        protected TemplateCacheStrategy $templateCacheInterface,
        protected string                $tplNsPrefix,
        array                           $customTags = []
    )
    {
        $this->customTags = array_merge(TemplateEngine::getDefaultCustomTags(), $customTags);
        $this->dataPool = new ArrayObject();
        $this->dataTable = new ArrayObject();
    }

    protected static function getDefaultCustomTags(): array
    {
        return [
            'checkboxOptions' => CheckboxOptionsTag::class,
            'checkbox' => CheckboxTag::class,
            'date' => DateTag::class,
            'elseif' => ElseifTag::class,
            'else' => ElseTag::class,
            'for2' => For2Tag::class,
            'forgroup' => ForgroupTag::class,
            'formComponent' => FormComponentTag::class,
            'for' => ForTag::class,
            'if' => IfTag::class,
            'lang' => LangTag::class,
            'loadSubTpl' => LoadSubTplTag::class,
            'options' => OptionsTag::class,
            'option' => OptionTag::class,
            'radioOptions' => RadioOptionsTag::class,
            'radio' => RadioTag::class,
            'snippet' => SnippetTag::class,
            'text' => TextTag::class,
            'print' => PrintTag::class,
            'formAddRemove' => FormAddRemoveTag::class,
        ];
    }

    public function getResultAsHtml(string $tplFile, ArrayObject $dataPool): string
    {
        $this->currentTemplateFile = $tplFile;
        $this->dataPool = $dataPool;
        $templateCacheEntry = $this->parse(tplFile: $tplFile);

        try {
            ob_start();

            require $this->templateCacheInterface->cachePath . $templateCacheEntry->path;

            return ob_get_clean();
        } catch (Throwable $e) {
            // Throw away the whole template code till now
            ob_clean();

            // Throw the Exception again
            throw $e;
        }
    }

    public function parse(string $tplFile): TemplateCacheEntry
    {
        $this->cached = $this->getTemplateCacheEntry($tplFile);
        if ($this->cached !== null) {
            return $this->cached;
        }

        // PARSE IT NEW: No NodeList given? Okay! I'll load defaults for you
        return $this->cache(tplFile: $tplFile);
    }

    /**
     * Returns cached template file
     *
     * @param string $filePath Path to the template file that should be checked
     *
     * @return ?TemplateCacheEntry
     * @throws Exception
     */
    private function getTemplateCacheEntry(string $filePath): ?TemplateCacheEntry
    {
        if (stream_resolve_include_path($filePath) === false) {
            throw new Exception('Could not find template file: ' . $filePath);
        }
        $tplCacheEntry = $this->templateCacheInterface->getCachedTplFile($filePath);
        if ($tplCacheEntry === null) {
            return null;
        }
        $changeTime = @filemtime($filePath);
        if ($changeTime === false) {
            $changeTime = @filectime($filePath);
        }
        if (($tplCacheEntry->size >= 0 && $tplCacheEntry->size !== @filesize(
                    $filePath
                )) || $tplCacheEntry->changeTime < $changeTime) {
            return null;
        }

        return $tplCacheEntry;
    }

    protected function cache($tplFile): TemplateCacheEntry
    {
        if (stream_resolve_include_path($tplFile) === false) {
            throw new Exception('Template file \'' . $tplFile . '\' does not exists');
        }
        $currentCacheEntry = $this->templateCacheInterface->getCachedTplFile($tplFile);
        // Render tpl
        $content = file_get_contents($tplFile);
        $this->htmlDoc = new HtmlDoc($content, $this->tplNsPrefix);
        foreach ($this->customTags as $customTag) {
            if (
                !in_array(needle: TagNode::class, haystack: class_implements(object_or_class: $customTag))
                || !$customTag::isSelfClosing()
            ) {
                continue;
            }

            $this->htmlDoc->addSelfClosingTag(tagName: $this->tplNsPrefix . ':' . $customTag::getName());
        }

        $this->load();

        $compiledTemplateContent = $this->htmlDoc->getHtml();
        $this->templateCacheInterface->saveOnDestruct = false;

        return $this->templateCacheInterface->addCachedTplFile($tplFile, $currentCacheEntry, $compiledTemplateContent);
    }

    protected function load(): void
    {
        $this->lastTplTag = null;
        $this->htmlDoc->parse();
        $nodeList = $this->htmlDoc->nodeTree->childNodes;
        if (count($nodeList) === 0) {
            throw new Exception('Invalid template-file: ' . $this->currentTemplateFile);
        }
        try {
            $this->copyNodes($nodeList);
        } catch (Throwable $e) {
            throw new Exception(
                'Error while processing the template file ' . $this->currentTemplateFile . ': ' . $e->getMessage()
            );
        }
    }

    protected function copyNodes(array $nodeList): void
    {
        foreach ($nodeList as $node) {
            // Parse inline tags if activated
            if ($node instanceof ElementNode === true) {
                foreach ($node->attributes as $name => $htmlTagAttribute) {
                    $htmlTagAttribute->value = $this->replaceInlineTag($htmlTagAttribute->value);
                    $node->updateAttribute($name, $htmlTagAttribute);
                }
            } else {
                if ($node instanceof TextNode || /*$node instanceof CommentNode ||*/
                    $node instanceof CDataSectionNode) {
                    $node->content = $this->replaceInlineTag($node->content);
                }

                continue;
            }

            if (count($node->childNodes) > 0) {
                $this->copyNodes($node->childNodes);
            }

            if ($node->namespace !== $this->tplNsPrefix) {
                continue;
            }

            if (isset($this->customTags[$node->tagName]) === false) {
                throw new Exception(
                    'The custom tag "' . $node->tagName . '" is not registered in this template engine instance'
                );
            }

            $tagClassName = $this->customTags[$node->tagName];

            if (class_exists($tagClassName) === false) {
                throw new Exception('The Tag "' . $tagClassName . '" does not exist');
            }

            $tagInstance = new $tagClassName();
            if (($tagInstance instanceof TemplateTag) === false) {
                $this->templateCacheInterface->saveOnDestruct = false;
                throw new Exception(
                    message: 'The class "' . $tagClassName . '" does not extend the abstract class "TemplateTag" and is so recognized as an illegal class for a custom tag."'
                );
            }
            try {
                $tagInstance->replaceNode($this, $node);
            } catch (Throwable $e) {
                $this->templateCacheInterface->saveOnDestruct = false;
                throw $e;
            }

            $this->lastTplTag = $tagInstance;
        }
    }

    protected function replaceInlineTag(string $value): string
    {
        preg_match_all(
            pattern: '@{' . $this->tplNsPrefix . ':(.+?)(?:\\s+(\\w+=\'.+?\'))?\\s*}@',
            subject: $value,
            matches: $inlineTags,
            flags: PREG_SET_ORDER
        );
        $amountOfInlineTags = count(value: $inlineTags);
        if ($amountOfInlineTags === 0) {
            return $value;
        }
        for ($j = 0; $j < $amountOfInlineTags; $j++) {
            $tagName = $inlineTags[$j][1];

            if (isset($this->customTags[$tagName]) === false) {
                throw new Exception(
                    'The custom tag "' . $tagName . '" is not registered in this template engine instance'
                );
            }

            $tagClassName = $this->customTags[$tagName];

            /** @var TagInline $tagInstance */
            $tagInstance = new $tagClassName();

            if ($tagInstance instanceof TemplateTag === false) {
                $this->templateCacheInterface->saveOnDestruct = false;
                throw new Exception(
                    'The class "' . $tagClassName . '" does not extend the abstract class "TemplateTag" and is so not recognized as an illegal class for a custom tag."'
                );
            }

            if ($tagInstance instanceof TagInline === false) {
                throw new Exception('CustomTag "' . $tagClassName . '" is not allowed to use inline.');
            }

            // Params
            $params = $parsedParams = [];

            if (array_key_exists(2, $inlineTags[$j])) {
                preg_match_all('@(\w+)=\'(.+?)\'@', $inlineTags[$j][2], $parsedParams, PREG_SET_ORDER);

                $countParams = count($parsedParams);
                for ($p = 0; $p < $countParams; $p++) {
                    $params[$parsedParams[$p][1]] = $parsedParams[$p][2];
                }
            }

            try {
                $repl = $tagInstance->replaceInline($this, $params);
                $value = str_replace($inlineTags[$j][0], $repl, $value);
            } catch (Throwable $e) {
                $this->templateCacheInterface->saveOnDestruct = false;
                throw $e;
            }
        }

        return $value;
    }

    public function getDomReader(): HtmlDoc
    {
        return $this->htmlDoc;
    }

    /**
     * Checks if a template node is followed by another template tag with a specific tagName.
     *
     * @param ElementNode $elementNode The template tag
     * @param array $tagNames Array with tagName(s) of the following template tag(s)
     *
     * @return bool
     */
    public function isFollowedBy(ElementNode $elementNode, array $tagNames): bool
    {
        $nextSibling = $elementNode->getNextSibling();

        return !($nextSibling === null || $nextSibling->namespace !== $this->tplNsPrefix || in_array(
                $nextSibling->tagName,
                $tagNames
            ) === false);
    }

    /**
     * Register a value to make it accessible for the engine
     *
     * @param string $key
     * @param mixed $value
     * @param bool $overwrite
     *
     * @throws Exception
     */
    public function addData(string $key, mixed $value, bool $overwrite = false): void
    {
        if ($this->dataPool->offsetExists($key) === true && $overwrite === false) {
            throw new Exception("Data with the key '" . $key . "' is already registered");
        }

        $this->dataPool->offsetSet($key, $value);
    }

    public function unsetData($key): void
    {
        if ($this->dataPool->offsetExists($key) === false) {
            return;
        }

        $this->dataPool->offsetUnset($key);
    }

    /**
     * Returns a registered data entry with the given key
     *
     * @param string $key The key of the data element
     *
     * @return mixed The value for that key or the key itself
     */
    public function getData(string $key): mixed
    {
        if ($this->dataPool->offsetExists($key) === false) {
            return null;
        }

        return $this->dataPool->offsetGet($key);
    }

    public function getDataFromSelector($selector)
    {
        return $this->getSelectorValue($selector);
    }

    /**
     * @param string $selectorStr
     * @param bool $returnNull
     *
     * @return mixed
     * @throws Exception
     */
    protected function getSelectorValue(string $selectorStr, bool $returnNull = false): mixed
    {
        $selParts = explode('.', $selectorStr);
        $firstPart = array_shift($selParts);
        $currentSel = $firstPart;

        if ($this->dataPool->offsetExists($firstPart) === false) {
            if ($returnNull === false) {
                throw new Exception(
                    message: 'The data with offset "' . $currentSel . '" does not exist for template file ' . $this->currentTemplateFile . '. Check, if the correct BaseView class has been found/executed and set the correct replacements.',
                    code: TemplateEngine::ERR_MISSING_TEMPLATE_VARIABLE
                );
            }

            return null;
        }

        $varData = $this->dataPool->offsetGet($firstPart);

        foreach ($selParts as $part) {
            $nextSel = $currentSel . '.' . $part;
            if ($varData instanceof ArrayObject === true) {
                if ($varData->offsetExists($part) === false) {
                    throw new Exception(
                        'Array key "' . $part . '" does not exist in ArrayObject "' . $currentSel . '"'
                    );
                }
                $varData = $varData->offsetGet($part);
            } elseif (is_object($varData) === true) {
                $args = [];
                $argPos = strpos($part, '(');
                if ($argPos !== false) {
                    $argStr = substr($part, $argPos + 1, -1);
                    $part = substr($part, 0, $argPos);
                    foreach (preg_split('/,/x', $argStr) as $no => $arg) {
                        if (!str_starts_with(haystack: $argStr, needle: '\'') || !str_ends_with(
                                haystack: $argStr,
                                needle: '\''
                            )) {
                            $args[$no] = $this->getSelectorValue($argStr, $returnNull);
                        } else {
                            $args[$no] = substr($arg, 1, -1);
                        }
                    }
                }

                if (property_exists($varData, $part) === true) {
                    $getProperty = new ReflectionProperty($varData, $part);

                    if ($getProperty->isPublic() === true) {
                        $varData = $varData->$part;
                    } else {
                        $getterMethodName = null;

                        foreach ($this->getterMethodPrefixes as $mp) {
                            $getterMethodName = $mp . ucfirst($part);

                            if (method_exists($varData, $getterMethodName) === true) {
                                break;
                            }

                            $getterMethodName = null;
                        }

                        if ($getterMethodName === null) {
                            throw new Exception(
                                'Could not access protected/private property "' . $part . '". Please provide a getter method'
                            );
                        }

                        $varData = call_user_func([$varData, $getterMethodName]);
                    }
                } elseif (method_exists($varData, $part) === true) {
                    $varData = call_user_func_array([$varData, $part], $args);
                } else {
                    throw new Exception('Don\'t know how to handle selector part "' . $part . '"');
                }
            } elseif (is_array($varData)) {
                if (array_key_exists($part, $varData) === false) {
                    throw new Exception('Array key "' . $part . '" does not exist in array "' . $currentSel . '"');
                }

                $varData = $varData[$part];
            } else {
                throw new Exception('The data with offset "' . $currentSel . '" is not an object nor an array.');
            }

            $currentSel = $nextSel;
            $this->dataTable->offsetSet($currentSel, $varData);
        }

        return $varData;
    }

    public function setAllData($dataPool): void
    {
        foreach ($dataPool as $key => $val) {
            $this->dataPool->offsetSet($key, $val);
        }
    }

    public function getAllData(): ArrayObject
    {
        return $this->dataPool;
    }

    /**
     * @param ElementNode $contextTag
     * @param array $attributes
     *
     * @return bool
     * @throws Exception
     */
    public function checkRequiredAttributes(ElementNode $contextTag, array $attributes): bool
    {
        foreach ($attributes as $attribute) {
            $val = $contextTag->getAttribute(name: $attribute)->value;
            if (!is_null(value: $val)) {
                continue;
            }
            throw new Exception(
                message: 'Could not parse the template: Missing attribute \'' . $attribute . '\' for custom tag \'' . $contextTag->tagName . '\' in ' . $this->currentTemplateFile . ' on line ' . $contextTag->line
            );
        }

        return true;
    }

    /**
     * Register a new tag for this TemplateEngine instance
     *
     * @param string $tagName The name of the tag
     * @param string $tagClass The class name of the tag
     */
    public function registerTag(string $tagName, string $tagClass): void
    {
        $this->customTags[$tagName] = $tagClass;
    }
}