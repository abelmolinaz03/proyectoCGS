<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$deporte = $_SESSION['deporte_usuario'];

$stmt = $conexion->prepare("SELECT * FROM productos WHERE deporte = ? AND stock > 0 ORDER BY fecha_creacion DESC");
$stmt->execute([$deporte]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar items en carrito
$id_usuario = $_SESSION['usuario_id'];
$stmt2 = $conexion->prepare("SELECT SUM(cantidad) FROM carrito WHERE id_usuario = ?");
$stmt2->execute([$id_usuario]);
$total_carrito = $stmt2->fetchColumn() ?? 0;
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-cart-shopping me-2"></i>Tienda — <?php echo $deporte; ?>
        </h2>
        <a href="carrito.php" class="btn text-white position-relative" style="background-color: var(--rojo-mezquita);">
            <i class="fa-solid fa-basket-shopping me-1"></i>Mi carrito
            <?php if($total_carrito > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                    <?php echo $total_carrito; ?>
                </span>
            <?php endif; ?>
        </a>
    </div>

    <?php if(empty($productos)): ?>
        <div class="alert alert-info">No hay productos disponibles para <?php echo $deporte; ?> de momento.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($productos as $producto): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom h-100 border-0 shadow-sm">
                    <?php if($producto['imagen']): ?>
                        <img src="/proyectoCGS/multimedia/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center" style="height: 200px; background-color: var(--crema-mezquita);">
                            <i class="fa-solid fa-image fa-3x" style="color: var(--dorado-mezquita);"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column p-4">
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                        <p class="small text-muted flex-grow-1"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold fs-5" style="color: var(--rojo-mezquita);">
                                <?php echo number_format($producto['precio'], 2); ?> €
                            </span>
                            <span class="small text-muted">Stock: <?php echo $producto['stock']; ?></span>
                        </div>
                        <a href="carrito.php?añadir=<?php echo $producto['id_producto']; ?>" class="btn text-white mt-3" style="background-color: var(--rojo-mezquita);">
                            <i class="fa-solid fa-cart-plus me-2"></i>Añadir al carrito
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