<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\ActraBackend;
use actra\backend\libs\db\DbAuthUser;
use actra\backend\libs\email\EmailAuthUser;
use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbMemberRepository;
use app\view\backend\php\member;

class MemberRejectForm extends Form
{
    private readonly TextField $subjectField;
    private readonly TextAreaField $bodyField;

    public function __construct(private readonly DbAuthUser $dbAuthUser)
    {
        parent::__construct(
            name: 'MemberAcceptForm'
        );
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->subjectField = new TextField(
                name: 'subjectField',
                label: HtmlText::encoded(textContent: 'Betreff'),
                value: 'Ihr Zugang wurde verweigert',
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
                        'Guten Tag ' . $dbAuthUser->firstName . ' ' . $dbAuthUser->lastName,
                        '',
                        'Ihr Zugang in unseren passwortgeschützten Bereich wurde leider verweigert.',
                        '',
                        'Vielen Dank für Ihr Verständnis.',
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
                submitLabel: HtmlText::encoded(textContent: 'Ablehnen'),
                cancelLink: member::getPath(ID: $dbAuthUser->ID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $dbAuthUser = $this->dbAuthUser;
        DbMemberRepository::deny($dbAuthUser->ID);
        EmailAuthUser::send(
            dbAuthUser: $dbAuthUser,
            subject: $this->subjectField->getRawValue(),
            message: $this->bodyField->getRawValue()
        );
        return true;
    }
}