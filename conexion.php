<?php
$host = "127.0.0.1";
$user = "root"; 
$pass = "1020767191"; 
$db   = "ferreteria_universal";
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>