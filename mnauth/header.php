<?php
/**
 * @author METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2015, METANET AG
 */

header('Content-Type: text/html; charset=' . $charset);

echo '<!DOCTYPE html>';
echo '<html lang="de">';
echo '<head>';
echo '<meta charset="' . $charset . '">';
echo '<title>MNAUTH</title>';
echo '<meta name="author" content="METANET AG, Zürich, www.metanet.ch">';
echo '<meta name="robots" content="noindex,nofollow">';
echo '</head>';
echo '<body>';

echo '<form method="post" action="?send">';

/* EOF */