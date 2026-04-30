<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\backend\libs\db\DB;
use actra\yuf\core\HttpResponse;
use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;
use classes\FormMailer;
use framework\form\component\field\EmailField;
use framework\html\HtmlText;

class register extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Registrieren';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $status = '';
        $Herr = '';
        $Frau = '';
        $vereine = '';

        $datenArr['vereinID'] = '';
        $datenArr['anrede'] = '';
        $datenArr['vorname'] = '';
        $datenArr['nachname'] = '';
        $datenArr['strasse'] = '';
        $datenArr['plz'] = '';
        $datenArr['ort'] = '';
        $datenArr['lizenz'] = '';
        $datenArr['telefon'] = '';
        $datenArr['email'] = '';
        $datenArr['kommentar'] = '';
        $datenArr['passwort'] = '';

        $fehlerArr = [];

        $vArr = [];
        $vArr[0] = 'keiner';
        $res = DB::get()->select(sql: \"SELECT ID, name FROM vereine ORDER BY name\");
        foreach ($res as $val) {
            $vArr[$val->ID] = $val->name;
        }

        if (isset($_GET['send'])) {
            if (!isset($_POST['vereinID']) || !isset($vArr[$_POST['vereinID']])) {
                $fehlerArr[] = \"Wählen Sie einen Verein aus\";
            } else {
                $datenArr['vereinID'] = $_POST['vereinID'];
            }

            if (!isset($_POST['anrede'])) {
                $fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';
            } else {
                if ($_POST['anrede'] != 'Herr' && $_POST['anrede'] != 'Frau') {
                    $fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';
                } else {
                    $datenArr['anrede'] = $_POST['anrede'];
                }
            }

            if (!isset($_POST['vorname']) || trim($_POST['vorname']) == '') {
                $fehlerArr[] = \"Geben Sie bitte Ihren Vornamen an.\";
            } else {
                $datenArr['vorname'] = $_POST['vorname'];
            }

            if (!isset($_POST['nachname']) || trim($_POST['nachname']) == '') {
                $fehlerArr[] = \"Geben Sie bitte Ihren Nachnamen an.\";
            } else {
                $datenArr['nachname'] = $_POST['nachname'];
            }

            if (!isset($_POST['strasse']) || trim($_POST['strasse']) == '') {
                $fehlerArr[] = \"Geben Sie bitte Ihre Strasse an.\";
            } else {
                $datenArr['strasse'] = $_POST['strasse'];
            }

            if (!isset($_POST['plz']) || trim($_POST['plz']) == '') {
                $fehlerArr[] = \"Geben Sie bitte die PLZ an.\";
            } else {
                $datenArr['plz'] = $_POST['plz'];
            }

            if (!isset($_POST['ort']) || trim($_POST['ort']) == '') {
                $fehlerArr[] = \"Geben Sie bitte den Ort an.\";
            } else {
                $datenArr['ort'] = $_POST['ort'];
            }

            if (isset($_POST['lizenz'])) {
                $datenArr['lizenz'] = $_POST['lizenz'];
            }
            if (isset($_POST['telefon'])) {
                $datenArr['telefon'] = $_POST['telefon'];
            }

            $emailField = new EmailField(
                name: 'email',
                label: HtmlText::encoded(textContent: 'E-Mail'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Geben Sie bitte eine gültige E-Mail-Adresse an.'),
                requiredError: HtmlText::encoded(textContent: 'Geben Sie bitte eine E-Mail-Adresse an.')
            );
            if (!$emailField->validate(inputData: $_POST)) {
                $fehlerArr[] = $emailField->getErrorsAsHtmlTextObjects()[0]->render();
            } else {
                $datenArr['email'] = $emailField->getRawValue();
                $res = DB::get()->select(
                    sql: \"SELECT COUNT(*) AS anz FROM benutzer WHERE email=?\",
                    parameters: [$datenArr['email']]
                );
                if ((int)$res[0]->anz != 0) {
                    $fehlerArr[] = 'Die eingegebene E-Mail-Adresse ist bereits registriert. Geben Sie bitte eine andere ein.';
                }
            }

            if (isset($_POST['kommentar'])) {
                $datenArr['kommentar'] = $_POST['kommentar'];
            }

            if (!isset($_POST['passwort']) || $_POST['passwort'] == '') {
                $fehlerArr[] = 'Sie haben kein Passwort eingegeben.';
            } else {
                if (!isset($_POST['passwort_confirm']) || $_POST['passwort'] != $_POST['passwort_confirm']) {
                    $fehlerArr[] = 'Geben Sie bitte zweimal dasselbe Passwort ein.';
                } else {
                    $datenArr['passwort'] = $_POST['passwort'];
                }
            }

            if (count($fehlerArr) == 0) {
                $datenArr['ip'] = '';
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $datenArr['ip'] = $_SERVER['REMOTE_ADDR'];
                }
                $datenArr['passwort'] = md5($datenArr['passwort']);

                // Since I cannot find insertEntry, I will use direct SQL
                $sql = \"INSERT INTO benutzer SET \";
                $params = [];
                foreach ($datenArr as $key => $val) {
                    $sql .= \"{$key}=?, \";
                    $params[] = $val;
                }
                $sql .= \"registered=NOW()\"; // Assumption based on common patterns
                DB::get()->execute(sql: $sql, parameters: $params);
                $ID = DB::get()->getLastInsertId();

                $to = $_POST['email'];
                $toName = $_POST['email'];
                $from = \"webmaster@bsv-buelach.ch\";
                $fromName = \"webmaster@bsv-buelach.ch\";
                $subject = \"Ihre Registrierung bei {$_SERVER['SERVER_NAME']}\";

                $code = md5(\"bsvregister{$ID}buelach\");
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

                $text = \"Grüezi\n\nSie haben sich bei {$_SERVER['SERVER_NAME']} registriert. Bitte klicken Sie auf den folgenden Link, um dies zu bestätigen:\n\n{$protocol}://{$_SERVER['SERVER_NAME']}/backend/confirm-{$ID}-{$code}.html\n\nWenn Sie sich nicht registriert haben, ignorieren Sie diese E-Mail und klicken Sie nicht auf den obigen Link!\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach\";

                (new FormMailer())->send($to, $toName, $from, $fromName, $subject, $text);

                $to = \"webmaster@bsv-buelach.ch\";
                $toName = \"webmaster@bsv-buelach.ch\";
                $subject = \"Neue Registrierung bei {$_SERVER['SERVER_NAME']}\";

                $text = \"Grüezi\n\nEs gibt eine neue Registrierung bei {$_SERVER['SERVER_NAME']}. Bitte prüfen Sie diese und akzeptieren oder verweigern Sie den Zugriff.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach\";

                (new FormMailer())->send($to, $toName, $from, $fromName, $subject, $text);

                HttpResponse::redirectAndExit(location: \"registerRes.html\");
            }
        }

        if (count($fehlerArr) != 0) {
            $status = \"<div id=\\"formfehler\\"><ul>\";
            foreach ($fehlerArr as $val) {
                $status .= '<li>' . $val . '</li>';
            }
            $status .= '</ul></div>';
        }

        $replacements = $htmlDocument->replacements;
        foreach ($datenArr as $key => $val) {
            if ($key == 'anrede') {
                if ($val == 'Herr') $Herr = ' checked=\"checked\"';
                if ($val == 'Frau') $Frau = ' checked=\"checked\"';
            } else {
                $replacements->addEncodedText(identifier: $key, content: (string)$val);
            }
        }

        foreach ($vArr as $key => $val) {
            $vereine .= \"<option value=\\"{$key}\\"\";
            if ($key == $datenArr['vereinID']) {
                $vereine .= ' selected=\"selected\"';
            }
            $vereine .= \">{$val}</option>\n\";
        }

        $replacements->addEncodedText(identifier: 'status', content: $status);
        $replacements->addEncodedText(identifier: 'herr', content: $Herr);
        $replacements->addEncodedText(identifier: 'frau', content: $Frau);
        $replacements->addEncodedText(identifier: 'vereine', content: $vereine);
    }
}
