<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_rutina = $_GET['id'] ?? null;
$id_usuario = $_SESSION['usuario_id'];

if(!$id_rutina){
    header("Location: index.php");
    exit();
}

// Obtener rutina (oficial o propia)
$stmt = $conexion->prepare("SELECT * FROM rutinas WHERE id_rutina = ? AND (tipo = 'oficial' OR id_usuario = ?)");
$stmt->execute([$id_rutina, $id_usuario]);
$rutina = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$rutina){
    die("No tienes acceso a esta rutina."); //Detiene la ejecución del script
}

// Obtener ejercicios
$stmt2 = $conexion->prepare("SELECT * FROM ejercicios_rutina WHERE id_rutina = ? ORDER BY orden ASC");
$stmt2->execute([$id_rutina]);
$ejercicios = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5" style="max-width: 800px;">

    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">
        <i class="fa-solid fa-arrow-left me-1"></i>Volver
    </a>

    <div class="card shadow border-0 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--rojo-mezquita);">
                    <?php echo htmlspecialchars($rutina['titulo']); ?>
                </h2>
                <span class="badge me-2" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                    <?php echo $rutina['dificultad']; ?>
                </span>
                <span class="badge" style="background-color: var(--rojo-mezquita); color: white;">
                    <?php echo $rutina['tipo'] === 'oficial' ? 'Oficial' : 'Personal'; ?>
                </span>
            </div>
            <?php if($rutina['duracion_minutos']): ?>
                <span class="text-muted"><i class="fa-solid fa-clock me-1"></i><?php echo $rutina['duracion_minutos']; ?> min</span>
            <?php endif; ?>
        </div>

        <?php if($rutina['descripcion']): ?>
            <p class="text-muted"><?php echo htmlspecialchars($rutina['descripcion']); ?></p>
        <?php endif; ?>

        <p class="small text-muted mb-0">
            <i class="fa-solid fa-tag me-1"></i><?php echo $rutina['deporte']; ?>
        </p>
    </div>

    <!-- Ejercicios -->
    <h4 class="fw-bold mb-3" style="color: var(--rojo-mezquita);">
        <i class="fa-solid fa-list-check me-2"></i>Ejercicios
    </h4>

    <?php if(empty($ejercicios)): ?>
        <div class="alert alert-info">Esta rutina no tiene ejercicios añadidos.</div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach($ejercicios as $i => $ej): ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fw-bold fs-4 text-center" style="color: var(--dorado-mezquita); min-width: 32px;">
                            <?php echo $i+1; ?>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($ej['nombre']); ?></h6>
                            <div class="d-flex gap-3 small text-muted flex-wrap">
                                <?php if($ej['series']): ?>
                                    <span><i class="fa-solid fa-repeat me-1"></i><?php echo $ej['series']; ?> series</span>
                                <?php endif; ?>
                                <?php if($ej['repeticiones']): ?>
                                    <span><i class="fa-solid fa-hashtag me-1"></i><?php echo htmlspecialchars($ej['repeticiones']); ?></span>
                                <?php endif; ?>
                                <?php if($ej['descanso_segundos']): ?>
                                    <span><i class="fa-solid fa-pause me-1"></i><?php echo $ej['descanso_segundos']; ?>s descanso</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if($rutina['tipo'] === 'personal' && $rutina['id_usuario'] == $id_usuario): ?>
        <div class="d-flex gap-2 mt-4">
            <a href="editar.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-warning">
                <i class="fa-solid fa-pen me-2"></i>Editar rutina
            </a>
            <a href="eliminar.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-danger" onclick="return confirmDelete(this)">
                <i class="fa-solid fa-trash me-2"></i>Eliminar
            </a>
        </div>
    <?php endif; ?>

</div>
</main>

<?php include("../../includes/footer.php"); ?>