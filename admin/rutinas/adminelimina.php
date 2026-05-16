<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../../includes/db.php");

if(!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin'){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

$id_rutina = $_GET['id'] ?? null;

if($id_rutina){
    $conexion->prepare("DELETE FROM rutinas WHERE id_rutina = ? AND tipo = 'oficial'")->execute([$id_rutina]);
}

header("Location: /proyectoCGS/admin/index.php");
exit();
?>