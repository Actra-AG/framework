<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

namespace app\settings;

use actra\yuf\html\HtmlDataObject;
use app\libs\backend\AuthUserHelper;

enum EventCategoryEnum: string
{
    case SA300 = 'sa300';
    case SA50 = 'sa50';
    case SA25 = 'sa25';
    case SA10 = 'sa10';
    case JS = 'js';
    case MW300 = 'mw300';
    case MW50 = 'mw50';
    case MWLG = 'mwlg';
    case MWLP = 'mwlp';
    case GM300 = 'gm300';
    case GM50 = 'gm50';
    case GM25 = 'gm25';
    case GM10 = 'gm10';
    case VS = 'vs';
    case VT = 'vt';
    case WB = 'wb';
    case VORSTAND = 'vorstand';

    public function getTitle(): string
    {
        return match ($this) {
            EventCategoryEnum::SA300 => 'Schiessanlässe 300m',
            EventCategoryEnum::SA50 => 'Schiessanlässe 50m',
            EventCategoryEnum::SA25 => 'Schiessanlässe 25m',
            EventCategoryEnum::SA10 => 'Schiessanlässe 10m',
            EventCategoryEnum::JS => 'Jungschützen/Nachwuchs',
            EventCategoryEnum::MW300 => 'Matchwesen 300m',
            EventCategoryEnum::MW50 => 'Matchwesen 50m',
            EventCategoryEnum::MWLG => 'Matchwesen Luftgewehr',
            EventCategoryEnum::MWLP => 'Matchwesen Luftpistole',
            EventCategoryEnum::GM300 => 'Gruppenmeisterschaft 300m',
            EventCategoryEnum::GM50 => 'Gruppenmeisterschaft 50m',
            EventCategoryEnum::GM25 => 'Gruppenmeisterschaft 25m',
            EventCategoryEnum::GM10 => 'Gruppenmeisterschaft 10m',
            EventCategoryEnum::VS => 'Versammlungen',
            EventCategoryEnum::VT => 'Veteranen',
            EventCategoryEnum::WB => 'Ausbildungen',
            EventCategoryEnum::VORSTAND => 'Vorstand',
        };
    }

    public function getShortTitle(): string
    {
        return match ($this) {
            EventCategoryEnum::SA300, EventCategoryEnum::MW300, EventCategoryEnum::GM300 => '300m',
            EventCategoryEnum::SA50, EventCategoryEnum::MW50, EventCategoryEnum::GM50 => '50m',
            EventCategoryEnum::SA25, EventCategoryEnum::GM25 => '25m',
            EventCategoryEnum::SA10, EventCategoryEnum::GM10 => '10m',
            EventCategoryEnum::JS => 'Jungschützen/Nachwuchs',
            EventCategoryEnum::MWLG => 'Luftgewehr',
            EventCategoryEnum::MWLP => 'Luftpistole',
            EventCategoryEnum::VS => 'Versammlungen',
            EventCategoryEnum::VT => 'Veteranen',
            EventCategoryEnum::WB => 'Ausbildungen',
            EventCategoryEnum::VORSTAND => 'Vorstand',
        };
    }

    public function getConditions(): string
    {
        return match ($this) {
            EventCategoryEnum::SA300, EventCategoryEnum::SA50, EventCategoryEnum::SA25, EventCategoryEnum::SA10, EventCategoryEnum::MW300, EventCategoryEnum::MW50, EventCategoryEnum::MWLG, EventCategoryEnum::MWLP, EventCategoryEnum::GM300, EventCategoryEnum::GM50, EventCategoryEnum::GM25, EventCategoryEnum::GM10, EventCategoryEnum::VT, EventCategoryEnum::WB => "p.datumBis>=DATE_SUB(CURDATE(), INTERVAL 3 DAY)",
            EventCategoryEnum::JS, EventCategoryEnum::VS => "YEAR(p.datumBis)>=YEAR(CURDATE())",
            EventCategoryEnum::VORSTAND => [],
        };
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'title',
            content: $this->getTitle(),
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'shortTitle',
            content: $this->getShortTitle(),
            isEncodedForRendering: true
        );
        return $htmlDataObject;
    }

    public function userCanAccess(): bool
    {
        return (
            $this !== EventCategoryEnum::VORSTAND
            || AuthUserHelper::isBoard()
        );
    }
}