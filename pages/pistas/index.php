<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$deporte = $_SESSION['deporte_usuario'];

$stmt = $conexion->prepare("SELECT * FROM pistas WHERE deporte_asociado = ? ORDER BY nombre_pista ASC");
$stmt->execute([$deporte]);
$pistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-map-location-dot me-2"></i>Pistas de <?php echo $deporte; ?>
        </h2>
        <a href="reservasuser.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-calendar-check me-2"></i>Mis reservas
        </a>
    </div>

    <?php if(empty($pistas)): ?>
        <div class="alert alert-info">No hay pistas disponibles para <?php echo $deporte; ?> todavía.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($pistas as $pista): ?>
            <div class="col-md-4">
                <div class="card card-custom h-100 border-0 shadow-sm">
                    <?php if($pista['imagen']): ?>
                        <img src="/proyectoCGS/multimedia/pistas/<?php echo htmlspecialchars($pista['imagen']); ?>"
                             class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($pista['nombre_pista']); ?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="height: 200px; background-color: var(--crema-mezquita);">
                            <i class="fa-solid fa-image fa-3x" style="color: var(--dorado-mezquita);"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($pista['nombre_pista']); ?></h5>
                        <p class="small text-muted mb-1">
                            <i class="fa-solid fa-location-dot me-1" style="color: var(--rojo-mezquita);"></i>
                            <?php echo htmlspecialchars($pista['direccion']); ?>
                        </p>
                        <p class="small text-muted flex-grow-1">
                            <i class="fa-solid fa-tag me-1" style="color: var(--dorado-mezquita);"></i>
                            <?php echo htmlspecialchars($pista['deporte_asociado']); ?>
                        </p>
                        <a href="reservar.php?id=<?php echo $pista['id_pista']; ?>" class="btn text-white mt-3" style="background-color: var(--rojo-mezquita);">
                            <i class="fa-solid fa-calendar-plus me-2"></i>Reservar
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