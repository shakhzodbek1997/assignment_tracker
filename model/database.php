<?php 
 // Configuration for the database connection
  $dsn = 'mysql:host=localhost;dbname=assignment_tracker';  // Database connection
  $username = 'root'; // Username for the database
  // i didn't set password to database!

  try{
    // Creating a new PDO instance to establish the database connection
    $db = new PDO($dsn, $username);
  }catch(PDOException $e){
    // If an error occurs during the connection, displays this error message
    $error = "Database Error: ";
    $error .= $e->getMessage();  // The .= operator is used for concatenation
    include('view/error.php');  // Display the error message in the error.php view
    exit(); // Stop further execution of the script
  }