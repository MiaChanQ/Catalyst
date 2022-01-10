<?php

function create_db($servername, $username, $password)
{
    // Create connection
    try {
        $conn = mysqli_connect($servername, $username, $password);
        echo "Connected MySQL successfully\n";
    } catch (Throwable $Error) {
        die("Connection failed:\n" . mysqli_connect_error() . "\n");
    }

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

function read_file($filename)
{
    // Check if csv file or not.
    try {
        $extension = explode(".", $filename)[1];
        if ($extension != "csv") {
            die("File should be csv file. \n");
        }
    } catch (Exception $e) {
        die("No file extention detected. \n");
    }


    $users = [];
    // Open the file.
    try {
        $handle = fopen($filename, "r");
    } catch (Throwable $Error) {
        die("Cannot open the file. \n");
    }
    // Read the file.
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
    $shortdir = "u:p:h:";
    $longdir = array(
        "file:",
        "create_table",
        "dry_run",
        "help"
    );
    $directives = getopt($shortdir, $longdir);
    if (array_key_exists("help", $directives)) {
        _usage($directives);
        return;
    }


    $conn = create_db($directives["h"], $directives["u"], $directives["p"]);
    if (array_key_exists("create_table", $directives)) {
        create_tb($conn);
        exit(0);
    }

    if (!array_key_exists("file", $directives)) {
        fprintf(STDERR, "Error: No file chosen\n");
        _usage();
        exit(1);
    }

    if (!file_exists($directives["file"])) {
        fprintf(STDERR, "Error: File is invalid or does not exist\n");
        exit(2);
    }

    $users = read_file($directives["file"]);
    $formatted_users = format_user_data($users);
    if (array_key_exists("dry_run", $directives)) {
        $conn->close();
        return;
    }
    insert_data($conn, $formatted_users);
    $conn->close();
    exit(0);
}

main();
