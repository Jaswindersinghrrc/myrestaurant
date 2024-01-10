<?php
    ob_start(); // session management
    session_start();

    require('databaseconnection.php');


$total_failed_login = 3;
$lockout_time = 15;
$account_locked = false;
date_default_timezone_set('America/Winnipeg');

$mypassword=$_POST['password'];

$select_sql = "SELECT password, id FROM members WHERE username=:username;";
$statement = $db->prepare($select_sql);
$statement->bindParam(':username',$_POST['username']);
$statement->execute();
$pass = $statement->fetch();

$returnedpassword=$pass['password'];
    
$checkpassword = password_verify($mypassword, $returnedpassword);


$user=$_POST['username'];

    // Check the database (Check user information)
$data = $db->prepare( 'SELECT failed_login, last_login FROM members WHERE username = (:username) LIMIT 1;' );
    $data->bindParam( ':username', $user, PDO::PARAM_STR );
    $data->execute();
    $row = $data->fetch();

    // Check to see if the user has been locked out.
    if( ( $data->rowCount() == 1 ) && ( $row[ 'failed_login' ] >= $total_failed_login ) )  {
        // User locked out. Following line should not  be included when in 
        // production, comment out for competency
        echo "<pre><br />This account has been locked due to too many incorrect logins.</pre>";

        // Calculate when the user would be allowed to login again
        $last_login = strtotime( $row[ 'last_login' ] );
        $timeout    = $last_login + ($lockout_time * 60);
        $timenow    = time();

        // Shows the login attempt timings.  The three lines below should not be 
        // included when in production, comment out for competency
        print "The last login was: " . date ("h:i:s", $last_login) . "<br />";
        print "The timenow is: " . date ("h:i:s", $timenow) . "<br />";
        print "The timeout is: " . date ("h:i:s", $timeout) . "<br />";

        // Check to see if enough time has passed, if it hasn't locked the account
        if( $timenow < $timeout ) {
            $account_locked = true;
            print "The account is locked for time<br />";
        }
    }

if($checkpassword && $_POST['password']<>'' && $account_locked == false){
        // Register $myusername and redirect to file index.php
        session_start();
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['userid'] = $pass['id'];
        header("Refresh:5; url=index.php", true, 303);
        echo "You have successfully logged in";

        // Reset bad login count
        $data = $db->prepare( 'UPDATE members SET failed_login = "0" WHERE username = (:username) LIMIT 1;' );
        $data->bindParam( ':username', $user, PDO::PARAM_STR );
        $data->execute();
    }
    else {
        echo "<p>Wrong username or password, or your account is Locked";

        // Update bad login count
        $data = $db->prepare( 'UPDATE members SET failed_login = (failed_login + 1) WHERE username = (:username) LIMIT 1;' );
        $data->bindParam( ':username', $user, PDO::PARAM_STR );
        $data->execute();
    }


// If returned password matches entered password, valid login
if($checkpassword){
    // Register $myusername and redirect to file "login_success.php"
    session_start();
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['userid'] = $pass['id']; // add user ID to session info
//header("Refresh:5; url=index.php", true, 303);
//echo "You have successfully logged in";

    // the following will be used for brute force attacks in the future
    //echo "Successful login as: ".$_SESSION['username'];
    header("refresh:1; url=login_success.php");
}
else {
    echo "Wrong Username or Password";
    // the following code should never be seen in a production website
    //echo "<pre>$select_sql</pre>";
    //echo "<pre>";
    //print_r($pass);
    //echo "<br /> password based on form: ".$mypassword;
    //echo "<br /> password from database: ".$returnedpassword;
    // These are the hashed password's components
    // password_verify will use this info to recreate the hash created by 
// password_hash().  This works because we know it uses bcrypt
    $algo = substr($returnedpassword, 0, 4); // $2y$ == Blowfish/bcrypt
    //echo "<br />          password algo: ".$algo;
    $cost = substr($returnedpassword, 4, 2);
    //echo "<br />          password cost: ".$cost;
    $salt = substr($returnedpassword, 7, 22);
    //echo "<br />          password salt: ".$salt;
    $hash = substr($returnedpassword, 29);
    //echo "<br />          password hash: ".$hash;
    
    // recreate hash from the form password and stored hash components
    $rehash_args=$algo.$cost."$".$salt;
    //echo "<br />   form password hashed: ".crypt($mypassword, $rehash_args);
    echo "</pre>";
}
// Set the last login time.  This pauses the wait time to the 
// $lockout_time for each attempt.
    $data = $db->prepare( 'UPDATE members SET last_login = now() WHERE username = (:username) LIMIT 1;' );
    $data->bindParam( ':username', $user, PDO::PARAM_STR );
    $data->execute();

ob_end_flush();
?>