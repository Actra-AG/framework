<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;


use actra\backend\libs\db\DbAuthUser;
use actra\yuf\html\HtmlDataObject;
use app\view\backend\php\member;
use DateTimeImmutable;

readonly class DbMember
{
    public function __construct(
        public DbAuthUser $dbAuthUser,
        public int $clubID,
        public string $clubName,
        public string $gender,
        public string $street,
        public string $zip,
        public string $city,
        public int $license,
        public string $phone,
        public ?DateTimeImmutable $birthDate,
        public string $comment,
        public string $notes,
        public bool $honorary,
        public int $honored,
        public ?DateTimeImmutable $accepted,
        public ?DateTimeImmutable $rejected
    ) {
    }

    public function isPendingRequest(): bool
    {
        return $this->accepted === null && $this->rejected === null;
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'detailsHref',
            content: member::getPath(ID: $this->dbAuthUser->ID),
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'registered',
            content: $this->dbAuthUser->registered->format(format: 'd.m.Y H:i:s'),
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'clubName',
            content: $this->clubName,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'gender',
            content: $this->gender,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'firstName',
            content: $this->dbAuthUser->firstName,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'lastName',
            content: $this->dbAuthUser->lastName,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'street',
            content: $this->street,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'zip',
            content: $this->zip,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'city',
            content: $this->city,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'license',
            content: (string)$this->license,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'phone',
            content: $this->phone,
            isEncodedForRendering: false
        );
        $birthDate = $this->birthDate;
        $htmlDataObject->addTextElement(
            propertyName: 'birthDate',
            content: $birthDate === null ? '' : $birthDate->format(format: 'd.m.Y'),
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'comment',
            content: $this->comment,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'notes',
            content: $this->notes,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'email',
            content: $this->dbAuthUser->email,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'honorary',
            content: ($this->honorary ? 'Ja' : 'Nein'),
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'honored',
            content: (string)$this->honored,
            isEncodedForRendering: false
        );


        return $htmlDataObject;
    }
}