<?php 
session_start();
header('Access-Control-Allow-Origin: *');
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
    if(check_rows('phone_number', $phone_number, true)) {
        $result = false;
        $messages["errors"][] = "Phone Number is already used: " . $phone_number;
        $page_errors["signup_phone_number"] = "Phone Number is already used: " . $phone_number;
    }
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
                $count++;
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

    // ADDED 2018 October 26
    // check if domain is signuplive.dlptrade.com
    $server_name = strtolower($_SERVER['SERVER_NAME']);
    if(preg_match("/signuplive\.dlptrade\.com/", $server_name)) {
        $domain_id = 1;
        $db = connectDB();
        while(true) {
            $domain = 'LIVEDEMO' . $domain_id;
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
    }

    $db = connectDB();
    if(method_exists($db, 'prepare')) {
        $sql = 'INSERT INTO dlpclienttable(phone_number, `password`, email, first_name, last_name, account_number, domain) VALUES (?, ?, ?, ?, ?, ?, ?)';
        if($stmt = $db->prepare($sql)) {
            $stmt->bind_param("sssssss", $phone_number,$password,$email, $first_name,$last_name,$account_number,$domain);
            $result = $stmt->execute();
            #echo $stmt->error;
            #if($result) {
            #    $messages["success"][] = "User created";
            #}
            if(mysqli_connect_errno()) {
                # die("Database error" . mysqli_connect_errno() );
                header("location:index.php?action=signin&email=" . $email);
            }
        }
        $db->close();
    }
    # sendToServer($email, FALSE);
    /*
    $_SESSION[$mySessionKey] = Array();
    $_SESSION[$mySessionKey]["username"] = $email;
    doNotify();
    doSignIn();
    
    /* COMMENTED OUT 01 OCT 2018
       * AS WE ARE SUPPOSED TO GET MORE INFORMATION
    header("location:index.php?action=signin&email=" . $email);
    */
    if(isset($_SESSION[$mySessionKey . $email])) unset($_SESSION[$mySessionKey . $email]);
    $_SESSION[$mySessionKey . $email] = Array();
    # get the info again
    $db = connectDB();
    if(method_exists($db, 'query')) {
        $sql = "SELECT * FROM dlpclienttable WHERE email = '" . $email . "'";
        if($query = $db->query($sql)) {
            while($row = $query->fetch_assoc) {
                foreach($row as $field => $value) {
                    $_SESSION[$mySessionKey . $email][$field] = $value;
                }
            }
            $_SESSION[$mySessionKey . $email]["password"] = "BLURRED";
        }
        $db->close();
    }
    header("location:index.php?action=complete&email=" . $email);
}

function doComplete() {
    global $email, $mySessionKey;
    if($email == "" || $email == NULL) header("location:index.php?action=signin&email=");
    # if(!isset($_SESSION[$mySessionKey . $email])) header("location:index.php?action=signin&email=" . $email);
    if(strtoupper($_SERVER["REQUEST_METHOD"]) == "POST" ) {
        doSaveProfile(FALSE);
        $messages["success"][] = "Profile Updated";
    }
    $rows = check_rows("email", $email, TRUE, TRUE);
    foreach($rows as $row)
        $values = $row;
    unset($values["password"]);
    unset($values["backoffice"]);
    unset($values["record_sent"]);
    unset($values["key1"]);
    unset($values["key2"]);
    $_SESSION[$mySessionKey . $email] = $values;
    include_once("complete.inc.php");
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

You have been signed up. Please complete your information to continue.


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
    if($password != null AND $password != "") {
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
    } else {
        $messages["login_errors"][] = NULL;
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

function doSaveProfile($doRedirect = TRUE) {
    global $action;
    global $mySessionKey;
    global $messages;
    foreach($_SESSION[$mySessionKey]["row_data"] as $key => $value) {
        global $$key;        
    }
    if($action == "complete") {
        global $email;
        foreach($_SESSION[$mySessionKey . $email] as $key => $value) {
            global $$key;
        }
    }

    $sql = "UPDATE dlpclienttable SET ";
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
    $sql .= "ss_id_number='" . $ss_id_number . "'";
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
    // do not send to server 
    # sendToServer($email, FALSE);

    if($doRedirect) {
        foreach($_SESSION[$mySessionKey]["row_data"] as $key => $value) {
            global $$key;
            $_SESSION[$mySessionKey]["row_data"][$key] = $$key;
        }

    }
}

function sendToServer($email = NULL, $test = TRUE) {
    if(is_null($email)) return FALSE;
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
    // loop through each rows and add server details
    foreach($rows as $id => $r) {
        $domain = $r["domain"];
        $db = connectDB();
        if($db != NULL) {
            $server_ip = "";
            $server_port = "";
            $dns_name = "";
            $server_type = "TRADE";
            $sql = "SELECT * FROM dlpclientserverdetails WHERE server_type='BOSERVER' AND domain='"  . $domain . "'";
            if($query = $db->query($sql)) {
                while($row = $query->fetch_assoc()) {
                    $server_ip = $row["server_ip"];
                    $server_port = $row["server_port"];
                    $server_type = $row["server_type"];
                    $dns_name = $row["dns_name"];
                }
            }
            $db->close();
            $r["server_type"] = $server_type;
            $r["server_ip"] = $server_ip;
            $r["server_port"] = $server_port;
            $r["dns_name"] = $dns_name;
        }
        $rows[$id] = $r;
    }

    $has_sent = FALSE;
    $db = connectDB();
    $servers = Array();
    // add this anyway since if we find this in the database, it will be overwritten
    // $servers[websocket_host] = websocket_port;
    $fin_websocket_host = websocket_host;
    $fin_websocket_port = websocket_port;
    if($db != NULL) {
        $sql = "SELECT server_ip, server_port, dns_name FROM `dlpclientserverdetails` WHERE server_type LIKE 'CRYPTODB'";
        if($query = $db->query($sql)) {
            while($row = $query->fetch_assoc()) {
                $server_ip = trim($row["server_ip"]);
                $server_port = trim($row["server_port"]);
                if($server_ip == "") $server_ip = $row["dns_name"];
                if(trim($server_ip) == "") continue;
                $fin_websocket_host = $server_ip;
                $fin_websocket_port = $server_port;
                break;
            }
        }
    }

    if(!empty($rows)) {
        $json_string = json_encode($rows);
        if($test) {
            echo $json_string;
            return NULL;
        }
        $retries = 0;
        while(true)
        {
            $socket = fsockopen($fin_websocket_host, $fin_websocket_port, $errno, $errstr, 1);
            if(!$socket) {
                echo "Unable to connect to " . $fin_websocket_host . ":" . $fin_websocket_port . "\n";
                unset($socket);
                $retries++;
                if($retries >= 2) {
                    echo "Tried connecting 3 times. All failed. Try again later";
                    break;
                }
                continue;
            }
            fwrite($socket, $json_string);
            stream_set_timeout($socket, 2);
            echo "Sent :: " . $json_string . " :: \n";
            fclose($socket);
            $has_sent = TRUE;
            break;
        }
    }
    return $has_sent;
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

function fieldRequired($fieldName) {
    switch(strtolower($fieldName)) {
        case "email": return TRUE;;
        case "phone_number": return TRUE;;
        case "first_name": return TRUE;;
        case "last_name": return TRUE;;
        case "account_number": return TRUE;;
        case "domain": return TRUE;;
        default: return FALSE;;
    }
}

