<?php
include("../../includes/auth.php");
include("../../includes/db.php");

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM marcas_personales WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit();

?>