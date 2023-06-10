<?php

// Connect to the database
use Classes\Secrets;

try {
    $host = Secrets::get('sqlHost');
    $user = Secrets::get('sqlUser');
    $pass = Secrets::get('sqlPass');
    $db = Secrets::get('sqlDatabase');

    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected";
} catch (PDOException $e) {
    // TODO: Something better
    echo "Failed to connect to DB";
    die();
}
// Check that all tables exist and have the correct schema

// If we're missing tables or columns, then reinstall the whole thing, wiping all data without verification.
