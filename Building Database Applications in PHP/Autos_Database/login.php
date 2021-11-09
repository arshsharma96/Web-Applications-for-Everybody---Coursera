<?php
require_once "MySQLConnection.php";

// creating the object
$mysqlObj = new MySQLConnection();

$now = new DateTime('now');


// p' OR '1' = '1

// hashing values
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123

$failure = false;

if (isset($_POST['email'], $_POST['password'])) {
    echo("<p>Handling POST data...</p>\n");
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "Email and password are required";
        error_log($now->format('c') . " Login Fail: $failure", 3,"errorLogLogin.log");
    } else if (str_contains($_POST['email'], "@")) {
        $failure = "Email must have an at-sign (@)";
        error_log($now->format('c') . " Login Fail: $failure", 3,"errorLogLogin.log");
    } else {
        $check = hash('md5', $salt.$_POST['password']);
        if ($check === $stored_hash){
            $sql = "SELECT name FROM users WHERE email = :em AND password = :pw";

            echo "<p>$sql</p>\n";

            $stmt = $mysqlObj->getPDO()->prepare($sql);
            $stmt->execute(array(
                ':em' => $_POST['email'],
                ':pw' => $_POST['password']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            var_dump($row);

            if ( $row === FALSE ) {
                $failure = "Login Incorrect as details not in database";
                error_log($now->format('c') . " Login Fail: $failure", 3,"errorLogLogin.log");
            } else {
                $failure = "Login Success";
                error_log($now->format('c') . " Login Success: " . $_POST['email'], 3,"successLogLogin.");
                header("Location: autos.php?name=".urlencode($_POST['email']));
                return;
            }
        } else {
            $failure = "Incorrect Password";
            error_log($now->format('c') . " Login Fail: " . $_POST['email'] . " $check", 3,"errorLogLogin.log");
        }
    }


}
?>
<p>Please Login</p>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
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
