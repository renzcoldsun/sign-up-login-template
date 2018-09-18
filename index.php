<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
define('tAccess', TRUE);
include("functions.inc.php");

doAuth();

if(isset($action)) {
    $action = strtolower($action);
    if($action == "signup") {
        if(signup_sanitize())
            signup_save();
    }
    if($action == "signin") {
        doSignIn();
    }
}
include_once('signup.inc.php');
