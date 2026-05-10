<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario_id = $_SESSION['usuario_id'];
    $deporte = trim($_POST['deporte']);
    $marca = trim($_POST['marca']);
    $fecha = trim($_POST['fecha']);

    $stmt = $conexion->prepare("INSERT INTO marcas_deportivas (id_usuario, deporte, tiempo_o_puntuacion, fecha_registro) VALUES (?, ?, ?, ?)");
    $stmt->execute([$usuario_id, $deporte, $marca, $fecha]);

    header("Location: index.php");
    exit();
}
?>

<div class="container py-5" style="max-width: 600px;">
    <div class="card shadow border-0 p-4">
        <h2 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-plus me-2"></i>Añadir Marca
        </h2>

        <div class="mb-3">
            <label class="form-label fw-semibold">Deporte</label>
            <select name="deporte" class="form-select" required>
                <option value="" disabled selected>Selecciona un deporte</option>
                <option value="Atletismo">Atletismo</option>
                <option value="Fútbol">Fútbol</option>
                <option value="Natación">Natación</option>
                <option value="Ciclismo">Ciclismo</option>
                <option value="Baloncesto">Baloncesto</option>
                <option value="Tenis">Tenis</option>
                <option value="Padel">Padel</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Marca / Puntuación</label>
            <input type="text" name="marca" class="form-control" placeholder="Ej: 11.20s, 3 sets ganados..." required>
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
    </div>
</div>

<?php include("../../includes/footer.php"); ?>