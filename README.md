# Catalyst

## Description
> The user_upload.php will accepts and processes the CSV file.
> The parsed file data will be inserted into a MySQL database.

### csv file format (email is unique)

| name   | surname  | email          |
| ------ | -----    |----------------|
| John   | Smith    |join@gmail.com  |

## How to use
> usage: php user_upload.php [--file <filename>] [--create_table]
    [--dry_run] [-u <username>] [-p] [-h <hostname>] [--help]

- file             name of the CSV to be parsed
- create_table     MySQL users table to be built
- dry_run          used with the --file directive, and run the script but not insert into the DB
- u                MySQL username
- p                MySQL password
- h                MySQL host
- help             output the above list of directives with details