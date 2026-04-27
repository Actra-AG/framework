<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\ActraBackend;
use actra\backend\libs\db\DbAuthUserRepository;
use actra\backend\libs\email\EmailAuthUser;
use actra\yuf\core\HttpRequest;
use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbEvent;
use app\libs\db\DbEventRepository;
use app\view\backend\php\event;

class EventActivateForm extends Form
{
    private readonly TextField $subjectField;
    private readonly TextAreaField $bodyField;

    public function __construct(
        private readonly DbEvent $dbEvent
    ) {
        parent::__construct(
            name: 'EventActivateForm'
        );
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->subjectField = new TextField(
                name: 'subjectField',
                label: HtmlText::encoded(textContent: 'Betreff'),
                value: 'Ihr Anlass wurde freigeschaltet',
                requiredError: HtmlText::encoded(textContent: 'Geben Sie bitte ein Betreff ein.')
            )
        );
        $this->addField(
            formField: $this->bodyField = new TextAreaField(
                name: 'bodyField',
                label: HtmlText::encoded(textContent: 'Textinhalt'),
                value: implode(
                    separator: PHP_EOL,
                    array: [
                        'Guten Tag ' . $dbEvent->registeredByName,
                        '',
                        'Der von Ihnen erfasste Anlass "' . $dbEvent->title . '" wurde von einem Administrator geprüft und auf unserer Website ' . HttpRequest::getProtocol(
                        ) . '://' . HttpRequest::getHost() . ' veröffentlicht.',
                        '',
                        'Freundliche Grüsse',
                        '',
                        ActraBackend::get()->mailerSettings->signature,
                    ]
                ),
                requiredError: HtmlText::encoded(textContent: 'Geben Sie bitte den gewünschten Text ein.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'submit',
                submitLabel: HtmlText::encoded(textContent: 'Bestätigen'),
                cancelLink: event::getPath(ID: $dbEvent->ID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        DbEventRepository::confirm(ID: $this->dbEvent->ID);
        EmailAuthUser::send(
            dbAuthUser: DbAuthUserRepository::selectByID(ID: $this->dbEvent->registeredByUserID),
            subject: $this->subjectField->getRawValue(),
            message: $this->bodyField->getRawValue()
        );
        return true;
    }
}