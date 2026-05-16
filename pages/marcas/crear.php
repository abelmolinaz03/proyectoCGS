<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/validaciones.php");

$errores = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario_id = $_SESSION['usuario_id'];
    $deporte = $_SESSION['deporte_usuario'];
    $marca = trim($_POST['marca']);
    $fecha = trim($_POST['fecha']);

    $errores = validar_longitudes([
        [$marca, 20, 'Marca / Puntuación'],
    ]);

    if(empty($errores)){
        $stmt = $conexion->prepare("INSERT INTO marcas_deportivas (id_usuario, deporte, tiempo_o_puntuacion, fecha_registro) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $deporte, $marca, $fecha]);

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
                <i class="fa-solid fa-plus me-2"></i>Añadir Marca
            </h2>
            <p class="text-muted mb-4">Deporte: <strong><?php echo $_SESSION['deporte_usuario']; ?></strong></p>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Marca / Puntuación</label>
                    <?php foreach($errores as $err): ?>
                        <div class="alert alert-danger py-2 small"><?php echo $err; ?></div>
                    <?php endforeach; ?>
                    <input type="text" name="marca" class="form-control" placeholder="Ej: 11.20s, 3 sets ganados..." maxlength="20" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" name="fecha" class="form-control" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Guardar
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include("../../includes/footer.php"); ?>