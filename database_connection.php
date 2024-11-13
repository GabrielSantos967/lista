<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

$servername = "localhost";
$username = "u182359865_usuario"; // Ajuste com seu usuário
$password = "2lVIqZax&eM"; // Ajuste com sua senha
$dbname = "u182359865_lista";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("falhou: " . mysqli_connect_error());
}

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
