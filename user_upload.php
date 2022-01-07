<?php

function create_db()
{
    $servername = "localhost";
    $username = "catalyst";
    $password = "qwert123Aa";
    //$dbname = "catalystDB";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
        die("Connection failed:\n" . mysqli_connect_error() . "\n");
    }
    echo "Connected MySQL successfully\n";

    // Create database
    $sql = "CREATE DATABASE if not exists catalystDB";
    if ($conn->query($sql) === TRUE) {
        $conn->select_db("catalystDB");
        echo "Database created successfully\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
    }

    return $conn;
}

function create_tb($conn)
{
    $sql = "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        surname VARCHAR(30) NOT NULL,
        email VARCHAR(50) Unique
        );";

    if ($conn->query($sql) === TRUE) {
        echo "Table users created successfully\n";
    } else {
        echo "Error creating table: " . $conn->error . "\n";
    }
}

function main()
{
    $conn = create_db();
    create_tb($conn);
}

main();
