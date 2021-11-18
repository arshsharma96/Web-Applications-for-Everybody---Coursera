<?php

require_once "MySQLConnection.php";

session_start();

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$status = "";

// on pressing logout
if (isset($_POST['cancel'])){
    header('Location: index.php');
    return;
}

// on adding a record
if (isset($_POST['add'])){
    if (isset($_POST['make'], $_POST['model'], $_POST['year'], $_POST['mileage'])){

        if (strlen($_POST['make']) < 1){
            $status = "All fields are required";
            error_log($now->format('c') . " Error : $status \n", 3,"errorLogAutos.log");
            $_SESSION['error'] = $status;
            header("Location: add.php");
            return;
        }

        if(strlen($_POST['model']) < 1){
            $status = "All fields are required";
            error_log($now->format('c') . " Error : $status \n", 3,"errorLogAutos.log");
            $_SESSION['error'] = $status;
            header("Location: add.php");
            return;
        }

        if((!is_numeric($_POST['year']))){
            $status = "Year must be an integer";
            error_log($now->format('c') . " Error : $status \n", 3,"errorLogAutos.log");
            $_SESSION['error'] = $status;
            header("Location: add.php");
            return;
        }

        if((!is_numeric($_POST['mileage']))){
            $status = "Mileage must be an integer";
            error_log($now->format('c') . " Error : $status \n", 3,"errorLogAutos.log");
            $_SESSION['error'] = $status;
            header("Location: add.php");
            return;
        }

        try{
            $sql = "INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)";
            $stmt = $mysqlObj->getPDO()->prepare($sql);
            $stmt->execute(array(':mk' => $_POST['make'],
                ':md' => $_POST['model'],
                ':yr' => $_POST['year'],
                ':mi' => $_POST['mileage']
            ));
            $status = "Record added";
            error_log($now->format('c') . " Record Inserted :" . "\n", 3,"successLogDatabase.log");
            $_SESSION['success'] = $status;
            header("Location: index.php");
            return;
        } catch (Exception $e){
            $status = "Record not inserted due to error";
            error_log($now->format('c') . " Error : " . $e, 3, "errorLogDatabase.log");
            $_SESSION['error'] = $status;
        }

    }
    else {
        $status = "All fields are required";
        error_log($now->format('c') . " Error : $status \n", 3,"errorLogAutos.log");
        $_SESSION['error'] = $status;
    }
}

// validating if username is passed from previous page
if(isset($_SESSION['name'])){
    // logging start of autos page for a user
    $status = "Autos Add Page started";
    error_log($now->format('c') . " Autos page started for user : " . $_SESSION['name'] . "\n", 3,"successLogAutos.log");
} else {
    $status = "Not logged in";
    error_log($now->format('c') . " Unable to start Autos Page due to $status \n", 3,"errorLogAutos.log");
    die($status);
}
?>

<html lang="en">
<head>
    <title>Autos Add Page</title>
</head>
<body>
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']); ?> </h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="make">
        Make: <input type="text" name="make">
    </label>
    <br>
    <br>
    <label>
        Model: <input type="text" name="model">
    </label>
    <br>
    <br>
    <label for="year">
        Year: <input type="number" name="year">
    </label>
    <br>
    <br>
    <label for="mileage">
        Mileage: <input type="number" name="mileage">
    </label>
    <br>
    <br>
    <input type="submit" name="add" value="Add"/>&nbsp;&nbsp;&nbsp;
    <input type="submit" name="cancel" value="Cancel"/>&nbsp;
</form>
</body>
</html>
