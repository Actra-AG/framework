<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\form;

use framework\core\HttpRequest;
use framework\form\component\collection\Form;
use framework\form\component\field\TextAreaField;
use framework\form\component\field\TextField;
use framework\form\component\FormControl;
use framework\html\HtmlText;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\db\DbAuthUserItem;
use site\libs\auth\email\EmailUserInvite;
use site\settings\ProjectSettings;

class UserInviteForm extends Form
{
    private readonly TextField $subjectField;
    private readonly TextAreaField $bodyField;

    public function __construct(private readonly DbAuthUserItem $dbAuthUserItem)
    {
        parent::__construct(name: 'UserInviteForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->subjectField = new TextField(
                name: 'subjectField',
                label: HtmlText::encoded(textContent: 'Betreff'),
                value: 'Zugang zum passwortgeschützten Bereich',
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
                        'Guten Tag ' . $this->dbAuthUserItem->firstName . ' ' . $this->dbAuthUserItem->lastName,
                        '',
                        'Wir haben Ihnen einen Zugang in unser Backend eingerichtet:',
                        HttpRequest::getProtocol() . '://' . HttpRequest::getHost(
                        ) . ProjectSettings::BACKEND_DIRECTORY,
                        '',
                        'Geben Sie zur Anmeldung Ihre E-Mail-Adresse ' . $this->dbAuthUserItem->email . ' und beim nächsten Schritt den erhaltenen Bestätigungscode ein, um sich anzumelden.',
                        '',
                        'Freundliche Grüsse',
                        '',
                        ProjectSettings::EMAIL_SIGNATURE,
                    ]
                ),
                requiredError: HtmlText::encoded(textContent: 'Geben Sie bitte den gewünschten Text ein.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'submit',
                submitLabel: HtmlText::encoded(textContent: 'senden')
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $dbUserItem = $this->dbAuthUserItem;
        EmailUserInvite::send(
            dbAuthUserItem: $dbUserItem,
            subject: $this->subjectField->getRawValue(),
            message: $this->bodyField->getRawValue()
        );
        DbAuthUser::sentInvitation(ID: $dbUserItem->ID);

        return true;
    }
}