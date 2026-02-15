<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

namespace classes;

class ShowPage extends RequestHandler
{
	public $pageArr;

	public function getConfig()
	{
		$grundkonf = [];
		$platzhalter = [];
		$navistufe = [];

		$file = $this->config['rootDir'] . 'config/' . $this->reqArr['varFiletitle'] . '.php';

		if (!file_exists($file)) {
			ErrorHandler::display_error(404);
		}
		require_once($file);

		$platzhalter['bodyid'] = 'body_' . $this->reqArr['varFiletitle'];

		$this->pageArr['grundkonf'] = $grundkonf;
		$this->pageArr['platzhalter'] = $platzhalter;
		$this->pageArr['navistufe'] = $navistufe;
	}

	public function getContent()
	{
		$file = $this->config['rootDir'] . 'pages/' . $this->reqArr['varFiletitle'] . '.html';

		if (!file_exists($file)) {
			ErrorHandler::display_error(404);
		}
		$this->pageArr['contentArr'] = file($file);
	}

	public function checkScripts()
	{
		$file = $this->config['scriptsDir'] . $this->reqArr['varFiletitle'] . '.php';

		if (!file_exists($file)) {
			return false;
		} else {
			$this->pageArr['dynPage'] = $file;

			return true;
		}
	}

	public function getTemplate()
	{
		$templateID = $this->pageArr['grundkonf']['templateID'];
		if ($templateID == 0) {
			$this->pageArr['templateArr'][] = '{{CONTENT}}';
		} else {
			$fileS = $this->config['rootDir'] . 'templates/template' . $templateID . '.html';
			$fileD = $this->config['scriptsDir'] . 'template' . $templateID . '.php';

			if (!file_exists($fileS)) {
				ErrorHandler::display_error(404);
			}
			$this->pageArr['templateArr'] = file($fileS);
			if (file_exists($fileD)) {
				$this->pageArr['dynTemplate'] = $fileD;

				return true;
			}
		}

		return false;
	}

	public function output()
	{
		$fullArr = [];

		foreach ($this->pageArr['templateArr'] as $line) {
			if (preg_match_all("{{CONTENT}}", $line)) {
				foreach ($this->pageArr['contentArr'] as $line1) {
					$fullArr[] = $line1;
				}
			} else {
				$fullArr[] = $line;
			}
		}

		$search = [];
		$replace = [];
		$i = 0;
		foreach ($this->pageArr['platzhalter'] as $key => $val) {
			$i++;
			$search[$i] = "{{" . strtoupper($key) . "}}";
			$replace[$i] = $val;
		}

		$stufen = [];
		foreach ($this->pageArr['navistufe'] as $stufeKey => $stufeVar) {
			$stufen[$stufeVar] = $stufeKey;
			$i++;
			$search[$i] = 'id="nav_' . $stufeVar . '"';
			$replace[$i] = 'class="nav_current"';
		}

		$fullHTML = '';
		$dontDisplay = [];

		foreach ($fullArr as $line) {
			if (preg_match_all("<!-- sub_([a-zA-Z0-9]*) START -->", $line, $regs)) {
				$stufenname = $regs[1][0];
				if (!array_key_exists($stufenname, $stufen)) {
					$dontDisplay[$stufenname] = 1;
				}
			}

			if (preg_match_all("<!-- UG_VISITOR START -->", $line, $regs2)) {
				if ($this->checkAccess()) {
					$dontDisplay['onlyvisitor'] = 1;
				}
			} else if (preg_match_all("<!-- UG_([A-Z]*) START -->", $line, $regs2)) {
				$usergroup = strtolower($regs2[1][0]);
				if (!$this->checkUG($usergroup)) {
					$dontDisplay['only' . $usergroup . 'content'] = 1;
				}
			} else if (preg_match_all("<!-- NOTUG_([A-Z]*) START -->", $line, $regs2)) {
				$usergroup = strtolower($regs2[1][0]);
				if ($this->checkUG($usergroup)) {
					$dontDisplay['onlynot' . $usergroup . 'content'] = 1;
				}
			}

			if (count($dontDisplay) == 0) {
				$fullHTML .= $line;
			}

			if (preg_match_all("<!-- sub_([a-zA-Z0-9]*) ENDE -->", $line, $regs)) {
				$stufenname = $regs[1][0];
				if (array_key_exists($stufenname, $dontDisplay)) {
					unset($dontDisplay[$stufenname]);
				}
			}

			if (preg_match_all("<!-- UG_VISITOR ENDE -->", $line, $regs2)) {
				if (array_key_exists('onlyvisitor', $dontDisplay)) {
					unset($dontDisplay['onlyvisitor']);
				}
			} else if (preg_match_all("<!-- UG_([A-Z]*) ENDE -->", $line, $regs2)) {
				$usergroup = strtolower($regs2[1][0]);
				if (array_key_exists('only' . $usergroup . 'content', $dontDisplay)) {
					unset($dontDisplay['only' . $usergroup . 'content']);
				}
			} else if (preg_match_all("<!-- NOTUG_([A-Z]*) ENDE -->", $line, $regs2)) {
				$usergroup = strtolower($regs2[1][0]);
				if (array_key_exists('onlynot' . $usergroup . 'content', $dontDisplay)) {
					unset($dontDisplay['onlynot' . $usergroup . 'content']);
				}
			}
		}

		$fullHTML = str_replace($search, $replace, $fullHTML);
		echo $fullHTML;
	}

	public function getPagenavi($link, $anzObjekte, $pos = 0, $proSeite = false, $minusplus = false, $plusstartende = false)
	{
		$proSeite = (!$proSeite) ? $this->config['lists']['entriesPerPage'] : $proSeite;
		$minusplus = (!$minusplus) ? $this->config['lists']['minusplus'] : $minusplus;
		$plusstartende = (!$plusstartende) ? $this->config['lists']['startend'] : $plusstartende;

		$seiten = "";
		$sub = $anzObjekte % $proSeite;
		if ($sub == 0) {
			$lastPos = $anzObjekte - $proSeite;
		} else {
			$lastPos = $anzObjekte - ($anzObjekte % $proSeite);
		}

		if ($anzObjekte > $proSeite) {
			$seiten .= "<div class=\"pagination\">\n<ul>\n";
			if ($pos == 0) {
				$seiten .= "<li class=\"zurueckdisable\">&laquo; zurück</li>\n";
			} else {
				$bl = $pos - $proSeite;
				$href = "{$link}.html?pos={$bl}";
				$seiten .= "<li class=\"zurueck\"><a href=\"{$href}\">&laquo; zurück</a></li>\n";
			}

			for ($i = 0; $i < $anzObjekte; $i = $i + $proSeite) {
				if ($i == 0 || $i == $pos || $i == $lastPos || $i <= $proSeite * $plusstartende || $i >= $lastPos - ($proSeite * $plusstartende) || ($i < $pos && $i >= ($pos - ($minusplus * $proSeite))) || ($i > $pos && $i <= ($pos + ($minusplus * $proSeite)))) {

					if ($i == $lastPos - ($proSeite * $plusstartende) && $pos < $lastPos - $proSeite - ($proSeite * $minusplus) - ($proSeite * $plusstartende)) {
						$seiten .= "<li><span>...</span></li>\n";
					}
					$nr = ($i / $proSeite) + 1;
					if ($i == $pos) {
						$seiten .= "<li class=\"currentpage\"><strong>{$nr}</strong></li>\n";
					} else {
						$href = "{$link}.html?pos={$i}";
						$seiten .= "<li><a href=\"{$href}\">{$nr}</a></li>\n";
					}
					if ($i == $proSeite * $plusstartende && $pos > 0 + $proSeite + ($proSeite * $minusplus) + ($proSeite * $plusstartende)) {
						$seiten .= "<li><span>...</span></li>\n";
					}
				}
			}
			if ($pos == $lastPos) {
				$seiten .= "<li class=\"vordisable\">vor &raquo;</li>\n";
			} else {
				$nl = $pos + $proSeite;
				$href = "{$link}.html?pos={$nl}";
				$seiten .= "<li class=\"zurueck\"><a href=\"{$href}\">vor &raquo;</a></li>\n";
			}
			$seiten .= "</ul></div>\n";
		}

		return $seiten;
	}

	public function dynTableHeader($fArr, $orderby = '', $ox = '')
	{
		$th = "<tr>\n";
		foreach ($fArr as $key => $val) {
			$th .= "<th scope=\"col\" {$val['attributes']}>";
			if ($val['order'] == 1) {
				$nox = $val['ox'];
				if ($orderby == $key && $ox == $val['ox']) {
					if ($val['ox'] == "ASC") {
						$nox = "DESC";
					} else {
						$nox = "ASC";
					}
				}
				$th .= "<a href=\"?orderby={$key}&amp;ox={$nox}\">{$val['value']}</a>";
			} else {
				$th .= $val['value'];
			}
			$th .= "</th>\n";
		}
		$th .= "</tr>\n";

		return $th;
	}

	function bytestostring($size, $precision = 3)
	{
		$sizes = ['YB', 'ZB', 'EB', 'PB', 'TB', 'GB', 'MB', 'KB', 'B'];
		$total = count($sizes);

		while ($total-- && $size > 1024) {
			$size /= 1024;
		}

		return round($size, $precision) . $sizes[$total];
	}
}