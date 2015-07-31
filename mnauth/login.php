<?php
/**
 * @author METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2015, METANET AG
 */
require_once('config.php');
if(isset($sessionName) && $sessionName != '') {
	session_name($sessionName);
}
session_start();

if(isset($_POST['auth'])) {
	require_once('header.php');
	$email = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
	$url = "https://cron.metanet.ch/mnauth/check?site=".urlencode($site)."&email=".urlencode($email);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$body = curl_exec($ch);
	$header = curl_getinfo($ch);
	curl_close($ch);

	$xml = simplexml_load_string($body);

	if(!isset($xml->access) || (string)$xml->access != 'true' || !isset($xml->token)) {
		echo '<p>Ungültige E-Mail-Adresse.</p><p><a href="?retry">Erneut versuchen</a></p>';
	} else {
		$_SESSION['mnauth']['email'] = $email;
		$_SESSION['mnauth']['token'] = (string)$xml->token;
		echo '<p><input type="text" name="token" placeholder="Token"> <input type="submit" name="login" value="einloggen"></p>';
	}
	require_once('body.php');

} elseif(isset($_POST['login'])) {
	$email = isset($_SESSION['mnauth']['email']) ? $_SESSION['mnauth']['email'] : '';
	$token = isset($_REQUEST['token']) ? (int)$_REQUEST['token'] : 0;

	if($token == 0 || !isset($_SESSION['mnauth']['token']) || $token != $_SESSION['mnauth']['token'] || $email == '') {
		$_SESSION['mnauth']['token'] = '';
		$_SESSION['mnauth']['access'] = false;
		require_once('header.php');
		echo '<p>Ungültiges Token.</p><p><a href="?retry">Erneut versuchen</a></p>';
		require_once('body.php');
	} else {
		$_SESSION['mnauth']['token'] = '';
		$_SESSION['mnauth']['access'] = true;
		header("Location: ".$loginPage);
		exit;
	}

} else {
	require_once('header.php');
	echo '<p><input type="text" name="email" placeholder="E-Mail-Adresse"> <input type="submit" name="auth" value="Token senden"></p>';
	require_once('body.php');
}
/* EOF */