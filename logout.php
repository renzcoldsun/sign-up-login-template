<?php
session_start();
global $mySessionKey;
$mySessionKey = 'MBxS}W8wW1yyyMc+g]gf>WhZ#w+U:c';
if(isset($_SESSION[$mySessionKey])) {
    unset($_SESSION[$mySessionKey]);
    header("location:index.php");
}