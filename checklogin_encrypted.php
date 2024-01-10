<?php
ob_start(); // session management

require('databaseconnection.php'); 

$tbl_name="members"; // Table name if you wish to use a variable

$select_sql = "SELECT password, salt FROM members WHERE username=:username;";
$statement = $db->prepare($select_sql);
$statement->bindParam(':username',$_POST['username']);
$statement->execute();
$pass = $statement->fetch();

$returnedpassword=$pass['password'];
$returnedsalt=$pass['salt'];
	
// take password, salt and encrypt it as we did in the register page
$salted_password=$returnedsalt.$_POST['password'];
$checkpassword = hash("sha512", $salted_password);

// If returned password matches entered password, valid login
if($checkpassword==$returnedpassword && $_POST['password']<>''){
	// Register $myusername and redirect to file "login_success.php"
	session_start();
	$_SESSION['username'] = $_POST['username'];
	header("location:login_success.php");
}
else {
	echo "Wrong Username or Password";
	echo "<pre>$select_sql</pre>";
	echo "<pre>";
	print_r($pass);
	echo "<br /> password based on form: ";
	echo $checkpassword;
	echo "<br /> password from database: ";
	echo $returnedpassword;
	echo "<br /> salt from database: ";
	echo $returnedsalt;
	echo "</pre>";
}
ob_end_flush();
?>