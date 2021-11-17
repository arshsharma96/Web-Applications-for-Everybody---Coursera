<?php
require_once "pdo.php";

// GET Parameter user_id=1

$pdo->setAttribute(mySQLConnection::ATTR_ERRMODE, mySQLConnection::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":pizza" => $_GET['user_id']));
$row = $stmt->fetch(mySQLConnection::FETCH_ASSOC);
if ( $row === false ) {
    echo("<p>user_id not found</p>\n");
} else {
    echo("<p>user_id found</p>\n");
}
?>

