<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class eidg07start extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidgenössisches Schützenfest 2007 im Tessin';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $sql = "SELECT n.ID, n.titel, n.teaser, n.text, DATE_FORMAT(n.datum, '%d.%m.%Y') AS datum, DATE_FORMAT(n.datum, '%T') AS zeit FROM news n WHERE n.archiv='0' AND n.typ=2 ORDER BY n.datum DESC LIMIT 0,3";
        $qry = $this->db->prepareAndExecute($sql);
        if ($qry->rowCount() == 0) {
            $eidgnews = '<span>keine Neuigkeiten</span>';
        } else {
            $ldate = '';
            $eidgnews = '<ul>';
            while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

                if ($ldate == $res['datum'] || $ldate == '') {
                    $href = 'newsDetails-' . $res['ID'] . '.html';
                    $eidgnews .= '<li><span><a href="' . $href . '">' . $res['titel'] . '</a></span> <em>' . $res['datum'] . '</em></li>';
                    $ldate = $res['datum'];
                }
            }
            $eidgnews .= '</ul>';
        }

        $sql = "
SELECT
  f.ID AS fotoID, k.ID AS albumID
  
FROM
  fotos f
  INNER JOIN alben k ON f.albumID=k.ID
  
WHERE
  k.typ = 2

ORDER BY
  f.registered DESC
  
LIMIT 0,1
";
        $qry = $this->db->prepareAndExecute($sql);
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        $href = 'eidg07foto-' . $res['albumID'] . '-' . $res['fotoID'] . '.html';
        $src = '/galerie/tnfoto' . $res['fotoID'] . '.jpg';
        $foto = '<a href="'.$href.'"><img src="'.$src.'" width="125" height="90" alt="" /></a>';

        $this->placeholders['foto'] = $foto;
        $this->placeholders['datum'] = (new DateTimeImmutable())->format(format: 'd.m.Y');
        $this->placeholders['eidgnews'] = $eidgnews;
    }
}
