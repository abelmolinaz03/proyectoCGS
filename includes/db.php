<?php
$host = getenv('MYSQL_HOST') ?: '127.0.0.1:3307';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASS') ?: '';
$db   = getenv('MYSQL_DB')   ?: 'sporthubCordoba';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>