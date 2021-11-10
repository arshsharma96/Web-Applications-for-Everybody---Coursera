<?php
require_once "MySQLConnection.php";

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');


// p' OR '1' = '1

// hashing values
$salt = 'php';
$stored_hash = '218140990315bb39d948a523d61549b4';  // Pw is meow123

$failure = false;

if (isset($_POST['who'], $_POST['pass'])) {
    echo("<p>Handling POST data...</p>\n");
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
        error_log($now->format('c') . " Login Fail: $failure \n", 3,"errorLogLogin.log");
    } else if (!str_contains($_POST['who'], "@")) {
        $failure = "Email must have an at-sign (@)";
        error_log($now->format('c') . " Login Fail: $failure \n", 3,"errorLogLogin.log");
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ($check === $stored_hash){
            $sql = "SELECT name FROM users WHERE email = :em AND password = :pw";

            echo "<p>$sql</p>\n";

            $stmt = $mysqlObj->getPDO()->prepare($sql);
            $stmt->execute(array(
                ':em' => $_POST['who'],
                ':pw' => $_POST['pass']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            var_dump($row);

            if ( $row === FALSE ) {
                $failure = "Login Incorrect as details not in database";
                error_log($now->format('c') . " Login Fail: $failure \n", 3,"errorLogLogin.log");
            } else {
                $failure = "Login Success";
                error_log($now->format('c') . " Login Success: " . $_POST['email'] . "\n", 3,"successLogLogin.");
                header("Location: autos.php?email=".urlencode($_POST['who']));
                return;
            }
        } else {
            $failure = "Incorrect Password";
            error_log($now->format('c') . " Login Fail: $failure for email " . $_POST['who'] . " and hash value - $check \n", 3,"errorLogLogin.log");
        }
    }


}
?>
<html lang="en">
<head>
    <title>Arsh Sharma Autos Login Page</title>
</head>
<body>
<p>Please Login</p>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>
        Email: <input type="text" size="40" name="who">
    </label>
    <br>
    <br>
    <label>
        Password: <input type="text" size="40" name="pass">
    </label>
    <br>
    <br>
    <input type="submit" value="Log In"/>&nbsp;&nbsp;
    <a href="<?php echo ($_SERVER['PHP_SELF']);?>">Refresh</a>
</form>
<p>
    Check out this
    <a href="http://xkcd.com/327/" target="_blank">XKCD comic that is relevant</a>.
</p>
</body>
</html>
