<?php
$servername = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'db_k2rent';

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn === false) {
    die("ERROR: Could not connect." . mysqli_connect_error());
}
