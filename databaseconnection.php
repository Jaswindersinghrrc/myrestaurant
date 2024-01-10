<?php
    //Define variables needed to connect to the MySQL database
    define('DB_DSN', 'mysql:host=192.168.56.101;dbname=blog;charset=utf8');
    define('DB_USER', 'blogadmin');
    define('DB_PASS', 'bloguser');

    //Connect to the database. If the connection fails the main form will not display
    try {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } catch (PDOException $e) {
        echo 'Error: '.$e->getMessage();
        die(); // Force execution to stop on errors.
    }
?>

