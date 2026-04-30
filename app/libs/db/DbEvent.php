<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\auth\MyAuthUser;
use actra\yuf\html\HtmlDataObject;
use app\libs\backend\AuthUserHelper;
use app\settings\EventCategoryCollection;
use DateTimeImmutable;

readonly class DbEvent
{
    public function __construct(
      public int $ID,
      public DateTimeImmutable $registered,
      public DateTimeImmutable $lastModified,
      public ?DateTimeImmutable $confirmed,
      public ?DateTimeImmutable $denied,
      public int $registeredByUserID,
      public string $registeredByName,
      public string $registeredByEmail,
      public ?int $clubID,
      public ?string $clubName,
      public DateTimeImmutable $dateFrom,
      public DateTimeImmutable $dateTo,
      public string $timeFormatted,
      public string $title,
      public string $location,
      public string $notes,
      public bool $export,
      public DateTimeImmutable $timeFrom,
      public DateTimeImmutable $timeTo,
      public EventCategoryCollection $eventCategoryCollection
    ) {
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
          propertyName: 'clubName',
          content: $this->clubName,
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'date',
          content: $this->dateFrom === $this->dateTo ? $this->dateFrom->format(
            format: 'd.m.Y'
          ) : $this->dateFrom->format(format: 'd.m.Y') . ' - ' . $this->dateTo->format(format: 'd.m.Y'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'title',
          content: $this->title,
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'location',
          content: $this->location,
          isEncodedForRendering: false
        );
        $htmlDataObject->addHtmlDataObjectsArray(
          propertyName: 'categories',
          htmlDataObjectsArray: $this->eventCategoryCollection->render()->items,
        );
        $htmlDataObject->addTextElement(
          propertyName: 'calendarExport',
          content: ($this->export ? 'Ja' : 'Nein'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'timeFrom',
          content: $this->timeFrom->format(format: 'H:i'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'timeTo',
          content: $this->timeTo->format(format: 'H:i'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'time',
          content: $this->timeFormatted,
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'notes',
          content: $this->notes,
          isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
          propertyName: 'registered',
          content: $this->registered->format(format: 'd.m.Y H:i:s'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'registeredByName',
          content: $this->registeredByName,
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'lastModified',
          content: $this->lastModified->format(format: 'd.m.Y H:i:s'),
          isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
          propertyName: 'status',
          content: $this->isAccepted() ? 'aktiv' : ($this->isRejected() ? 'abgelehnt' : 'zu prüfen'),
          isEncodedForRendering: false
        );
        return $htmlDataObject;
    }

    public function isAccepted(): bool
    {
        return $this->confirmed !== null;
    }

    public function isRejected(): bool
    {
        return $this->denied !== null;
    }

    public function canActivate(): bool
    {
        return (
          AuthUserHelper::isAdmin()
          && !$this->isAccepted()
        );
    }

    public function canReject(): bool
    {
        return (
          AuthUserHelper::isAdmin()
          && !$this->isRejected()
        );
    }

    public function userCanEdit(): bool
    {
        return (
          AuthUserHelper::isAdmin()
          || $this->registeredByUserID === MyAuthUser::get()->ID
        );
    }

    public function isMonth(int $year, int $month): bool
    {
        $queryMonthStart = new DateTimeImmutable(datetime: "$year-$month-01 00:00:00");
        $queryMonthEnd = $queryMonthStart->modify(modifier: 'last day of this month')->setTime(
          hour: 23,
          minute: 59,
          second: 59
        );
        return (
          $this->dateFrom <= $queryMonthEnd
          && $this->dateTo >= $queryMonthStart
        );
    }
}