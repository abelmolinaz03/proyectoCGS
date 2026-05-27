<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/validaciones.php");

$id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];
$deporte = $_SESSION['deporte_usuario'];

$stmt = $conexion->prepare("SELECT * FROM marcas_deportivas WHERE id_marca = ? AND id_usuario = ?");
$stmt->execute([$id, $usuario_id]);
$marca = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$marca){
    die("No tienes permiso para editar esta marca.");
}

$errores = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $marca_valor = trim($_POST['marca']);
    $fecha = trim($_POST['fecha']);

    $errores = validar_longitudes([
        [$marca_valor, 20, 'Marca / Puntuación'],
    ]);

    if(empty($errores)){
        $stmt = $conexion->prepare("UPDATE marcas_deportivas SET tiempo_o_puntuacion=?, fecha_registro=? WHERE id_marca=? AND id_usuario=?");
        $stmt->execute([$marca_valor, $fecha, $id, $usuario_id]);

        header("Location: index.php");
        exit();
    }
}

include("../../includes/header.php");
?>
<main style="flex: 1;">
    <div class="container py-5" style="max-width: 600px;">
        <div class="card shadow border-0 p-4">
            <h2 class="fw-bold mb-1" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-pen me-2"></i>Editar Marca
            </h2>
            <p class="text-muted mb-4">Deporte: <strong><?php echo $deporte; ?></strong></p>

            <form method="POST" id="formEditar">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Marca / Puntuación</label>
                    <?php foreach($errores as $err): ?>
                        <div class="alert alert-danger py-2 small"><?php echo $err; ?></div>
                    <?php endforeach; ?>
                    <input type="text" name="marca" class="form-control" 
                        value="<?php echo htmlspecialchars($marca['tiempo_o_puntuacion']); ?>" maxlength="20" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" name="fecha" class="form-control" 
                        value="<?php echo htmlspecialchars($marca['fecha_registro']); ?>" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn text-white" style="background-color: var(--rojo-mezquita);"
                            onclick="confirmEdit()">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Actualizar
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</main>

<?php include("../../includes/footer.php"); ?>