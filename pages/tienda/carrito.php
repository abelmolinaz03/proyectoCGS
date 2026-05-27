<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/header.php");

$id_usuario = $_SESSION['usuario_id'];

// Añadir producto al carrito
if(isset($_GET['añadir'])){
    $id_producto = $_GET['añadir'];

    // Comprobar si ya está en el carrito
    $stmt = $conexion->prepare("SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?");
    $stmt->execute([$id_usuario, $id_producto]);
    $existe = $stmt->fetch();

    if($existe){
        $conexion->prepare("UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = ? AND id_producto = ?")
                 ->execute([$id_usuario, $id_producto]);
    } else {
        $conexion->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)")
                 ->execute([$id_usuario, $id_producto]);
    }

    header("Location: carrito.php");
    exit();
}

// Eliminar producto del carrito
if(isset($_GET['eliminar'])){
    $conexion->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?")
             ->execute([$id_usuario, $_GET['eliminar']]);
    header("Location: carrito.php");
    exit();
}

// Actualizar cantidad
if(isset($_POST['actualizar'])){
    $id_producto = $_POST['id_producto'];
    $cantidad = max(1, (int)$_POST['cantidad']);
    $conexion->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?")
             ->execute([$cantidad, $id_usuario, $id_producto]);
    header("Location: carrito.php");
    exit();
}

// Obtener items del carrito
$stmt = $conexion->prepare("
    SELECT c.*, p.nombre, p.precio, p.imagen, p.stock 
    FROM carrito c 
    JOIN productos p ON c.id_producto = p.id_producto 
    WHERE c.id_usuario = ?
");
$stmt->execute([$id_usuario]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $items));
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-basket-shopping me-2"></i>Mi Carrito
        </h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Seguir comprando
        </a>
    </div>

    <?php if(empty($items)): ?>
        <div class="alert alert-info">Tu carrito está vacío.</div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <?php foreach($items as $item): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body d-flex align-items-center gap-3 p-3">
                        <?php if($item['imagen']): ?>
                            <img src="/proyectoCGS/multimedia/productos/<?php echo htmlspecialchars($item['imagen']); ?>" 
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;" alt="">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: var(--crema-mezquita); border-radius: 8px;">
                                <i class="fa-solid fa-image" style="color: var(--dorado-mezquita);"></i>
                            </div>
                        <?php endif; ?>

                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($item['nombre']); ?></h6>
                            <span style="color: var(--rojo-mezquita); font-weight: bold;"><?php echo number_format($item['precio'], 2); ?> €</span>
                        </div>

                        <form method="POST" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="id_producto" value="<?php echo $item['id_producto']; ?>">
                            <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" 
                                   min="1" max="<?php echo $item['stock']; ?>" 
                                   class="form-control form-control-sm" style="width: 70px;">
                            <button type="submit" name="actualizar" class="btn btn-sm btn-outline-secondary">
                                <i class="fa-solid fa-rotate"></i>
                            </button>
                        </form>

                        <span class="fw-bold"><?php echo number_format($item['precio'] * $item['cantidad'], 2); ?> €</span>

                        <a href="carrito.php?eliminar=<?php echo $item['id_producto']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirmDelete(this)">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3" style="color: var(--rojo-mezquita);">Resumen del pedido</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span><?php echo number_format($total, 2); ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Envío</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total</span>
                        <span style="color: var(--rojo-mezquita);"><?php echo number_format($total, 2); ?> €</span>
                    </div>
                    <a href="checkout.php" class="btn text-white w-100" style="background-color: var(--rojo-mezquita);">
                        <i class="fa-solid fa-credit-card me-2"></i>Finalizar pedido
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
</main>

<?php include("../../includes/footer.php"); ?>