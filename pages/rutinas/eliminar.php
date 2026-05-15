<?php
include("../../includes/auth.php");
include("../../includes/db.php");

$id_rutina = $_GET['id'] ?? null;
$id_usuario = $_SESSION['usuario_id'];

if($id_rutina){
    $stmt = $conexion->prepare("DELETE FROM rutinas WHERE id_rutina = ? AND id_usuario = ? AND tipo = 'personal'");
    $stmt->execute([$id_rutina, $id_usuario]);
}

header("Location: index.php");
exit();
?>