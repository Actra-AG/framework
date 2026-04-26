<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\backend\libs\db\DbAuthGroupRepository;
use actra\backend\view\backend\php\visits;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\InputParameter;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\db\DbMemberRepository;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class member extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_REMOVE,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_ADDED,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_CHANGED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            maxAllowedPathVars: 1,
            activeHtmlIdList: [
                'members',
            ],
            useNavigator: true
        );
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::MANAGE_USERS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Mitglied');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbMember = DbMemberRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbMember === null) {
            throw new NotFoundException();
        }
        $dbAuthUser = $dbMember->dbAuthUser;
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'memberModHref',
            content: ''
        );
        $replacements->addDataObject(
            identifier: 'member',
            htmlDataObject: $dbMember->render()
        );
        $replacements->addEncodedText(
            identifier: 'removeHref',
            content: '?' . member::PARAM_REMOVE
        );
        $replacements->addBool(
            identifier: 'added',
            booleanValue: !is_null(value: $this->getInputString(keyName: member::PARAM_ADDED))
        );
        $replacements->addBool(
            identifier: 'changed',
            booleanValue: !is_null(value: $this->getInputString(keyName: member::PARAM_CHANGED))
        );
        $replacements->addUnencodedText(
            identifier: 'firstName',
            content: $dbAuthUser->firstName
        );
        $replacements->addUnencodedText(
            identifier: 'lastName',
            content: $dbAuthUser->lastName
        );
        $replacements->addEncodedText(
            identifier: 'email',
            content: $dbAuthUser->email
        );
        $replacements->addEncodedText(
            identifier: 'registered',
            content: $dbAuthUser->registered->format(format: 'd.m.Y H:i:s')
        );
        $replacements->addEncodedText(
            identifier: 'invitedDate',
            content: $dbAuthUser->isInvited() ? $dbAuthUser->invitedDate->format(format: 'd.m.Y H:i:s') : ''
        );
        $replacements->addEncodedText(
            identifier: 'lastLogin',
            content: $dbAuthUser->renderLastLogin()
        );
        $replacements->addEncodedText(
            identifier: 'visitsHref',
            content: visits::getPath(userID: $dbAuthUser->ID)
        );
        $replacements->addHtmlDataObjectCollection(
            identifier: 'userGroups',
            htmlDataObjectCollection: DbAuthGroupRepository::listByUserID(userID: $dbAuthUser->ID)->render()
        );
        $replacements->addEncodedText(
            identifier: 'active',
            content: $dbAuthUser->isActive ? 'ja' : 'nein'
        );
        $replacements->addEncodedText(
            identifier: 'events',
            content: ''
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'member-' . $ID . '.html';
    }

    /*
	public function execute(): void {
		$status = '';
		$confirm = '';
		$ID = 0;
		$jahresprogramm = '';
		$liste = '';

		$datenArr['verein'] = '';
		$datenArr['anrede'] = '';
		$datenArr['vorname'] = '';
		$datenArr['nachname'] = '';
		$datenArr['strasse'] = '';
		$datenArr['plz'] = '';
		$datenArr['ort'] = '';
		$datenArr['lizenz'] = '';
		$datenArr['telefon'] = '';
		$datenArr['email'] = '';
		$datenArr['geburtsdatum'] = '';
		$datenArr['kommentar'] = '';
		$datenArr['bemerkungen'] = '';
		$datenArr['ehrenmitglied'] = '';
		$datenArr['registered'] = '';
		$datenArr['confirmed'] = '';
		$datenArr['lastlogin'] = '';
		$datenArr['zugriff'] = '';
		$datenArr['aktiv'] = '';
		$datenArr['admin'] = '';
		$datenArr['redaktor'] = '';
		$datenArr['vorstand'] = '';
		$datenArr['wronglogin'] = '';
		$datenArr['visits'] = '';

		if ($this->showPage->checkUG('admin')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "
	SELECT
	  v.name AS verein, b.anrede, b.vorname, b.nachname, b.strasse, b.plz, b.ort, b.lizenz, b.telefon, b.email, IF(b.geburtsdatum='0000-00-00', '', DATE_FORMAT(b.geburtsdatum, '%d.%m.%Y')) AS geburtsdatum, kommentar, bemerkungen, IF(b.ehren=0, 'nein', 'ja') AS ehren, IF(b.ernannt='', '', CONCAT(' (', ernannt, ')')) AS ernannt, IF(b.registered='0000-00-00', '', DATE_FORMAT(b.registered, '%d.%m.%Y')) AS registered, IF(b.confirmed='0000-00-00', 'unbestätigt', DATE_FORMAT(b.confirmed, '%d.%m.%Y')) AS confirmed, IF(b.lastlogin='0000-00-00', '', DATE_FORMAT(b.lastlogin, '%d.%m.%Y')) AS lastlogin, IF(b.accepted!='0000-00-00 00:00:00', 'akzeptiert', IF(b.denied!='0000-00-00 00:00:00', 'abgelehnt', 'zu prüfen')) AS zugriff, IF(b.aktiv=0, 'nein', 'ja') AS aktiv, IF(b.admin=0, 'nein', 'ja') AS admin, IF(b.redaktor=0, 'nein', 'ja') AS redaktor, IF(b.vorstand=0, 'nein', 'ja') AS vorstand, b.wronglogin, b.visits
	  
	FROM
	  benutzer b
	  LEFT JOIN vereine v ON b.vereinID=v.ID
	  
	WHERE
	  b.ID=?
	";
			$qry = $this->db->prepareAndExecute($sql, [$ID]);
			if ($qry->rowCount() == 0) {
				$this->showPage->redirect("benutzer.html");
			}
			$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

			if ($datenArr['zugriff'] == 'zu prüfen') {
				$href1 = "accept-{$ID}.html";
				$href2 = "deny-{$ID}.html";
				$confirm = "<p>Dieser Antrag ist noch offen! <a href=\"{$href1}\">akzeptieren</a> | <a href=\"{$href2}\">ablehnen</a></p>";
			}

			$cjp = [];
			$sql = "SELECT vereinID FROM benutzervereine WHERE benutzerID=?";
			$qry = $this->db->prepareAndExecute($sql, [$ID]);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$cjp[] = $res['vereinID'];
			}

			$vArr = [];
			$vArr[0] = 'keiner';
			$sql = "SELECT ID, name FROM vereine ORDER BY name";
			$qry = $this->db->prepareAndExecute($sql);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$vArr[$res['ID']] = $res['name'];
			}

			$xArr = [];
			foreach ($cjp AS $vID) {
				if (isset($vArr[$vID])) {
					$xArr[] = "<li>{$vArr[$vID]}</li>\n";
				}
			}

			if (count($xArr) != 0) {
				$jahresprogramm = "<ul>" . implode("", $xArr) . "</ul>\n";
			}

			$cond = "WHERE p.registered_by=?";
			$paramsArr[] = $ID;

			$fArr['verein']['attributes'] = '';
			$fArr['verein']['order'] = 0;
			$fArr['verein']['ox'] = '';
			$fArr['verein']['value'] = 'Verein';

			$fArr['p.datum']['attributes'] = '';
			$fArr['p.datum']['order'] = 0;
			$fArr['p.datum']['ox'] = '';
			$fArr['p.datum']['value'] = 'Datum';

			$fArr['p.titel']['attributes'] = '';
			$fArr['p.titel']['order'] = 0;
			$fArr['p.titel']['ox'] = '';
			$fArr['p.titel']['value'] = 'Titel';

			$fArr['p.ort']['attributes'] = '';
			$fArr['p.ort']['order'] = 0;
			$fArr['p.ort']['ox'] = '';
			$fArr['p.ort']['value'] = 'Ort';

			$fArr['status']['attributes'] = '';
			$fArr['status']['order'] = 0;
			$fArr['status']['ox'] = '';
			$fArr['status']['value'] = 'Status';

			$fn = "xjp{$ID}";

			$pos = 0;
			$ox = "";
			$orderby = "p.datumVon DESC, p.datumBis DESC, p.zeit DESC";
			if (isset($_GET['pos'])) {
				$_SESSION[$fn]['pos'] = $_GET['pos'];
			}
			if (isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) {
				$_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']);
			}
			if (isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) {
				$_SESSION[$fn]['ox'] = $_GET['ox'];
			}
			if (isset($_SESSION[$fn]['pos'])) {
				$pos = (int)$_SESSION[$fn]['pos'];
			}
			if (isset($_SESSION[$fn]['orderby'])) {
				$orderby = $_SESSION[$fn]['orderby'];
			}
			if (isset($_SESSION[$fn]['ox'])) {
				$ox = $_SESSION[$fn]['ox'];
			}

			$sql = "
  SELECT
    COUNT(*) AS anz
    
  FROM
    jahresprogramm p
    "."{$cond}";
			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {
				$pagination = $this->showPage->getPagenavi("benutzerDet-{$ID}", $res->anz, $pos);
				$liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$i = 0;
				$sql = "
    SELECT
      v.name AS verein, p.ID, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, IF(p.confirmed!='0000-00-00 00:00:00', 'aktiviert', IF(p.denied='0000-00-00 00:00:00', 'abgelehnt', '')) AS status
    
    FROM
      jahresprogramm p
      LEFT JOIN vereine v ON p.vereinID=v.ID
      
    "."{$cond}
    
    ORDER BY
      {$orderby} {$ox}
    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$qry = $this->db->prepareAndExecute($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {
					$i++;
					$alt = ($i % 2 == 0) ? ' class="alt"' : '';
					$datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res -> datumVon} - <br />{$res -> datumBis}";

					$href = "anlassDet-{$res -> ID}.html";
					$liste .= "<tr{$alt}><td>{$res -> verein}</td>\n<td>{$datum}</td>\n<td><a href=\"{$href}\">{$res -> titel}</a></td>\n<td>{$res -> ort}</td>\n<td>{$res -> status}</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['confirm'] = $confirm;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['jahresprogramm'] = $jahresprogramm;
		$this->placeholders['liste'] = $liste;

		foreach ($datenArr AS $key => $val) {
			$this->placeholders[$key] = $val;
		}
	}
    */
}