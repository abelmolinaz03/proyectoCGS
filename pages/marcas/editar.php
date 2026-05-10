<?php
include("../../includes/auth.php");
include("../../includes/db.php");

$id = $_GET['id'];
$usuario_id = $_SESSION['id'];

// Obtener datos actuales (SEGURIDAD AÑADIDA)
$stmt = $conn->prepare("SELECT * FROM marcas_personales WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

$result = $stmt->get_result();
$marca = $result->fetch_assoc();

// Si no existe o no es del usuario → bloquear
if(!$marca){
    die("No tienes permiso para editar esta marca");
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $deporte = $_POST['deporte'];
    $marca_valor = $_POST['marca'];
    $fecha = $_POST['fecha'];

    // UPDATE con seguridad
    $stmt = $conn->prepare("UPDATE marcas_personales SET deporte=?, marca=?, fecha=? WHERE id=? AND usuario_id=?");
    $stmt->bind_param("sssii", $deporte, $marca_valor, $fecha, $id, $usuario_id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<h2>Editar marca</h2>

<form method="POST">
    <input type="text" name="deporte" value="<?php echo $marca['deporte']; ?>"><br>
    <input type="text" name="marca" value="<?php echo $marca['marca']; ?>"><br>
    <input type="date" name="fecha" value="<?php echo $marca['fecha']; ?>"><br>

    <button type="submit">Actualizar</button>
</form>