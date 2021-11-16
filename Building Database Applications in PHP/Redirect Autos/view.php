<?php

require_once "MySQLConnection.php";

session_start();

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$failure = false;
$databaseStatus = false;

// on pressing logout
if (isset($_POST['logout'])){
    header('Location: logout.php');
    return;
}

if (isset($_POST['add'])){
    header("Location: add.php");
    return;
}


// validating if username is passed from previous page
if(isset($_SESSION['name'])){
//    $sql = "SELECT email FROM users WHERE email = :em";
//    $stmt = $mysqlObj->getPDO()->prepare($sql);
//    $stmt->execute(array(
//            ':em' => $_GET['email']
//    ));
//    $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//    // validating if username present in the database
//    if ( $row === FALSE ) {
//        $failure = "Username not in database";
//        error_log($now->format('c') . " Unable to start Autos Page due to : $failure \n", 3,"errorLogAutos.log");
//        die("Name parameter missing");
//    }

    // logging start of autos page for a user
    $failure = "Autos Page started";
    error_log($now->format('c') . " Autos page started for user : " . $_SESSION['name'] . "\n", 3,"successLogAutos.log");
} else {
    $failure = "Not logged in";
    error_log($now->format('c') . " Unable to start Autos Page due to $failure \n", 3, "errorLogAutos.log");
    die($failure);
}
?>

<html lang="en">
<head>
    <title>Autos View Page</title>
</head>
<body>
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']); ?> </h1>
<?php
if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'. ($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?>
<p><h2>Automobiles</h2>
<?php
    $stmt = $mysqlObj->getPDO()->query("SELECT make, year, mileage FROM autos");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<ul>";
    foreach ( $rows as $row ) {
        echo "<li>";
        echo(htmlentities($row["make"]) . " " . htmlentities($row["year"]) . " / " . htmlentities($row["mileage"]));
        echo("</li>");
    }
    echo "</ul>\n";
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!--    <input type="submit" name="add" value="Add New"/>&nbsp;&nbsp;&nbsp;-->
<!--    <input type="submit" name="logout" value="Logout"/>&nbsp;-->
    <a href="add.php">Add New</a>&nbsp; | &nbsp;
    <a href="logout.php">Logout</a>
</form>
</body>
</html>
