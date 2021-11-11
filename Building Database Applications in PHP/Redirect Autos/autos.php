<?php

require_once "MySQLConnection.php";

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$failure = false;
$databaseStatus = false;

// on pressing logout
if (isset($_POST['logout'])){
    header('Location: index.php');
    return;
}

// on adding a record
if (isset($_POST['add'])){
    $email = $_POST['email'];
    if (isset($_POST['make'], $_POST['year'], $_POST['mileage'])){
        if (strlen($_POST['make']) < 1){
            $failure = "Make is required";
            error_log($now->format('c') . " Error : $failure \n", 3,"errorLogAutos.log");
            //$databaseStatus = "$failure";
            header("Location: autos.php?email=".urlencode($email)."&status=".urlencode($failure));
            return;
        }

        if((!is_numeric($_POST['year'])) || (!is_numeric($_POST['mileage']))){
            $failure = "Mileage and year must be numeric";
            error_log($now->format('c') . " Error : $failure \n", 3,"errorLogAutos.log");
            //$databaseStatus = "$failure";
            header("Location: autos.php?email=".urlencode($email)."&status=".urlencode($failure));
            return;
        }
        else {
            try{
                $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
                $stmt = $mysqlObj->getPDO()->prepare($sql);
                $stmt->execute(array(
                    ':mk' => htmlentities($_POST['make']),
                    ':yr' => htmlentities($_POST['year']),
                    ':mi' => htmlentities($_POST['mileage'])
                ));
                var_dump($stmt);
                $databaseStatus = "Record Inserted";
                error_log($now->format('c') . " Record Inserted :" . "\n", 3,"successLogDatabase.log");
                header("Location: autos.php?email=".urlencode($email)."&status=".urlencode($databaseStatus));
                return;
            } catch (Exception $e){
                $databaseStatus = "Record not inserted due to error";
                error_log($now->format('c') . " Error : " . $e, 3, "errorLogDatabase.log");
                header("Location: autos.php?email=".urlencode($email)."&status=".urlencode($databaseStatus));
                return;
            }

        }
    } else {
        $failure = "All the fields must be filled";
        $databaseStatus = "Record not inserted due to error";
        error_log($now->format('c') . " Error : $failure \n", 3,"errorLogAutos.log");
        header("Location: autos.php?email=".urlencode($email)."&status=".urlencode($databaseStatus));
        return;
    }
}


// validating if username is passed from previous page
if(isset($_GET['email'])){
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
    error_log($now->format('c') . " Autos page started for user : " . $_GET['email'] . "\n", 3,"successLogAutos.log");
} else {
    $failure = "Name parameter missing";
    error_log($now->format('c') . " Unable to start Autos Page due to $failure \n", 3, "errorLogAutos.log");
    die("Name parameter missing");
}
?>

<html lang="en">
    <head>
        <title>Autos Page</title>
    </head>
    <body>
        <h1>Tracking Autos for <?php echo htmlentities($_GET['email']); ?> </h1>
        <p>
            <?php echo $_GET['status']; ?>
        </p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="make">
                Make: <input type="text" name="make">
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
            <label>
                <input type="hidden" name="email" value="<?php echo $_GET['email'] ?>"
            </label>
            <br>
            <br>
            <input type="submit" name="add" value="Add"/>&nbsp;&nbsp;&nbsp;
            <input type="submit" name="logout" value="Logout"/>&nbsp;
        </form>
    <p><h3>Automobiles</h3>
        <?php
            if(!empty($_GET['status'])){
                $stmt = $mysqlObj->getPDO()->query("SELECT make, year, mileage FROM autos");
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<ul>";
                foreach ( $rows as $row ) {
                    echo "<li>";
                    echo($row["make"] . " " . $row["year"] . " / " . $row["mileage"]);
                    echo("</li>");
                }
                echo "</table>\n";
            }
        ?>
    </body>
</html>
