<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\backend\php;

use framework\auth\AccessRightCollection;
use framework\auth\AuthSession;
use framework\core\HttpResponse;
use framework\html\HtmlDocument;
use framework\html\HtmlText;
use site\libs\auth\form\LoginForm;
use site\libs\auth\MyAuthUser;
use site\settings\ProjectSettings;
use site\view\BackendView;

class login extends BackendView
{
    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createEmpty();
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Anmelden');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        if (AuthSession::isLoggedIn()) {
            MyAuthUser::get()->redirectToFirstAllowedPage();
        }
        $htmlDocument->templateName = 'authentication';
        $replacements = $htmlDocument->replacements;
        $loginForm = new LoginForm();
        if ($loginForm->process()) {
            HttpResponse::redirectAndExit(relativeOrAbsoluteUri: loginToken::getPath());
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $loginForm->render()
        );
    }

    public function oldExecute()
    {
        $status = '';
        $loginemail = '';
        $loginpasswort = '';

        $fehlerArr = [];

        AuthSession::logOut();

        if (isset($_GET['send'])) {
            if (!isset($_POST['loginemail']) || $_POST['loginemail'] == '') {
                $fehlerArr[] = 'Geben Sie Ihre E-Mail-Adresse ein.';
            } else {
                $loginemail = $_POST['loginemail'];
            }

            if (!isset($_POST['loginpasswort']) || $_POST['loginpasswort'] == '') {
                $fehlerArr[] = 'Geben Sie Ihr Passwort ein.';
            } else {
                $loginpasswort = $_POST['loginpasswort'];
            }

            if (count($fehlerArr) == 0) {

                $sql = "
  	SELECT
  	  b.ID, b.wronglogin, b.passwort, b.confirmed, b.accepted, b.vorname, b.nachname, b.aktiv, b.admin, b.vorstand, b.redaktor
  	  
  	FROM
  	  benutzer b
  	  
  	WHERE
  	  b.email=?
  	";
                $qry = $this->db->prepareAndExecute($sql, [$loginemail]);
                $db_passwort = md5($loginpasswort);

                if ($qry->rowCount() != 1) {
                    $fehlerArr[] = 'Sie haben ungültige Zugangsdaten eingegeben.';
                } else {
                    $personData = $qry->fetchObject();
                    if ($personData->confirmed == '0000-00-00 00:00:00') {
                        $fehlerArr[] = 'Sie haben Ihre Registrierung noch nicht bestätigt.';
                    } else if ($personData->accepted == '0000-00-00 00:00:00') {
                        $fehlerArr[] = 'Ihr Zugang wurde noch nicht durch uns freigeschaltet.';
                    } else if ($personData->aktiv == 0) {
                        $fehlerArr[] = 'Dieser Zugang ist leider nicht aktiv.';
                    } else if ($personData->wronglogin >= 10) {
                        $href = "keinpw.html";
                        $fehlerArr[] = 'Bei diesem Konto wurde zehnmal hintereinander das falsche Passwort eingegeben. Falls Sie dies nicht waren, muss jemand anderes versucht haben, sich mit Ihren Zugangsdaten einzuloggen. Bitte geben Sie bei <a href="' . $href . '">Passwort vergessen?</a> Ihre E-Mail-Adresse ein. Sie erhalten dann eine E-Mail mit einem bestimmten Link, wo Sie ein neues Passwort wählen können.';
                    } else if ($personData->passwort != $db_passwort) {
                        $fehlerArr[] = 'Sie haben ungültige Zugangsdaten eingegeben.';
                        $this->db->prepareAndExecute("UPDATE benutzer SET wronglogin=wronglogin+1 WHERE ID=?", [$personData->ID]);
                    } else {
                        $ip = '';
                        if (isset($_SERVER['REMOTE_ADDR'])) {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }
                        $this->db->prepareAndExecute("UPDATE benutzer SET lastlogin=NOW(), wronglogin=0, visits=visits+1 WHERE ID=?",
                            [$personData->ID]);
                        $this->db->prepareAndExecute("INSERT INTO visits SET benutzerID=?, sessionID=?, ip=?",
                            [$personData->ID, session_id(), $ip]);

                        $vArr = [];
                        $sql = "SELECT vereinID FROM benutzervereine WHERE benutzerID=?";
                        $qry = $this->db->prepareAndExecute($sql, [$personData->ID]);
                        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
                            $vArr[] = $res['vereinID'];
                        }
                        $personData->vereine = $vArr;

                        unset($personData->wronglogin);
                        unset($personData->passwort);

                        $_SESSION = [];
                        $_SESSION['userData'] = $personData;
                        $_SESSION['intAccess'] = true;

                        AbstractSessionHandler::getSessionHandler()->regenerateID();

                        $this->showPage->redirect("start.html");
                    }
                }
            }
        }

        if (count($fehlerArr) != 0) {
            $status = "<div id=\"formfehler\"><ul>\n";
            foreach ($fehlerArr as $val) {
                $status .= "<li>{$val}</li>\n";
            }
            $status .= '</ul></div>';
        }

        $this->placeholders['loginemail'] = $loginemail;
        $this->placeholders['loginpasswort'] = $loginpasswort;
        $this->placeholders['status'] = $status;
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'login.html';
    }
}
