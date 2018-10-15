<?php
define('tAccess', TRUE);
include_once("functions.inc.php");
$data = Array();
if(array_key_exists("username", $_GET) && array_key_exists("password", $_GET)) {
    $username = $_GET['username'];
    $password = $_GET['password'];
}

if(array_key_exists("username", $_POST) && array_key_exists("password", $_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}

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
                $data["server_type"] = $server_type;
                $data[$server_type ."_server_ip"] = $server_ip;
                $data[$server_type ."_server_port"] = $server_port;
                if($dns_name != NULL AND $dns_name != "") {
                    $data[$server_type ."server_ip"] = $dns_name;
                }
            }
            if(mysqli_connect_errno()) {
                die("Database error" . mysqli_connect_errno() );
            }
        
        }
        $db->close();
    }
}

header("Content-type: application/json");
echo json_encode($data);
