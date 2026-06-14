<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/validaciones.php");

$id_rutina = $_GET['id'] ?? null;
$id_usuario = $_SESSION['usuario_id'];

if(!$id_rutina){
    header("Location: index.php");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM rutinas WHERE id_rutina = ? AND id_usuario = ? AND tipo = 'personal'");
$stmt->execute([$id_rutina, $id_usuario]);
$rutina = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$rutina){
    die("No tienes permiso para editar esta rutina.");
}

$stmt2 = $conexion->prepare("SELECT * FROM ejercicios_rutina WHERE id_rutina = ? ORDER BY orden ASC");
$stmt2->execute([$id_rutina]);
$ejercicios = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$errores = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titulo      = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $dificultad  = $_POST['dificultad'];
    $duracion    = trim($_POST['duracion_minutos']);
    $duracion    = $duracion !== '' ? (int)$duracion : null;

    // Validar longitud del título
    $errores = validar_longitudes([
        [$titulo, 100, 'Título'],
    ]);

    // Solo guarda si NO hay errores (estaba invertido)
    if(empty($errores)){
        $stmt = $conexion->prepare(
            "UPDATE rutinas SET titulo=?, descripcion=?, dificultad=?, duracion_minutos=?
             WHERE id_rutina=? AND id_usuario=?"
        );
        $stmt->execute([$titulo, $descripcion, $dificultad, $duracion, $id_rutina, $id_usuario]);

        $conexion->prepare("DELETE FROM ejercicios_rutina WHERE id_rutina = ?")->execute([$id_rutina]);

        $nombres   = $_POST['ejercicio_nombre'] ?? [];
        $series    = $_POST['ejercicio_series'] ?? [];
        $reps      = $_POST['ejercicio_repeticiones'] ?? [];
        $descansos = $_POST['ejercicio_descanso'] ?? [];

        foreach($nombres as $i => $nombre){
            if(!empty($nombre)){
                $serie    = isset($series[$i])    && $series[$i]    !== '' ? (int)$series[$i]    : null;
                $descanso = isset($descansos[$i]) && $descansos[$i] !== '' ? (int)$descansos[$i] : null;
                $stmt2 = $conexion->prepare(
                    "INSERT INTO ejercicios_rutina (id_rutina, nombre, series, repeticiones, descanso_segundos, orden)
                     VALUES (?, ?, ?, ?, ?, ?)"
                );
                $stmt2->execute([$id_rutina, $nombre, $serie, $reps[$i], $descanso, $i + 1]);
            }
        }

        header("Location: ver.php?id=" . $id_rutina);
        exit();
    }
}

include("../../includes/header.php");
?>

<main style="flex: 1;">
<div class="container py-5" style="max-width: 800px;">
    <div class="card shadow border-0 p-4">
        <h2 class="fw-bold mb-1" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-pen me-2"></i>Editar Rutina
        </h2>
        <p class="text-muted mb-4">Deporte: <strong><?php echo $_SESSION['deporte_usuario']; ?></strong></p>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Título</label>
                <?php foreach($errores as $err): ?>
                    <div class="alert alert-danger py-2 small"><?php echo $err; ?></div>
                <?php endforeach; ?>
                <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($rutina['titulo']); ?>" maxlength="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($rutina['descripcion']); ?></textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Dificultad</label>
                    <select name="dificultad" class="form-select" required>
                        <option value="Principiante" <?php echo $rutina['dificultad'] === 'Principiante' ? 'selected' : ''; ?>>Principiante</option>
                        <option value="Intermedio" <?php echo $rutina['dificultad'] === 'Intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                        <option value="Avanzado" <?php echo $rutina['dificultad'] === 'Avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Duración (minutos)</label>
                    <input type="number" name="duracion_minutos" class="form-control" value="<?php echo $rutina['duracion_minutos']; ?>" min="1">
                </div>
            </div>

            <h5 class="fw-bold mb-3" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-list-check me-2"></i>Ejercicios
            </h5>

            <div id="ejercicios-container">
                <?php if(empty($ejercicios)): ?>
                <div class="ejercicio-row card p-3 mb-3 border-0 shadow-sm">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Ejercicio</label>
                            <input type="text" name="ejercicio_nombre[]" class="form-control form-control-sm" placeholder="Ej: Sentadillas" maxlength="100">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Series</label>
                            <input type="number" name="ejercicio_series[]" class="form-control form-control-sm" placeholder="3" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Repeticiones</label>
                            <input type="text" name="ejercicio_repeticiones[]" class="form-control form-control-sm" placeholder="Ej: 12 o 30seg" maxlength="50">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Descanso (seg)</label>
                            <input type="number" name="ejercicio_descanso[]" class="form-control form-control-sm" placeholder="60" min="0">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger w-100 eliminar-ejercicio">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <?php foreach($ejercicios as $ej): ?>
                    <div class="ejercicio-row card p-3 mb-3 border-0 shadow-sm">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Ejercicio</label>
                                <input type="text" name="ejercicio_nombre[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($ej['nombre']); ?>" maxlength="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Series</label>
                                <input type="number" name="ejercicio_series[]" class="form-control form-control-sm" value="<?php echo $ej['series']; ?>" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Repeticiones</label>
                                <input type="text" name="ejercicio_repeticiones[]" class="form-control form-control-sm" value="<?php echo htmlspecialchars($ej['repeticiones']); ?>" maxlength="50">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Descanso (seg)</label>
                                <input type="number" name="ejercicio_descanso[]" class="form-control form-control-sm" value="<?php echo $ej['descanso_segundos']; ?>" min="0">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger w-100 eliminar-ejercicio">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <button type="button" id="añadir-ejercicio" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="fa-solid fa-plus me-1"></i>Añadir ejercicio
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Guardar cambios
                </button>
                <a href="ver.php?id=<?php echo $id_rutina; ?>" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</main>

<script>
document.getElementById('añadir-ejercicio').addEventListener('click', function(){
    const container = document.getElementById('ejercicios-container');
    const primera = container.querySelector('.ejercicio-row');
    const nueva = primera.cloneNode(true);
    nueva.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(nueva);
    bindEliminar();
});

function bindEliminar(){
    document.querySelectorAll('.eliminar-ejercicio').forEach(btn => {
        btn.onclick = function(){
            const filas = document.querySelectorAll('.ejercicio-row');
            if(filas.length > 1) this.closest('.ejercicio-row').remove();
        };
    });
}
bindEliminar();
</script>

<?php include("../../includes/footer.php"); ?>