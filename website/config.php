<?php

  session_start();
  ob_start();

  $host =  'localhost';
  $user = 'root';
  $password = 'root';
  $dbname = 'tms';

  // Set DSNs
  $dsn = 'mysql:host='. $host .';dbname='. $dbname;
  try{
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }
  catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
  }

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>