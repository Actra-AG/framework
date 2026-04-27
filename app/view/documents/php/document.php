<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\documents\php;

use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\BaseView;
use actra\yuf\core\HttpResponse;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\core\RequestHandler;
use actra\yuf\exception\NotFoundException;
use app\libs\common\Helper;
use app\libs\db\DbDocumentRepository;

class document extends BaseView
{
    public function __construct()
    {
        parent::__construct(
            requiredViewGroupName: 'documents',
            ipWhitelist: [],
            authUser: null,
            requiredAccessRights: AccessRightCollection::createEmpty(),
            inputParameterCollection: new InputParameterCollection(),
        );
    }

    public function execute(): void
    {
        $routeVariables = RequestHandler::get()->routeVariables;
        $documentID = (int)$routeVariables['documentID'];
        $token = $routeVariables['token'];
        if ($token !== Helper::createDocumentToken(documentID: $documentID)) {
            throw new NotFoundException();
        }
        $dbDocument = DbDocumentRepository::selectByID(ID: $documentID);
        if ($dbDocument === null) {
            throw new NotFoundException();
        }
        $filePath = $dbDocument->getFilePath();
        if (!file_exists(filename: $filePath)) {
            throw new NotFoundException();
        }
        DbDocumentRepository::increaseViews(ID: $documentID);
        $httResponse = HttpResponse::createResponseFromFilePath(
            absolutePathToFile: $filePath,
            forceDownload: false,
            individualFileName: null,
            maxAge: 0
        );
        $httResponse->sendAndExit();
    }
}