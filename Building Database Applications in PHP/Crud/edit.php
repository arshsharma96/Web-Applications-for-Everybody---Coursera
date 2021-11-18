<?php
require_once "MySQLConnection.php";
session_start();

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

$status = "";

//
//// Guardian: Make sure that autos_id is present
//if (!isset($_GET['id'])) {
//    $_SESSION['error'] = "Missing autos_id";
////    header('Location: index.php');
////    return;
//}


if (isset($_POST['update'], $_POST['make'], $_POST['model'], $_POST['year'], $_POST['mileage'], $_POST['autos_id'])) {

    // Data validation
    if (strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?id=" . $_POST['autos_id'] . "");
        return;
    }

    if ((!is_numeric($_POST['year']))) {
        $status = "Year must be an integer";
        error_log($now->format('c') . " Error : $status \n", 3, "errorLogAutos.log");
        $_SESSION['error'] = $status;
        header("Location: edit.php?id=" . $_POST['autos_id'] . "");
        return;
    }

    if ((!is_numeric($_POST['mileage']))) {
        $status = "Mileage must be an integer";
        error_log($now->format('c') . " Error : $status \n", 3, "errorLogAutos.log");
        $_SESSION['error'] = $status;
        header("Location: edit.php?id=" . $_POST['autos_id'] . "");
        return;
    }

    $sql = "UPDATE autos SET make = :make, model = :model, year = :yr, mileage = :mileage WHERE autos_id = :autos_id";
    $stmt = $mysqlObj->getPDO()->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}


$stmt = $mysqlObj->getPDO()->prepare("SELECT * FROM autos where autos_id = :autos_id");
$stmt->execute(array(":autos_id" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: index.php');
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>

<html lang="en">
<head>
    <title>Autos Edit Page</title>
</head>
<body>
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']); ?> </h1>
<?php
// flash message
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
    unset($_SESSION['error']);
}
?>
<h1>Edit Autos</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="make">
        Make: <input type="text" name="make" value="<?php echo $make; ?>">
    </label>
    <br>
    <br>
    <label>
        Model: <input type="text" name="model" value="<?php echo $model; ?>">
    </label>
    <br>
    <br>
    <label for="year">
        Year: <input type="number" name="year" value="<?php echo $year; ?>">
    </label>
    <br>
    <br>
    <label for="mileage">
        Mileage: <input type="number" name="mileage" value="<?php echo $mileage; ?>">
    </label>
    <label>
        <input type="hidden" name="autos_id" value="<?php echo $autos_id; ?>">
    </label>
    <br>
    <br>
    <input type="submit" name="update" value="Save"/>&nbsp;&nbsp;&nbsp;
    <input type="submit" name="cancel" value="Cancel"/>&nbsp;
</form>
