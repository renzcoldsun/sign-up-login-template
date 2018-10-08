<?php
define('tAccess', TRUE);
include_once('config.inc.php');
include_once('functions.inc.php');
$test = FALSE;
$accounts_sent = Array();
$db = connectDB();
$sql = "SELECT email FROM dlpclienttable WHERE record_sent=false";
if(test) $sql = "SELECT email FROM dlpclienttable";
if($query1 = $db->query($sql)) {
    while($row1 = $query1->fetch_assoc()) {
        $email = $row1["email"];
        if(sendToServer($email, $test))
            $accounts_sent[] = $email;
    }
}
$db->close();

if(!$test) {
    foreach($accounts_sent as $email) {
        $db = connectDB();
        $sql = "UPDATE dlpclienttable SET record_sent=true WHERE email='" . $email . "'";
        if($query = $db->query($sql)) {
            echo "Email <" . $email . "> SENT TO SERVER " . websocket_host . ":" . websocket_port;
        }
        $db->close();
    }
}