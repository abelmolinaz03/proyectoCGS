<?php
include("../../includes/auth.php");
include("../../includes/db.php");


$id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conexion->prepare("DELETE FROM marcas_deportivas WHERE id_marca = ? AND id_usuario = ?");
$stmt->execute([$id, $usuario_id]);

header("Location: index.php");
exit();
?>