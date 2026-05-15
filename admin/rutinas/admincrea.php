<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../../includes/db.php");
include("../../includes/header.php");

// Verificar que es admin
if(!isset($_SESSION['usuario_id'])){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

$deportes = ["Atletismo", "Fútbol", "Baloncesto", "Pádel", "Ciclismo", "Natación", "Tenis"];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $deporte = $_POST['deporte'];
    $dificultad = $_POST['dificultad'];
    $duracion = trim($_POST['duracion_minutos']);

    $stmt = $conexion->prepare("INSERT INTO rutinas (titulo, descripcion, deporte, tipo, id_usuario, dificultad, duracion_minutos) VALUES (?, ?, ?, 'oficial', NULL, ?, ?)");
    $stmt->execute([$titulo, $descripcion, $deporte, $dificultad, $duracion]);
    $id_rutina = $conexion->lastInsertId();

    $nombres = $_POST['ejercicio_nombre'] ?? [];
    $series = $_POST['ejercicio_series'] ?? [];
    $reps = $_POST['ejercicio_repeticiones'] ?? [];
    $descansos = $_POST['ejercicio_descanso'] ?? [];

    foreach($nombres as $i => $nombre){
        if(!empty($nombre)){
            $stmt2 = $conexion->prepare("INSERT INTO ejercicios_rutina (id_rutina, nombre, series, repeticiones, descanso_segundos, orden) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt2->execute([$id_rutina, $nombre, $series[$i], $reps[$i], $descansos[$i], $i+1]);
        }
    }

    header("Location: /proyectoCGS/admin/index.php");
    exit();
}
?>

<main style="flex: 1;">
<div class="container py-5" style="max-width: 800px;">
    <div class="card shadow border-0 p-4">
        <h2 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-plus me-2"></i>Crear Rutina Oficial
        </h2>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-semibold">Título</label>
                <input type="text" name="titulo" class="form-control" placeholder="Ej: Entrenamiento de velocidad" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3" placeholder="Describe el objetivo..."></textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Deporte</label>
                    <select name="deporte" class="form-select" required>
                        <option value="" disabled selected>Selecciona</option>
                        <?php foreach($deportes as $d): ?>
                            <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dificultad</label>
                    <select name="dificultad" class="form-select" required>
                        <option value="Principiante">Principiante</option>
                        <option value="Intermedio" selected>Intermedio</option>
                        <option value="Avanzado">Avanzado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Duración (minutos)</label>
                    <input type="number" name="duracion_minutos" class="form-control" placeholder="Ej: 45" min="1">
                </div>
            </div>

            <h5 class="fw-bold mb-3" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-list-check me-2"></i>Ejercicios
            </h5>

            <div id="ejercicios-container">
                <div class="ejercicio-row card p-3 mb-3 border-0 shadow-sm">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Ejercicio</label>
                            <input type="text" name="ejercicio_nombre[]" class="form-control form-control-sm" placeholder="Ej: Sentadillas">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Series</label>
                            <input type="number" name="ejercicio_series[]" class="form-control form-control-sm" placeholder="3" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Repeticiones</label>
                            <input type="text" name="ejercicio_repeticiones[]" class="form-control form-control-sm" placeholder="Ej: 12 o 30seg">
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
            </div>

            <button type="button" id="añadir-ejercicio" class="btn btn-outline-secondary btn-sm mb-4">
                <i class="fa-solid fa-plus me-1"></i>Añadir ejercicio
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Guardar rutina oficial
                </button>
                <a href="/proyectoCGS/admin/index.php" class="btn btn-outline-secondary">Cancelar</a>
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