<?php
require_once "MySQLConnection.php";

// creating the object
$mysqlObj = new MySQLConnection();


// p' OR '1' = '1

if (isset($_POST['email'], $_POST['password'])) {
    echo("<p>Handling POST data...</p>\n");

    $sql = "SELECT name FROM users WHERE email = :em AND password = :pw";

    echo "<p>$sql</p>\n";

    $stmt = $mysqlObj->getPDO()->prepare($sql);
    $stmt->execute(array(
        ':em' => $_POST['email'],
        ':pw' => $_POST['password']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    var_dump($row);
    
    if ( $row === FALSE ) {
        echo "<h1>Login incorrect.</h1>\n";
    } else {
        echo "<p>Login success.</p>\n";
    }
}
?>
<p>Please Login</p>
<form method="post">
    <p>Email:
        <label>
            <input type="text" size="40" name="email">
        </label>
    </p>
    <p>Password:
        <label>
            <input type="text" size="40" name="password">
        </label>
    </p>
    <p><input type="submit" value="Login"/><a href="<?php echo($_SERVER['PHP_SELF']);?>">Refresh</a></p>
</form>
<p>
    Check out this
    <a href="http://xkcd.com/327/" target="_blank">XKCD comic that is relevant</a>.
