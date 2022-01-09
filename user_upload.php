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

function read_file()
{
    // validate if csv file

    $users = [];
    if (($handle = fopen("users.csv", "r")) !== FALSE) {
        $schema = fgetcsv($handle);
        while (($line = fgetcsv($handle)) !== FALSE) {
            $data = [
                trim($schema[0]) => trim($line[0]),
                trim($schema[1]) => trim($line[1]),
                trim($schema[2]) => trim($line[2]),
            ];

            array_push($users, $data);
        }

        return $users;
        fclose($handle);
    } else {
        echo "Error opening the file.";
    }
}

function format_user_data($users)
{

    $formatted_users = [];
    for ($i = 0; $i < count($users); $i++) {
        $user = $users[$i];

        if (!filter_var($user["email"], FILTER_VALIDATE_EMAIL)) {
            fprintf(STDOUT, "Invalid Email: %s\n", $user["email"]);
        } else {
            $formatted_users[$i]["name"] = ucfirst($user["name"]);
            $formatted_users[$i]["surname"] = ucfirst($user["surname"]);
            $formatted_users[$i]["email"] = strtolower($user["email"]);
        }
    }

    foreach ($formatted_users as $userr) {
        echo var_dump($userr);
    }
}

function insert_data()
{
}

function main()
{
    // $conn = create_db();
    // create_tb($conn);
    $users = read_file();
    format_user_data($users);
}

main();
