<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\view;

use framework\auth\AccessRightCollection;
use framework\auth\AuthSession;
use framework\auth\UnauthorizedAccessRightException;
use framework\core\BaseView;
use framework\core\ContentHandler;
use framework\core\HttpRequest;
use framework\core\HttpResponse;
use framework\core\InputParameter;
use framework\core\InputParameterCollection;
use framework\html\HtmlDocument;
use framework\html\HtmlText;
use site\libs\auth\db\DbAuthSession;
use site\libs\auth\MyAuthUser;
use site\settings\ProjectSettings;
use site\view\backend\php\login;

abstract class BackendView extends BaseView
{
    public const string PARAM_FROM_LOGIN = 'fromLogin';

    public function __construct(
        InputParameterCollection $inputParameterCollection = new InputParameterCollection(),
        private readonly array   $activeHtmlIdList = [],
        private readonly bool    $useNavigator = false,
        private readonly bool    $resetNavigator = false,
        bool                     $forceLogout = false,
        int                      $maxAllowedPathVars = 0
    )
    {
        if ($forceLogout) {
            AuthSession::logOut();
        }
        $requiredAccessRights = static::getRequiredAccessRights();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: BackendView::PARAM_FROM_LOGIN,
                isRequired: false
            )
        );
        $user = AuthSession::isLoggedIn() ? MyAuthUser::get() : null;
        try {
            parent::__construct(
                requiredViewGroupName: 'backend',
                ipWhitelist: [],
                authUser: $user,
                requiredAccessRights: $requiredAccessRights,
                inputParameterCollection: $inputParameterCollection,
                maxAllowedPathVars: $maxAllowedPathVars
            );
        } catch (UnauthorizedAccessRightException $unauthorizedAccessRightException) {
            if (
                is_null(value: $user)
                && is_null(value: $this->getInputString(keyName: BackendView::PARAM_FROM_LOGIN))
                && ContentHandler::get()->getContentType()->isHtml()
            ) {
                MyAuthUser::setRequestedPageAfterLogin(path: HttpRequest::getURI());
                HttpResponse::redirectAndExit(relativeOrAbsoluteUri: login::getPath());
            }
            throw $unauthorizedAccessRightException;
        }
        if (!is_null(value: $user)) {
            DbAuthSession::updateLastAction(ID: AuthSession::getAuthSessionID());
        }
    }

    public function execute(): void
    {
        $htmlDocument = HtmlDocument::get();
        $this->prepareHtmlDocument(htmlDocument: $htmlDocument);
        foreach ($this->activeHtmlIdList as $key => $val) {
            $htmlDocument->setActiveHtmlId(key: $key, val: $val);
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addHtmlText(
            identifier: 'pageTitle',
            htmlText: $this->getPageTitle()
        );
        $replacements->addEncodedText(
            identifier: 'backendName',
            content: ProjectSettings::BACKEND_NAME
        );
        $replacements->addEncodedText(
            identifier: 'frontendHref',
            content: ProjectSettings::FRONTEND_HREF
        );
        $replacements->addEncodedText(
            identifier: 'frontendName',
            content: ProjectSettings::FRONTEND_NAME
        );
        if (!AuthSession::isLoggedIn()) {
            $replacements->addBool(
                identifier: 'isLoggedIn',
                booleanValue: false
            );
            $replacements->addEncodedText(
                identifier: 'breadcrumb',
                content: null
            );
            return;
        }
        /*
        $myAuthUser = MyAuthUser::get();
        $mainNavigation = BackendNavigation::get(myAuthUser: $myAuthUser);
        $replacements->addBool(
            identifier: 'isLoggedIn',
            booleanValue: true
        );
        $replacements->addEncodedText(
            identifier: 'firstPageHref',
            content: $myAuthUser->getFirstAllowedPage()
        );
        $replacements->addHtmlDataObjectCollection(
            identifier: 'mainNavigation',
            htmlDataObjectCollection: $mainNavigation->navigationItemCollection->prepareForRenderer(
                activeSubNavigationItem: array_key_exists(
                    key: 1,
                    array: $this->activeHtmlIdList
                ) ? $this->activeHtmlIdList[1] : ''
            )
        );
        $replacements->addUnencodedText(
            identifier: 'userName',
            content: $myAuthUser->getUserName()
        );
        if ($myAuthUser->isSessionChange()) {
            $replacements->addEncodedText(
                identifier: 'cancelSessionChangeLink',
                content: overview::getCancelSessionChangePath()
            );
        } else {
            $replacements->addEncodedText(identifier: 'cancelSessionChangeLink', content: '');
        }
        $replacements->addEncodedText(
            identifier: 'logoutHref',
            content: logout::getPath()
        );
        if ($this->useNavigator) {
            $oldNavigator = new OldNavigator(
                pathVars: RequestHandler::get()->pathVars,
                navigationLevels: $this->activeHtmlIdList
            );
            if ($this->resetNavigator) {
                $oldNavigator->resetBreadcrumb();
            }
            $oldNavigator->addBreadcrumb(title: $this->getPageTitle()->render());
            foreach ($oldNavigator->setNavistufe() as $key => $val) {
                $htmlDocument->setActiveHtmlId(key: $key, val: $val);
            }
            $breadcrumb = $oldNavigator->getBreadcrumb();
        } else {
            $breadcrumb = null;
        }

        $replacements->addEncodedText(identifier: 'breadcrumb', content: $breadcrumb);
        $replacements->addBool(identifier: 'useQuill', booleanValue: $this->useQuill);
        */
    }

    abstract protected static function getRequiredAccessRights(): AccessRightCollection;

    abstract protected function getPageTitle(): HtmlText;

    abstract protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void;
}