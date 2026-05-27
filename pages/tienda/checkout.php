<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];

// Obtener items del carrito
$stmt = $conexion->prepare("
    SELECT c.*, p.nombre, p.precio 
    FROM carrito c 
    JOIN productos p ON c.id_producto = p.id_producto 
    WHERE c.id_usuario = ?
");
$stmt->execute([$id_usuario]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(empty($items)){
    header("Location: index.php");
    exit();
}

$total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $items));

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vaciar carrito tras confirmar pedido
    $conexion->prepare("DELETE FROM carrito WHERE id_usuario = ?")->execute([$id_usuario]);

    header("Location: index.php?pedido=ok");
    exit();
}
?>

<main style="flex: 1;">
<div class="container py-5" style="max-width: 700px;">

    <?php if(isset($_GET['pedido']) && $_GET['pedido'] === 'ok'): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-circle-check fa-4x mb-3" style="color: var(--dorado-mezquita);"></i>
            <h2 class="fw-bold mb-2" style="color: var(--rojo-mezquita);">¡Pedido confirmado!</h2>
            <p class="text-muted mb-4">Gracias por tu compra. Recibirás tu pedido en breve.</p>
            <a href="index.php" class="btn text-white" style="background-color: var(--rojo-mezquita);">Volver a la tienda</a>
        </div>
    <?php else: ?>

        <div class="card border-0 shadow-sm p-4 mb-4">
            <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-receipt me-2"></i>Resumen del pedido
            </h4>
            <?php foreach($items as $item): ?>
            <div class="d-flex justify-content-between mb-2">
                <span><?php echo htmlspecialchars($item['nombre']); ?> x<?php echo $item['cantidad']; ?></span>
                <span class="fw-bold"><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?> €</span>
            </div>
            <?php endforeach; ?>
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total</span>
                <span style="color: var(--rojo-mezquita);"><?php echo number_format($total, 2); ?> €</span>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-truck me-2"></i>Datos de envío
            </h4>
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre completo</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="tel" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" class="form-control" value="Córdoba" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Código postal</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="col-12 mt-2">
                        <button type="submit" class="btn text-white w-100 py-3" style="background-color: var(--rojo-mezquita);">
                            <i class="fa-solid fa-check me-2"></i>Confirmar pedido
                        </button>
                    </div>
                </div>
            </form>
        </div>

    <?php endif; ?>

</div>
</main>

<?php include("../../includes/footer.php"); ?>