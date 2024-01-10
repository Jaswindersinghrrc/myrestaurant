<html>
<head></head>
<body><h1>SQL Injection Results</h1>

<div style="border: 1px solid #000000; 
	width :230px; margin-top: 
	50px;margin-left: 70px;
	padding:20px 20px 20px 20px ; 
	background-color: #F5F5FF;">

<?php
ob_start(); // session management

require_once 'HTMLPurifier.standalone.php';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

    define('DB_DSN', 'mysql:host=localhost;dbname=dvwa;charset=utf8');
    define('DB_USER', 'dvwa');
    define('DB_PASS', 'password');

    //Connect to the database. If the connection fails the webapp exits
    try {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        echo 'Error: '.$e->getMessage();
        die(); // Force execution to stop on errors.
    }
$checkpassword=0;
$query='';
$mypassword=$_GET['password'];
$homepage=$_GET['homePage'];
$userName=$_GET['userName'];

$clean_password = $purifier->purify($mypassword);
$clean_homepage = $purifier->purify($homepage);
$clean_username = $purifier->purify($userName);

// $cleanhomepage=htmlspecialchars($homepage);

$select_sql = "SELECT userPass FROM user WHERE userName=:username;";
$statement = $db->prepare($select_sql);
$statement->bindParam(':username',$clean_username);
$statement->execute();
$pass = $statement->fetch();

$returnedpassword=$pass['userPass'];

if ($returnedpassword == $clean_password)
{
	$checkpassword = 1;
}

// If returned password matches entered password, valid login
if($checkpassword){
	// Register $myusername and redirect to file "login_success.php"
	session_start();
	$_SESSION['username'] = $_GET['userName'];
	
	// the following will be used for brute force attacks in the future
	echo "Successful login as: ".$_SESSION['username'];
}
else {
	echo "Wrong Username or Password";
}
ob_end_flush();
if ( $homepage<>"" && isset($_SESSION['username']))
{
	echo "<p>updating home page";
	$query = "update user set homepage = :homepage where username= :username";
	$statement = $db->prepare($query);
	$statement->bindParam(':homepage',$clean_homepage);
	$statement->bindParam(':username',$_SESSION['username']);
	$statement->execute() or die(print_r($statement->errorInfo(), true));
}
else
{
	$query = "select homepage from user where username= :username";
	$statement = $db->prepare($query);
	$statement->bindParam(':username',$_SESSION['username']);
	$statement->execute() or die(print_r($statement->errorInfo(), true));
	$homepage = $statement->fetch();

	$returnedhomepage=$homepage['homepage'];
	$clean_homepage = $purifier->purify($returnedhomepage);
	
}

echo "<p>Your query is  " . $query;

echo "<p>Your web page is now " . $clean_homepage;

//echo "<p>Your query is  " . htmlentities($query);
//
//echo "<p>Your web page is now " . htmlentities($homepage);

?>
</div>
</body>
</html>
