<?php 
if(!defined('tAccess')) die("Unable to continue, direct access not allowed");

define('dbuser', 'imtrader');
define('dbpass', 'Stomata1968@');
define('dbname', 'dlpclientdatabase');
define('dbhost', 'localhost');
define('dbport', NULL);
define('dbsocket', NULL);
define('captcha_secret', '6LeUwW4UAAAAAJ4IeZWgAA8EvHDyx9mfym3Pe6Do');
define('catpcha_url', 'https://www.google.com/recaptcha/api/siteverify');

### for php mailer
define('mailhost', 'smtp.gmail.com');
define('mailport', 587);
define('mailsecure', 'tlz');
define('mailuser', 'lsalmingo@salmingo.com');
define('mailpass', '**TaNg4EVER::SALMINGO');

$page_errors = Array();
$messages = Array();
$messages["success"] = Array();
$messages["errors"] = Array();
$messages["login_errors"] = Array();
$GLOBALS["messages"] = $messages;
$GLOBALS["page_errors"] = $page_errors;
