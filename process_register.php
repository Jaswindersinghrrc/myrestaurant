<?php
  require 'config.php';
  require 'database.php';
  $g_title = BLOG_NAME . ' - Main Login';
  $g_page = 'main_login';
  require 'header.php';
  require 'menu.php';
?>


<?php
ob_start(); // session management
require('databaseconnection.php');
$tbl_name="members"; // Table name if you wish to use a variable
//$myusername=$_POST['username'];
//$mypassword=$_POST['password'];

$myusername = $_POST['username'];
$myemail = $_POST['email'];
$mypassword = $_POST['password'];
$mypassword2 = $_POST['password2'];

// set an error array so you can
// print out all problems
$errors = array();

if (empty($myusername)){ array_push($errors, "Username required!"); }
if (empty($myemail)) { array_push($errors, "Email is required!"); }
if (empty($mypassword)) { array_push($errors, "Password required!"); }

if (!filter_var($myemail, FILTER_VALIDATE_EMAIL)) { array_push($errors, "Email is not valid!"); }

if ($mypassword != $mypassword2) { array_push($errors, "The two passwords do not match!"); }

	// Verify username and email don't exist in members table
	$user_check_query = "SELECT username, email FROM members WHERE username=:myusername OR email=:myemail LIMIT 1";
	$statement = $db->prepare($user_check_query);
	$statement->bindParam(':myusername',$myusername);
	$statement->bindParam(':myemail',$myemail);
	$statement->execute() or die(print_r($statement->errorInfo(), true));
	$user = $statement->fetch();

	if ($user) { // if user exists, which field?
	if ($user['username'] == $myusername) {
		array_push($errors, "Username already exists!");
	}
	if ($user['email'] == $myemail) {
		array_push($errors, "Email already exists!");
	}
}
if (count($errors) == 0) {

$encrypted_password = password_hash($mypassword, PASSWORD_DEFAULT);




$insert_sql="insert into members (username,password,email) values(:myusername,:encrypted_password,:myemail)";
$statement = $db->prepare($insert_sql);
$statement->bindParam(':myusername',$myusername);
$statement->bindParam(':encrypted_password',$encrypted_password);
$statement->bindParam(':myemail',$myemail);
$statement->execute() or die(print_r($statement->errorInfo(), true));
$pass = $statement->fetch();
;
echo "Registered";
header("refresh:3; url=main_login.php");

}
else
{
foreach ($errors as $error) {
echo "<p>$error</p>";
	}
}


// Again, we should never see this in a production environment
// printf("<br />SQL statement is $insert_sql");
ob_end_flush();
?>

<?php
  require 'footer.php';
?>