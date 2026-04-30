<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\calendar\php;

use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\BaseView;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\core\RequestHandler;
use actra\yuf\exception\NotFoundException;
use app\libs\common\CalendarFile;
use app\libs\db\DbEventRepository;

class calendar extends BaseView
{
    public function __construct()
    {
        parent::__construct(
          requiredViewGroupName: 'calendar',
          ipWhitelist: [],
          authUser: null,
          requiredAccessRights: AccessRightCollection::createEmpty(),
          inputParameterCollection: new InputParameterCollection(),
        );
    }

    public function execute(): void
    {
        $requestHandler = RequestHandler::get();
        $routeVariables = $requestHandler->routeVariables;
        $eventID = (int)$routeVariables['eventID'];
        $dbEvent = DbEventRepository::selectByID(ID: $eventID);
        if ($dbEvent === null || !$dbEvent->export) {
            throw new NotFoundException();
        }
        new CalendarFile(
          dbEvent: $dbEvent,
          filename: 'event.ics'
        )->downloadAndExit();
    }
}