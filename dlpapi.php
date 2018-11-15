<?php
ob_start();
define('tAccess', TRUE);
include_once("functions.inc.php");

$cli = FALSE;
if(defined('STDIN') )  {
    if($argc < 3) {
        echo "Script requires at least two argumets: username and password";
        exit(0);
    }
    $username = $argv[1];
    $password = $argv[2];
    $cli = TRUE;
}

$data = Array();
if(array_key_exists("username", $_GET) && array_key_exists("password", $_GET)) {
    $username = $_GET['username'];
    $password = $_GET['password'];
}

if(array_key_exists("username", $_POST) && array_key_exists("password", $_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

$server_type = "";
if(array_key_exists("server_type", $_POST))
    $server_type = $_POST["server_type"];
if(array_key_exists("server_type", $_GET))
    $server_type = $_GET["server_type"];
if($server_type != "") $server_type = strtopper($server_type);


/** RENZ: REMOVE THESE ON THE PRODUCTION, TOO DANGEROUSE TO LEAVE BEHIND **/
/** 01 OCT 2018 REMOVING GETs Moving to POSTs
if(array_key_exists("username", $_GET) && array_key_exists("password", $_GET)) {
    $username = $_GET['username'];
    $password = $_GET['password'];
}
**/

$data["username"] = $username;
// $data["password"] = $password;

/** GET THE DATA FROM THE DATABASE **/

/** GET ACCOUNT NUMBER and DOMAIN */
$data["account_number"] = "";
$data["domain"] = "";
$sql = "SELECT account_number, domain FROM dlpclienttable WHERE email = ? AND password = ?";
$db = connectDB();
if($stmt = $db->prepare($sql)) {
    $stmt->bind_param("ss", $username, $password);
    $stmt->bind_result($account_number, $domain);
    $stmt->execute();
    $stmt->store_result();
    while($stmt->fetch()) {
        $data["account_number"] = $account_number;
        $data["domain"] = $domain;
    }
    if(mysqli_connect_errno()) {
        die("Database error" . mysqli_connect_errno() );
    }

}
$db->close();

// get action
$action = "";
foreach($_GET as $key => $value) {
    $key = strtolower($key);
    if($key == "action") $action = trim(strtolower($value));
}

foreach($_POST as $key => $value) {
    $key = strtolower($key);
    if($key == "action") $action = trim(strtolower($value));
}

// get action_array
$action_array = "";
foreach($_GET as $key => $value) {
    $key = strtolower($key);
    if($key == "actionarray") $action_array = $value;
}

foreach($_POST as $key => $value) {
    $key = strtolower($key);
    if($key == "actionarray") $action_array = $value;
}

// post : username=mtrv34RsacdTephlOEBK&password=qInxdyquEZJLImygesZv
if($username == spec_user) {
    if($password == spec_pass) {
        $values = Array("%");
        switch($action) {
            case "getserverbydomain":
                $condition = " domain LIKE ?";
                break;
            case "getserverbytype":
                $condition = " server_type LIKE ?";
                break;
            case "getsymbols":
                getSymbols($action_array);
                exit(0);
                break;
            case "getuserinfo":
                getUserInfo($action_array);
                exit(0);
                break;
            default:
                $condition = " server_type LIKE ?";
                break;
        }
        if($action_array != "") {
            $values = explode(" ", $action_array);
            if(strtolower($action_array) == "all")
            $values = Array("%");
        }
        $db = connectDB();
        $sql = "SELECT domain, server_ip, server_port, dns_name FROM dlpclientserverdetails WHERE " . $condition;
        $return_values = Array();
        foreach($values as $value) {
            if($stmt = $db->prepare($sql)) {
                $stmt->bind_param("s", $value);
                $stmt->bind_result($domain, $server_ip, $server_port, $dns_name);
                $stmt->execute();
                $stmt->store_result();
                while($stmt->fetch()) {
                    $return_value = Array();
                    $return_value["domain"] = $domain;
                    $return_value["server_ip"] = $server_ip;
                    $return_value["server_port"] = $server_port;
                    $return_value["dns_name"] = $dns_name;
                    $return_values[] = $return_value;
                }
            $stmt->close();
            }
        }
        echo json_encode($return_values);
        exit(0);
    }
}

if($data["account_number"] == "") {
    # account number still missing. maybe not email?
    $data["account_number"] = "";
    $data["domain"] = "";
    $sql = "SELECT account_number, domain FROM dlpclienttable WHERE phone_number = ? AND password = ?";
    $db = connectDB();
    if($stmt = $db->prepare($sql)) {
        $stmt->bind_param("ss", $username, $password);
        $stmt->bind_result($account_number, $domain);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch()) {
            $data["account_number"] = $account_number;
            $data["domain"] = $domain;
        }
        if(mysqli_connect_errno()) {
            die("Database error" . mysqli_connect_errno() );
        }
    
    }
    $db->close();
}

/** GET SERVER DETAILS VIA DOMAIN **/
if($data["account_number"] != "") {
    if($data["domain"] != "")
    {
        $sql = "SELECT server_type, server_ip, server_port, dns_name FROM dlpclientserverdetails WHERE domain = 'ALL' OR domain = ?";
        $db = connectDB();
        if($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $data["domain"]);
            $stmt->bind_result($server_type, $server_ip, $server_port, $dns_name);
            $stmt->execute();
            $stmt->store_result();
            while($stmt->fetch()) {
                $data[$server_type ."_server_type"] = $server_type;
                $data[$server_type ."_server_ip"] = $server_ip;
                $data[$server_type ."_server_port"] = $server_port;
                if($dns_name != NULL AND $dns_name != "") {
                    $data[$server_type ."_server_ip"] = $dns_name;
                }
            }
            if(mysqli_connect_errno()) {
                die("Database error" . mysqli_connect_errno() );
            }
        
        }
        $db->close();
    }
    if($data["username"] != "") {
        $token = get_or_create_token($data["username"]);
        $data["token"] = $token;
    }
}

function getSymbols($action_array) {
    if($action_array == "") $action_array = "all";

    $values = explode(" ", $action_array);
    if(strtolower($action_array) == "all")
        $values = Array("%");
    $return_values = Array();
    $sql = "SELECT symbol,exchange,formatprice,formatsize,bidvol,askvol FROM ecnsymbols WHERE symbol LIKE ?";
    $db = connectDB();
    foreach($values as $value) {
        if($stmt = $db->prepare($sql)) {
            $value = '%' . strtoupper($value) . '%';
            $stmt->bind_param("s", $value);
            $stmt->bind_result($symbol, $exchange, $formatprice, $formatsize, $bidvol, $askvol);
            $stmt->execute();
            $stmt->store_result();
            while($stmt->fetch()) {
                $return_value = Array();
                $return_value["symbol"] = $symbol;
                $return_value["exchange"] = $exchange;
                $return_value["formatprice"] = $formatprice;
                $return_value["formatsize"] = $formatsize;
                $return_value["bidvol"] = $bidvol;
                $return_value["askvol"] = $askvol;
                $return_values[] = $return_value;
            }
        $stmt->close();
        }
    }
    echo json_encode($return_values);
}

function getUserInfo($action_array) {
    $aa = $action_array;
    if(trim(strtolower($aa)) != "") {
        $return_values = Array();
        $email_address = explode(" ", $aa);
        foreach($email_address as $email) {
            $sql = "SELECT account_number, domain FROM dlpclienttable WHERE email LIKE ?";
            $db = connectDB();
            if($stmt = $db->prepare($sql)) {
                $cond = '%' . $email . '%';
                $stmt->bind_param("s", $cond);
                $stmt->bind_result($account_number, $domain);
                $stmt->execute();
                $stmt->store_result();
                $data = Array();
                while($stmt->fetch()) {
                    $data["account_number"] = $account_number;
                    $data["domain"] = $domain;
                    $return_values[] = $data;
                }
                if(mysqli_connect_errno()) {
                    die("Database error" . mysqli_connect_errno() );
                }
            
            }
            $db->close();

            foreach($return_values as $id => $val) {
                $data = $val;
                $sql = "SELECT server_type, server_ip, server_port, dns_name FROM dlpclientserverdetails WHERE domain = 'ALL' OR domain = ?";
                $db = connectDB();
                if($stmt = $db->prepare($sql)) {
                    $stmt->bind_param("s", $val["domain"]);
                    $stmt->bind_result($server_type, $server_ip, $server_port, $dns_name);
                    $stmt->execute();
                    $stmt->store_result();
                    while($stmt->fetch()) {
                        $data[$server_type ."_server_type"] = $server_type;
                        $data[$server_type ."_server_ip"] = $server_ip;
                        $data[$server_type ."_server_port"] = $server_port;
                        if($dns_name != NULL AND $dns_name != "") {
                            $data[$server_type ."_server_ip"] = $dns_name;
                        }
                    }
                    if(mysqli_connect_errno()) {
                        die("Database error" . mysqli_connect_errno() );
                    }
                    $return_values[$id] = $data;
                }
                $db->close();
            }
        }

        echo json_encode($return_values);
    } else {
        echo "HI";
    }
}

if($cli)
    header("Content-type: application/json");
echo json_encode($data);
ob_end_flush();
