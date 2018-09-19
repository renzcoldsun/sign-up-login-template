<?php


function generate_hash($username, $password, $algo = "sha512") {
	$pref = "pbkdf2:|:";
        if($username == "" || $username == NULL) return "";
        if($password == "" || $password == NULL) return "";
        if(!function_exists('hash_pbkdf2')) die("Unable to continue, hash_pbkdf2 is a requirement");
        if($algo == "" || $algo == NULL) {
            $algos = hash_algos();
            $algo = $algos[count($algos) - 1]; 
        }
	$salt = generate_salt($username, $password);
        $hashed = $pref  . $algo . $pref  . hash_pbkdf2($algo, $password, $salt, 64);
        return $hashed;
    }

function generate_salt($username, $password) {
        $salt = "";
        if(strlen($username) < 4) $username = substr(str_repeat($username, 100), 0, 20);
        $salt .= substr($username, 0, 6);
        if(strlen($password) < 20) $password = substr(str_repeat($password, 100), 0, 20);
        $salt .= substr($password, 14, 6);
        return $salt;
    }

$generated = generate_hash("renz", "renz1234");
$db = "pbkdf2:|:sha512pbkdf2:|:b4b6540d151e28369a6a96c1fd86c234b2b0403c4bb66b8a38ce893d37a45c7ccb34a8ce1b8ca6802a95a39fdc8c4e5c42fdeae3c6873b5d45e4366b2bd56e3c";
echo ($generated == $db) ? "TRUE" : "FALSE";

// pbkdf2:|:sha512pbkdf2:|:b4b6540d151e28369a6a96c1fd86c234b2b0403c4bb66b8a38ce893d37a45c7ccb34a8ce1b8ca6802a95a39fdc8c4e5c42fdeae3c6873b5d45e4366b2bd56e3c
