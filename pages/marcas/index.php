<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];
$stmt = $conexion->prepare("SELECT * FROM marcas_deportivas WHERE id_usuario = ? ORDER BY fecha_registro DESC");
$stmt->execute([$id_usuario]);
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">Mis Marcas</h2>
        <a href="crear.php" class="btn btn-success">
            <i class="fa-solid fa-plus me-2"></i>Añadir marca
        </a>
    </div>

    <?php if (empty($marcas)): ?>
        <div class="alert alert-info">Todavía no tienes marcas registradas. ¡Añade tu primera!</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead style="background-color: var(--rojo-mezquita); color: white;">
                    <tr>
                        <th>Deporte</th>
                        <th>Marca / Puntuación</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($marcas as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['deporte']); ?></td>
                        <td><?php echo htmlspecialchars($row['tiempo_o_puntuacion']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_registro']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo $row['id_marca']; ?>" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="eliminar.php?id=<?php echo $row['id_marca']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
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

<?php include("../../includes/footer.php"); ?>