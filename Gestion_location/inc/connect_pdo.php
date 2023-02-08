<?php
$servername = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'db_k2rent';
try {
        $bdd = new PDO('mysql:host='.$servername.';dbname='.$dbname.';charset=utf8', $username, $password);
} catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
}
