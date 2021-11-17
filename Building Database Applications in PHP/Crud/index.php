<?php

require_once "MySQLConnection.php";

session_start();

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$status = "";


// validating if username is passed from previous page
if(isset($_SESSION['name'])){
    // logging start of autos page for a user
    $status = "Autos Page started";
    error_log($now->format('c') . " Autos page started for user : " . $_SESSION['name'] . "\n", 3,"successLogAutos.log");
} else {
    $status = "ACCESS DENIED";
    error_log($now->format('c') . " Unable to start Autos Page due to $status \n", 3, "errorLogAutos.log");
    die($status);
}
?>

<html lang="en">
<head>
    <title>Autos View Page</title>
</head>
<body>
<h1>Welcome to Automobiles Database</h1>
<?php
if (isset($_SESSION['success'])) {
    echo('<p style="color: green;">'. ($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>
<p><h2>Automobiles</h2>
<br>
<br>

<?php
$stmt = $mysqlObj->getPDO()->query("SELECT autos_id, make, model, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
// TODO if no data in table no rows to be printed
// creating table
echo "<table><tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr>";
foreach ( $rows as $row ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['make']) . "</td>";
    echo "<td>" . htmlentities($row['model']) . "</td>";
    echo "<td>" . htmlentities($row['year']) . "</td>";
    echo "<td>" . htmlentities($row['mileage']) . "</td>";
    echo '<td> <a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ';
    echo  '<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a></td>';
    echo "</tr>";
}
echo "</table>";
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <a href="add.php">Add New Entry</a>
    <br>
    <br>
    <a href="logout.php">Logout</a>
</form>
</body>
</html>
