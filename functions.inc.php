<?php 
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(!defined('tAccess')) die("Unable to continue, direct access not allowed");
include_once("config.inc.php");
$mySessionKey = 'MBxS}W8wW1yyyMc+g]gf>WhZ#w+U:c';
global $mySessionKey;

foreach($_GET as $key => $value)
{
    $dataKey = preg_replace("/[^A-Za-z0-9_]+/", "", $key);
    $val = htmlspecialchars($value);
    $GLOBALS[$dataKey] = $val;
}

foreach($_POST as $key => $value)
{
    $dataKey = preg_replace("/[^A-Za-z0-9_]+/", "", $key);
    $val = htmlspecialchars($value);
    global ${$dataKey};
    $GLOBALS[$dataKey] = $val;
    ${$dataKey} = $val;
}


function connectDB() {
    if(dbsocket != NULL)
        $db = new mysqli(dbhost, dbuser, dbpass, dbname, NULL, dbsocket);
    else
        if(dbport != NULL)
            $db = new mysqli(dbhost, dbuser, dbpass, dbname, dbport, NULL);
        else
            $db = new mysqli(dbhost, dbuser, dbpass, dbname);
    if(mysqli_connect_errno()) {
        die("Database error" . mysqli_connect_errno() );
        return NULL;
    }
    return $db;
}

function showPageErrors($key) {
    global $page_errors;
    if(isset($page_errors[$key])) return $page_errors[$key];
    return "";
}

function verifyCaptcha($captchaValue="") {
    $result = false;
    if($captchaValue == "") {
        if(isset($grecaptcharesponse)) $captchaValue = $grecaptcharesponse;
        else return $result;
    }
    if($captchaValue == "") return $result;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, catpcha_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    $post_fields = http_build_query(array(
        "secret" => captcha_secret,
        "response" => $captchaValue
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $server_output = curl_exec($ch);
    curl_close($ch);
    if($server_output != "") return $result;
    $jsonobj = json_decode($server_output, true);
    if($jsonobj == NULL) return $result;
    if(property_exists($jsonobj, 'success')) {
        if($jsonobj->success == "true") $result = true;
    }
    return $result;
}

/** SIGNUP ROUTINES **/
function signup_sanitize() {
    $result = true;
    global $username, $phone_number, $password, $email, $first_name, $last_name, $messages, $page_errors;
    /*
    if(check_rows('username', $username, true)) {
        $result = false;
        $messages["errors"][] = "Username is already taken: " . $username;
        $page_errors["signup_username"] = "Username is already taken: " . $username;
    }
    */
    /*
    if(check_rows('phone_number', $phone_number, true)) {
        $result = false;
        $messages["errors"][] = "Phone Number is already used: " . $phone_number;
        $page_errors["signup_phone_number"] = "Phone Number is already used: " . $phone_number;
    }
    */
    if($email != "" && $email != NULL) {
        if(check_rows('email', $email, true)) {
            $result = false;
            $messages["errors"][] = "Email is already used: " . $email;
            $page_errors["signup_email"] = "Email is already used: " . $email;
        }
    }

    return $result;
}

function check_rows($field, $value, $check_exists, $return_rows = false) {
    $result = false;
    $db = connectDB();
    $sql = "SELECT * FROM dlpclienttable WHERE `" . $field . "` = '" . $value . "'";
    if($stmt = $db->prepare($sql)) {
        $res = $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        if(mysqli_connect_errno()) {
            die("Database error" . mysqli_connect_errno() );
        }
        if($check_exists) $result = ($stmt->num_rows() > 0) ? true : false;
        if(!$check_exists) $result = ($stmt->num_rows() <= 0) ? true : false;
    }
    if($return_rows) {
        $db->close();
        $db = connectDB();
        $result = Array();
        if($query = $db->query($sql)) {
            $count = 0;
            while($row = $query->fetch_assoc()) {
                $record = Array();
                foreach($row as $column_name => $value) {
                    $record[$column_name] = $value;
                }
                $result[$count] = $record;
                $counnt++;
            }
        }
    } else {
        $db->close();
    }
    return $result;
}


function signup_save() {
    global $mySessionKey;
    $db = connectDB();
    if($db == NULL) die("Cannot continue");
    global $phone_number, $password, $email, $first_name, $last_name, $messages;
    $account_number = 1000;
    $domain = 'DLPT$TRIAL1$';
    $domain_id = 1;


    # get the account nnumber
    $user_count = 0;
    while(true)
    {
        $db = connectDB();
        $sql = "SELECT count(*) as user_count FROM dlpclienttable WHERE account_number = " . ((int) $account_number);
        if($query = $db->query($sql)) {
            while($row = $query->fetch_assoc()) {
                $user_count = (int) $row["user_count"];
            }
            if($user_count <= 0) break;
            $account_number++;
        } else {
            break;
        }
        $db->close();
    }

    # get the domain
    $db = connectDB();
    while(true) {
        $domain = 'DLPT$TRIAL' . $domain_id . '$';
        $sql = "SELECT count(*) AS domain_count FROM dlpclienttable WHERE domain='${domain}'";
        if($query = $db->query($sql)) {
            if($query->num_rows <= 0) break;
            while($row = $query->fetch_assoc()) {
                $domain_count = (int) $row["domain_count"];
            }
            if($domain_count <= 250) break;
        } else {
            break;
        }
        $domain_id++;
    }
    $db->close();

    $db = connectDB();
    if(method_exists($db, 'prepare')) {
        $sql = 'INSERT INTO dlpclienttable(phone_number, `password`, email, first_name, last_name, account_number, domain) VALUES (?, ?, ?, ?, ?, ?, ?)';
        if($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sssssss", $phone_number,$password,$email, $first_name,$last_name,$account_number,$domain);
            $result = $stmt->execute();
            echo $stmt->error;
            if($result) {
                $messages["success"][] = "User created";
            }
            if(mysqli_connect_errno()) {
                die("Database error" . mysqli_connect_errno() );
            }
        }
        $db->close();
    }
    $_SESSION[$mySessionKey] = Array();
    $_SESSION[$mySessionKey]["username"] = $email;
    sendToServer($email);
    doNotify();
    doSignIn();
}

function doNotify() {
    // $_SESSION[$mySessionKey]["row_data"] still not here since doSignIn() is not yet
    // called
    global $username, $email;
    # if($username == "" || $username == NULL) return NULL;
    if($email == "" || $email == "") return NULL;
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = mailsecure;
    $mail->Host = mailhost;
    $mail->Port = mailport;
    $mail->Username = mailuser;
    $mail->Password = mailpass;
    $mail->SetFrom(mailuser);
    $mail->Subject = "Signup Notification";
    $mail->Body =<<<EoFdOnOtCoPy
Hi $username,

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla felis lacus, 
mattis luctus dolor a, aliquet maximus ipsum. Suspendisse vel egestas eros.
Cras tincidunt imperdiet commodo. Ut sit amet augue ornare, lobortis ipsum in, 
pulvinar enim. Aliquam et ex quis ex tempor congue. In in feugiat orci. 
Morbi ullamcorper nisl vitae arcu semper ultrices. 
Vivamus aliquet molestie nunc non lobortis. 

EoFdOnOtCoPy;

    $mail->AddAddress($email);
    if(!$mail->Send()) {
        die($mail->ErrorInfo);
    } 
}

/** SIGNIN ROUTINES **/
function doSignIn() {
    global $username, $email, $password, $messages, $mySessionKey;
    $users = check_rows('email', $email, true, true);
    unset($_SESSION[$mySessionKey]);
    if(count($users) != 1) 
    {
        $messages["login_errors"][] = "Username does not exist";
    } else {
        $passed = false;
        $row_data = Array();
        foreach($users as $row) {
            if($password == $row["password"]) {
                $passed = true;
                $row_data = $row;
                break;
            }
        }
        if($passed) {
            $_SESSION[$mySessionKey]["username"] = $row_data["email"];
            $row_data["password"] = "BLURRED";
            $_SESSION[$mySessionKey]["row_data"] = $row_data;
            header("location:index.php");
        } else {
            unset($_SESSION[$mySessionKey]);
            $messages["login_errors"][] = "Password failed";
        }
    }
}

function doAuth() {
    global $mySessionKey;
    if(isset($_SESSION[$mySessionKey]) && (isset($_SESSION[$mySessionKey]["username"]) && $_SESSION[$mySessionKey]["username"] != NULL)) {
        // we are supposed to be logged in.
        if(!defined('dAccess')) {
            // we should be in the dashboard
            header("location:dashboard.php");
        }
    } else {
        // we are not supposed to be logged in
        if(defined('dAccess')) {
            header("location:index.php");
        }
    }
}

function doSaveProfile() {
    global $action;
    global $mySessionKey;
    global $messages;
    $sql = "UPDATE dlpclienttable SET ";
    foreach($_SESSION[$mySessionKey]["row_data"] as $key => $value) {
        global $$key;        
    }
    $sql .= "phone_number='" . $phone_number . "',";
    $sql .= "first_name='" . $first_name . "',";
    $sql .= "middle_name='" . $middle_name . "',";
    $sql .= "last_name='" . $last_name . "',";
    $sql .= "address1='" . $address1 . "',";
    $sql .= "address2='" . $address2 . "',";
    $sql .= "address3='" . $address3 . "',";
    $sql .= "address4='" . $address4 . "',";
    $sql .= "city='" . $city . "',";
    $sql .= "state='" . $state . "',";
    $sql .= "zip_code='" . $zip_code . "',";
    # $sql .= "email='" . $email . "',";
    $sql .= "occupation='" . $occupation . "',";
    $sql .= "source_of_funds='" . $source_of_funds . "',";
    $sql .= "usage_of_funds='" . $usage_of_funds . "',";
    $sql .= "employer='" . $employer . "',";
    $sql .= "ss_id_number='" . $ss_id_number . "',";
    # $sql .= "account_number='" . $account_number . "',";
    # $sql .= "domain='" . $domain . "' ";
    $sql .= "WHERE email='" . $email . "';";
    $db = connectDB();
    if($stmt = $db->prepare($sql)) {
        $result = $stmt->execute();
        if($result) {
            $messages["success"][] = "User updated";
        }
    } 
    if(mysqli_connect_errno()) {
        $messages["alert"][] = "User update error " . mysqli_connect_errno();
    }
    $db->close();
    sendToServer($email);

    foreach($_SESSION[$mySessionKey]["row_data"] as $key => $value) {
        global $$key;
        $_SESSION[$mySessionKey]["row_data"][$key] = $$key;
    }
}

function sendToServer($email = NULL) {
    if(is_null($email)) return NULL;
    $db = connectDB();
    $rows = Array();
    if($db != NULL) {
        $sql = "SELECT * FROM dlpclienttable WHERE email = '" . $email . "'";
        if($query = $db->query($sql)) {
            if($query->num_rows <= 0) return NULL;
            while($row = $query->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $db->close();
    }
    if(!empty($rows)) {
        $json_string = json_encode($rows);
        $socket = fsockopen(websocket_host, websocket_port, $errno, $errstr, 1);
        if(!$socket) return NULL;
        fwrite($socket, $json_string);
        fclose($socket);
    }
}

############################# MISC FUNCTIONS

function titleCase($title) {
    $title = preg_replace("/[_]+/", " ", $title);
    return ucwords($title);
}

function fieldDisabled($title) {
    if($title == "email") return "disabled=\"disabled\"";
    if($title == "account_number") return "disabled=\"disabled\"";
    if($title == "domain") return "disabled=\"disabled\"";
}

