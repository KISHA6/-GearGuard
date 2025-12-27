<?php
require_once "config/db.php";

$r = mysqli_query($conn, "SELECT DATABASE()");
$d = mysqli_fetch_row($r);

echo "Connected DB: " . $d[0] . "<br>";

$q = mysqli_query($conn, "SELECT email, password FROM users");
while ($row = mysqli_fetch_assoc($q)) {
    echo $row['email'] . " => " . $row['password'] . "<br>";
}
