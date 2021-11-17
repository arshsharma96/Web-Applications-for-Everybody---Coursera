<?php
require_once "MySQLConnection.php";

// creating the object
//$mysqlObj = new MySQLConnection();

$now = new DateTime('now');

session_start();

// p' OR '1' = '1

// hashing values
//$salt = 'php';
$stored_hash = '218140990315bb39d948a523d61549b4';  // Pw is meow123

$status = "";

if (isset($_POST['email'], $_POST['pass'])) {
   // var_dump($_POST['who']);
    //echo("<p>Handling POST data...</p>\n");
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $status = "User name and password are required";
        error_log($now->format('c') . " Login Fail: $status \n", 3,"errorLogLogin.log");
        $_SESSION['error'] = $status;
        header("Location: login.php");
        return;
    } else if (!str_contains($_POST['email'], "@")) {
        $status = "Email must have an at-sign (@)";
        error_log($now->format('c') . " Login Fail: $status \n", 3,"errorLogLogin.log");
        $_SESSION['error'] = $status;
        header('Location: login.php');
        return;
    } else {
        $check = hash('md5', $_POST['pass']);
        if ($check === $stored_hash){
//            header("Location: autos.php?email=".urlencode($_POST['who']));
//            return;
            $_SESSION['name'] = $_POST['email'];
            header("Location: index.php");
            return;

//            $sql = "SELECT email FROM users WHERE email = :em AND password = :pw";
//
//            echo "<p>$sql</p>\n";
//
//            $stmt = $mysqlObj->getPDO()->prepare($sql);
//            $stmt->execute(array(
//                ":em" => $_POST['who'],
//                ":pw" => $_POST['pass']));
//            var_dump($stmt);
//            $row = $stmt->fetch(PDO::FETCH_ASSOC);
//
//            var_dump($row);
//
//            if ( $row === FALSE ) {
//                $failure = "Login Incorrect as details not in database";
//                error_log($now->format('c') . " Login Fail: $failure \n", 3,"errorLogLogin.log");
//            } else {
//                $failure = "Login Success";
//                error_log($now->format('c') . " Login Success: " . $_POST['email'] . "\n", 3,"successLogLogin.");
//                header("Location: autos.php?email=".urlencode($_POST['who']));
//                return;
//            }

        }
        else {
            $status = "Incorrect Password";
            error_log($now->format('c') . " Login Fail: $status for email " . $_POST['email'] . " and hash value - $check \n", 3,"errorLogLogin.log");
            $_SESSION['error'] = $status;
            header('Location: login.php');
            return;
        }
    }
}
?>
<html lang="en">
<head>
    <title>Arsh Sharma Autos Login Page</title>
</head>
<body>
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
    if(isset($_SESSION['error'])){
        echo('<p style="color: red;">'. ($_SESSION['error']) ."</p>\n");
        unset($_SESSION['error']);
    }
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>
        User Name: <input type="text" size="40" name="email" required>
    </label>
    <br>
    <br>
    <label>
        Password: <input type="text" size="40" name="pass" required>
    </label>
    <br>
    <br>
    <input type="submit" value="Log In"/>&nbsp;&nbsp;
    <a href="entry.php">Cancel</a>
</form>
</body>
</html>
