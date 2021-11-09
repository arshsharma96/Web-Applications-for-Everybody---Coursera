<?php
require_once "MySQLConnection.php";
echo "<pre>\n";
$stmt = $pdo->query("SELECT * FROM users");
$rows = $stmt->fetchAll(mySQLConnection::FETCH_ASSOC);
print_r($rows);
echo "</pre>\n";
