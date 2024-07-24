<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-type: application/json');

// Need Secure Connection
require('./secure/dbconnection.php');
require('./models/player.php');

// Connect to DB
$connect = new DBConnection();
$connect -> connectToDatabase();

// Call the stored procedure and get the result
$sql = "CALL getleaderboard()";
$result = mysqli_query($connect -> con, $sql);
$output = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Initialize array to hold objects
$teams = [];

// Close SQL Connection
$connect -> closeConnection();

// Return JSON back to client
echo json_encode($output);


?>