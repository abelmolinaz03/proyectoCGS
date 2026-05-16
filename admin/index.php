<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");

if(!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin'){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

// Estadísticas
$total_usuarios = $conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_rutinas = $conexion->query("SELECT COUNT(*) FROM rutinas WHERE tipo = 'oficial'")->fetchColumn();
$total_marcas = $conexion->query("SELECT COUNT(*) FROM marcas_deportivas")->fetchColumn();

$rutinas = $conexion->query("SELECT * FROM rutinas WHERE tipo = 'oficial' ORDER BY fecha_creacion DESC")->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
?>

<main style="flex: 1;">
<div class="container py-5">

    <h2 class="fw-bold mb-5" style="color: var(--rojo-mezquita);">
        <i class="fa-solid fa-gauge me-2"></i>Panel de Administración
    </h2>

    <!-- Estadísticas -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="fa-solid fa-users fa-2x mb-2" style="color: var(--rojo-mezquita);"></i>
                <h3 class="fw-bold"><?php echo $total_usuarios; ?></h3>
                <p class="text-muted mb-0">Usuarios registrados</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="fa-solid fa-dumbbell fa-2x mb-2" style="color: var(--rojo-mezquita);"></i>
                <h3 class="fw-bold"><?php echo $total_rutinas; ?></h3>
                <p class="text-muted mb-0">Rutinas oficiales</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center">
                <i class="fa-solid fa-trophy fa-2x mb-2" style="color: var(--rojo-mezquita);"></i>
                <h3 class="fw-bold"><?php echo $total_marcas; ?></h3>
                <p class="text-muted mb-0">Marcas registradas</p>
            </div>
        </div>
    </div>

    <!-- Rutinas oficiales -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-dumbbell me-2"></i>Rutinas Oficiales
        </h4>
        <a href="/proyectoCGS/admin/rutinas/admincrea.php" class="btn text-white" style="background-color: var(--rojo-mezquita);">
            <i class="fa-solid fa-plus me-2"></i>Nueva rutina
        </a>
    </div>

    <?php if(empty($rutinas)): ?>
        <div class="alert alert-info">No hay rutinas oficiales todavía.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--rojo-mezquita); color: white;">
                    <tr>
                        <th>Título</th>
                        <th>Deporte</th>
                        <th>Dificultad</th>
                        <th>Duración</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rutinas as $rutina): ?>
                    <tr>
                        <td class="fw-semibold"><?php echo htmlspecialchars($rutina['titulo']); ?></td>
                        <td><?php echo $rutina['deporte']; ?></td>
                        <td>
                            <span class="badge" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                                <?php echo $rutina['dificultad']; ?>
                            </span>
                        </td>
                        <td><?php echo $rutina['duracion_minutos'] ? $rutina['duracion_minutos'].' min' : '—'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($rutina['fecha_creacion'])); ?></td>
                        <td>
                            <a href="/proyectoCGS/admin/rutinas/adminedita.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="/proyectoCGS/admin/rutinas/adminelimina.php?id=<?php echo $rutina['id_rutina']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(this)">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
</main>

<?php include("../includes/footer.php"); ?>