<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];
$deporte = $_SESSION['deporte_usuario'];

$stmt = $conexion->prepare("SELECT * FROM marcas_deportivas WHERE id_usuario = ? AND deporte = ? ORDER BY fecha_registro DESC");
$stmt->execute([$id_usuario, $deporte]);
$marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main style="flex: 1">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-trophy me-2"></i>Mis Marcas — <?php echo $deporte; ?>
            </h2>
            <a href="crear.php" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                <i class="fa-solid fa-plus me-2"></i>Añadir marca
            </a>
        </div>

        <?php if (empty($marcas)): ?>
            <div class="alert alert-info">Todavía no tienes marcas de <?php echo $deporte; ?>. ¡Añade tu primera!</div>
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
                                <a href="eliminar.php?id=<?php echo $row['id_marca']; ?>" 
                                class="btn btn-sm btn-danger"
                                onclick="return confirmDelete(this)">
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

<?php include("../../includes/footer.php"); ?>