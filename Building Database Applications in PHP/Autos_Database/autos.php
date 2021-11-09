<?php

require_once "MySQLConnection.php";

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$failure = false;
$databaseStatus = "";

// validating if username is passed from previous page
if(isset($_GET['email'])){
    $sql = "SELECT name FROM users WHERE email = :em";
    $stmt = $mysqlObj->getPDO()->prepare($sql);
    $stmt->execute(array(
            ':em' => $_GET['email']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($row);

    // validating if username present in the database
    if ( $row === FALSE ) {
        $failure = "Username not in database";
        error_log($now->format('c') . " Unable to start Autos Page due to : $failure \n", 3,"errorLogAutos.log");
        die("Name parameter missing");
    }

    // logging start of autos page for a user
    $failure = "Autos Page started";
    error_log($now->format('c') . " Autos page started for user : " . $_GET['email'] . "\n", 3,"successLogAutos.log");

    // on pressing logout
    if (isset($_POST['logout'])){
        header('Location: index.php');
        return;
    }

    // on adding a record
    if (isset($_POST['make'], $_POST['add'], $_POST['year'])){
        if (strlen($_POST['make']) < 1){
            $failure = "The make does not have any characters";
            error_log($now->format('c') . " Error : $failure \n", 3,"errorLogAutos.log");
        } else {
            try{
                $sql = "INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)";
                $stmt = $mysqlObj->getPDO()->prepare($sql);
                $stmt->execute(array(
                    ':mk' => $_POST['make'],
                    ':yr' => $_POST['year'],
                    ':mi' => $_POST['mileage']
                ));
                $databaseStatus = "Record Inserted";
                error_log($now->format('c') . " Record Inserted : $stmt" . "\n", 3,"successLogDatabase.log");
            } catch (Exception $e){
                error_log($now->format('c') . " Error : " . $e, 3, "errorLogDatabase.log");
            }

        }
    } else {
        $failure = "All the fields must be filled";
        error_log($now->format('c') . " Error : $failure \n", 3,"errorLogAutos.log");
    }





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
        <h1>Tracking Autos for <?php echo $_GET['email']; ?> </h1>
        <p style="background-color: #008000">
            <?php
                if(!empty($databaseStatus)){
                    echo $databaseStatus;
                }
            ?>
        </p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="Make">
                Make: <input type="text" name="make">
            </label>
            <br>
            <br>
            <label for="Year">
                Year: <input type="number" name="year">
            </label>
            <br>
            <br>
            <label for="Mileage">
                Mileage: <input type="number" name="mileage">
            </label>
            <br>
            <br>
            <input type="submit" name="add" value="Add"/>&nbsp;&nbsp;&nbsp;
            <input type="submit" name="logout" value="Logout"/>&nbsp;
        </form>
    <p>
        <?php
            if(!empty($databaseStatus)){
                // TODO - Show all data from Database in Unordered List
            }
        ?>
    </p>
    </body>
</html>
