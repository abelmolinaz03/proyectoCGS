<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];
$deporte = $_SESSION['deporte_usuario'];

// Rutinas oficiales de su deporte
$stmt = $conexion->prepare("SELECT * FROM rutinas WHERE deporte = ? AND tipo = 'oficial' ORDER BY fecha_creacion DESC");
$stmt->execute([$deporte]);
$rutinas_oficiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Rutinas personales del usuario
$stmt = $conexion->prepare("SELECT * FROM rutinas WHERE id_usuario = ? AND tipo = 'personal' ORDER BY fecha_creacion DESC");
$stmt->execute([$id_usuario]);
$rutinas_personales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5">

    <!-- Rutinas Oficiales -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-dumbbell me-2"></i>Rutinas Oficiales — <?php echo $deporte; ?>
        </h2>
    </div>

    <?php if (empty($rutinas_oficiales)): ?>
        <div class="alert alert-info mb-5">No hay rutinas oficiales para <?php echo $deporte; ?> todavía.</div>
    <?php else: ?>
        <div class="row g-4 mb-5">
            <?php foreach($rutinas_oficiales as $rutina): ?>
            <div class="col-md-4">
                <div class="card card-custom h-100 shadow-sm border-0 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($rutina['titulo']); ?></h5>
                        <span class="badge" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                            <?php echo $rutina['dificultad']; ?>
                        </span>
                    </div>
                    <p class="small text-muted"><?php echo htmlspecialchars($rutina['descripcion']); ?></p>
                    <?php if($rutina['duracion_minutos']): ?>
                        <p class="small"><i class="fa-solid fa-clock me-1"></i><?php echo $rutina['duracion_minutos']; ?> min</p>
                    <?php endif; ?>
                    <a href="ver.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm text-white mt-auto" style="background-color: var(--rojo-mezquita);">
                        <i class="fa-solid fa-eye me-1"></i>Ver rutina
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Rutinas Personales -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-person-running me-2"></i>Mis Rutinas Personales
        </h2>
        <a href="crear.php" class="btn text-white" style="background-color: var(--rojo-mezquita);">
            <i class="fa-solid fa-plus me-2"></i>Crear rutina
        </a>
    </div>

    <?php if (empty($rutinas_personales)): ?>
        <div class="alert alert-info">Todavía no tienes rutinas personales. ¡Crea tu primera!</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($rutinas_personales as $rutina): ?>
            <div class="col-md-4">
                <div class="card card-custom h-100 shadow-sm border-0 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($rutina['titulo']); ?></h5>
                        <span class="badge" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                            <?php echo $rutina['dificultad']; ?>
                        </span>
                    </div>
                    <p class="small text-muted"><?php echo htmlspecialchars($rutina['descripcion']); ?></p>
                    <?php if($rutina['duracion_minutos']): ?>
                        <p class="small"><i class="fa-solid fa-clock me-1"></i><?php echo $rutina['duracion_minutos']; ?> min</p>
                    <?php endif; ?>
                    <div class="d-flex gap-2 mt-auto">
                        <a href="ver.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm text-white" style="background-color: var(--rojo-mezquita);">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="editar.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <a href="eliminar.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(this)">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
</main>

<?php include("../../includes/footer.php"); ?>