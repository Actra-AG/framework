<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form;

use framework\form\renderer\DefaultCollectionRenderer;
use LogicException;

abstract class FormCollection extends FormComponent
{
    /** @var FormComponent[] : Array with all child components, which can also be collections */
    private(set) array $childComponents = [];

    final public function addChildComponent(FormComponent $formComponent): void
    {
        $childComponentName = $formComponent->name;
        if (isset($this->childComponents[$childComponentName])) {
            throw new LogicException(
                'There is already an existing child component with the same name: ' . $childComponentName
            );
        }
        $formComponent->setParentFormComponent($this);

        $this->childComponents[$childComponentName] = $formComponent;
    }

    public function getChildComponent(string $childComponentName): FormComponent
    {
        if (!$this->hasChildComponent($childComponentName)) {
            throw new LogicException(
                'FormCollection ' . $this->name . ' does not contain requested ChildComponent ' . $childComponentName
            );
        }

        return $this->childComponents[$childComponentName];
    }

    public function hasChildComponent(string $childComponentName): bool
    {
        return array_key_exists($childComponentName, $this->childComponents);
    }

    public function removeChildComponent(string $childComponentName): void
    {
        if (!$this->hasChildComponent($childComponentName)) {
            throw new LogicException(
                'FormCollection ' . $this->name . ' does not contain requested ChildComponent ' . $childComponentName
            );
        }
        unset($this->childComponents[$childComponentName]);
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new DefaultCollectionRenderer($this);
    }
}