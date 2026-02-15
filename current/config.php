<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

# --------------
# Error Handling
# --------------

require_once 'settings/EnvSettings.php';
$config['errorEmail'] = 'error@bsv-buelach.ch';
$config['errorReporting'] = E_ALL | E_STRICT;

# --------------
# localization
# --------------

$config['locale'] = ['de_CH@euro', 'de_CH', 'de', 'ge'];
$config['default_timezone'] = "Europe/Zurich";

# ---------------
# set request URI
# ---------------

$config['reqURI'] = $_SERVER['REQUEST_URI'];
if (!strpos($config['reqURI'], ".") && !strpos($config['reqURI'], "?") && !str_ends_with($config['reqURI'], "/")) {
	$config['reqURI'] = $config['reqURI'] . "/";
}

# ----------------
# general settings
# ----------------

define('CLASS_PATH', $_SERVER['DOCUMENT_ROOT'] . '/classes/');
$config['useDB'] = true;
$config['accessLog'] = false;
$config['defaultpage'] = 'start';
$config['scriptsDir'] = $_SERVER['DOCUMENT_ROOT'] . '/scripts/';
$config['rootDir'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/';
$config['country'] = 'CH';
$config['language'] = 'de';
$config['requireSSL'] = true;

# ----------------------
# additional directories
# ----------------------

$config['allowedDir']['/backend/']['defaultpage'] = 'login';
$config['allowedDir']['/backend/']['scriptsDir'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/scripts/';
$config['allowedDir']['/backend/']['rootDir'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/';
$config['allowedDir']['/backend/']['country'] = 'CH';
$config['allowedDir']['/backend/']['language'] = 'de';

# -------------------------------
# default settings for pagination
# -------------------------------

$config['lists']['entriesPerPage'] = 25;
$config['lists']['minusplus'] = 2;
$config['lists']['startend'] = 1;

# ---------------------------
# additional project settings
# ---------------------------

$config['defaultSpracheID'] = 1;
$config['extension']['cryptLinks']['key'] = '';

# -------------------
# additional services
# -------------------

$config['services'] = [];
$config['services']['dokumente'] = '';
$config['services']['calendar'] = '';