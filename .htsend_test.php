<?php
define('tAccess', TRUE);
include_once('config.inc.php');
include_once('functions.inc.php');

$db = connectDB();
$sql = "SELECT email FROM dlpclienttable";
if($query1 = $db->query($sql)) {
    while($row1 = $query1->fetch_assoc()) {
        $email = $row1["email"];
        sendToServer($email);
    }
}