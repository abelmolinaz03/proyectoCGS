<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];

// Cancelar reserva
if(isset($_GET['cancelar'])){
    $id_reserva = $_GET['cancelar'];
    $stmt = $conexion->prepare("DELETE FROM reservas WHERE id_reserva = ? AND id_usuario = ? AND fecha_reserva >= CURDATE()");
    $stmt->execute([$id_reserva, $id_usuario]);
    header("Location: reservasuser.php");
    exit();
}

// Obtener reservas futuras
$stmt = $conexion->prepare("
    SELECT r.*, p.nombre_pista, p.direccion, p.imagen
    FROM reservas r
    JOIN pistas p ON r.id_pista = p.id_pista
    WHERE r.id_usuario = ? AND r.fecha_reserva >= CURDATE()
    ORDER BY r.fecha_reserva ASC, r.hora_inicio ASC
");
$stmt->execute([$id_usuario]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener reservas pasadas
$stmt2 = $conexion->prepare("
    SELECT r.*, p.nombre_pista, p.direccion
    FROM reservas r
    JOIN pistas p ON r.id_pista = p.id_pista
    WHERE r.id_usuario = ? AND r.fecha_reserva < CURDATE()
    ORDER BY r.fecha_reserva DESC
    LIMIT 5
");
$stmt2->execute([$id_usuario]);
$reservas_pasadas = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-calendar-check me-2"></i>Mis Reservas
        </h2>
        <a href="index.php" class="btn text-white" style="background-color: var(--rojo-mezquita);">
            <i class="fa-solid fa-plus me-2"></i>Nueva reserva
        </a>
    </div>

    <!-- Reservas próximas -->
    <h5 class="fw-bold mb-3">Próximas</h5>
    <?php if(empty($reservas)): ?>
        <div class="alert alert-info mb-5">No tienes reservas próximas.</div>
    <?php else: ?>
        <div class="row g-3 mb-5">
            <?php foreach($reservas as $r): ?>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-4 d-flex flex-row align-items-center gap-3">
                    <?php if($r['imagen']): ?>
                        <img src="/proyectoCGS/multimedia/pistas/<?php echo htmlspecialchars($r['imagen']); ?>"
                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px;" alt="">
                    <?php else: ?>
                        <div style="width: 70px; height: 70px; background: var(--crema-mezquita); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-location-dot" style="color: var(--dorado-mezquita);"></i>
                        </div>
                    <?php endif; ?>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($r['nombre_pista']); ?></h6>
                        <p class="small text-muted mb-1">
                            <i class="fa-solid fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($r['fecha_reserva'])); ?>
                        </p>
                        <p class="small text-muted mb-0">
                            <i class="fa-solid fa-clock me-1"></i><?php echo substr($r['hora_inicio'], 0, 5); ?> — <?php echo substr($r['hora_fin'], 0, 5); //Recorta los primeros 5 caracteres para mostrar solo 08:00 en vez de 08:00:00 ?>
                        </p>
                    </div>
                    <a href="reservasuser.php?cancelar=<?php echo $r['id_reserva']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirmDelete(this)">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Reservas pasadas -->
    <?php if(!empty($reservas_pasadas)): ?>
    <h5 class="fw-bold mb-3 text-muted">Historial</h5>
    <div class="row g-3">
        <?php foreach($reservas_pasadas as $r): ?>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 d-flex flex-row align-items-center gap-3" style="opacity: 0.6;">
                <div style="width: 70px; height: 70px; background: var(--crema-mezquita); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-location-dot" style="color: var(--dorado-mezquita);"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($r['nombre_pista']); ?></h6>
                    <p class="small text-muted mb-1">
                        <i class="fa-solid fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($r['fecha_reserva'])); ?>
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="fa-solid fa-clock me-1"></i><?php echo substr($r['hora_inicio'], 0, 5); ?> — <?php echo substr($r['hora_fin'], 0, 5); ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>
</main>

<?php include("../../includes/footer.php"); ?>