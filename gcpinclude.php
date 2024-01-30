<?php
function WriteHeaders($TitleBar, $StyleFile, $ScriptFile)
{
    echo '<!doctype html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title>' . $TitleBar . '</title>';
    echo '<link rel="stylesheet" type="text/css" href="' . $StyleFile . '.css"/>';
    echo '<script src="' . $ScriptFile . '.js" defer></script>';
    echo '</head>
    <body>';
}

function WriteFooters()
{
    echo "</body>\n";
    echo "</html>\n";
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt', 'r');
    $Host = trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh));
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password, $Database, $Port);
    // if the connection and authentication are successful,
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_errno != 0) {
        echo "<p>Connection failed. Unable to open database $Database. Error: "
            . $mysqlObj->connect_error
            . '</p>';
        // stop executing the php script
        exit ();
    }

    return $mysqlObj;
}

function CloseConnection($mysqlObj)
{
    $mysqlObj->close();
}
?>