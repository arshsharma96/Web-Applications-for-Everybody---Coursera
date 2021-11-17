<?php
require_once "MySQLConnection.php";
session_start();

$mysqlObj = new MySQLConnection();

if (isset($_POST['delete'], $_POST['autos_id'])) {
    $sql = "DELETE FROM autos WHERE user_id = :auto";
    $stmt = $mysqlObj->getPDO()->prepare($sql);
    $stmt->execute(array(':auto' => $_POST['user_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}

$stmt = $mysqlObj->getPDO()->prepare("SELECT make FROM autos where autos_id = :auto");
$stmt->execute(array(":auto" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['make']) ?></p>

<form method="post">
    <input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
</form>
