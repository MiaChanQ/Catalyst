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
        $field = fgetcsv($handle);
        while (($line = fgetcsv($handle)) !== FALSE) {
            $data = [
                trim($field[0]) => trim($line[0]),
                trim($field[1]) => trim($line[1]),
                trim($field[2]) => trim($line[2]),
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

    return $formatted_users;
}

function insert_data($conn, $users)
{
    for ($i = 0; $i < count($users); $i++) {
        $fieldVal1 = mysqli_real_escape_string($conn, $users[$i]["name"]);
        $fieldVal2 = mysqli_real_escape_string($conn, $users[$i]["surname"]);
        $fieldVal3 = mysqli_real_escape_string($conn, $users[$i]["email"]);
        $query = "INSERT INTO users (name, surname, email) VALUES ( '" . $fieldVal1 . "','" . $fieldVal2 . "','" . $fieldVal3 . "' )";
        mysqli_query($conn, $query);
    }
}

function _usage()
{
    echo "usage: php user_upload.php [--file <filename>] [--create_table]
    [--dry_run] [-u <username>] [-p] [-h <hostname>] [--help]\n";
    echo "file             name of the CSV to be parsed\n";
    echo "create_table     MySQL users table to be built\n";
    echo "dry_run          used with the --file directive, and run the
                           script but not insert into the DB\n";
    echo "u                MySQL username\n";
    echo "p                MySQL password\n";
    echo "h                MySQL host\n";
    echo "help             output the above list of directives with details\n";
}

function main()
{
    // $conn = create_db();
    // create_tb($conn);
    // $users = read_file();
    // $formatted_users = format_user_data($users);
    // insert_data($conn, $formatted_users);

    $longdir = array(
        "file:",
        "create_table",
        "dry_run",
        "help"
    );
    $directives = getopt("", $longdir);

    if (array_key_exists("help", $directives)) {
        _usage();
    }
}


main();
