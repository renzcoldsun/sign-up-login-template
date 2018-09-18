<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
define('tAccess', TRUE);
define('dAccess', TRUE);
include_once('functions.inc.php');
doAuth();
if(isset($action)) {
    if($action == "save_profile") {
        doSaveProfile();
    }
}
include_once("admin/dashboard.inc.php");
