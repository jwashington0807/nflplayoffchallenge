<?php 

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Content-type: application/json');

    session_start();

    $_SESSION['email'] = '';
    $_SESSION['loggedin'] = '';
    $_SESSION['token'] = '';

    session_destroy();

    echo json_encode('session terminated');
?>