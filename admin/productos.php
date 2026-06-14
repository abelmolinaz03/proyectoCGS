<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");
include("../includes/validaciones.php");

if(!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin'){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

$deportes = ["Atletismo", "Fútbol", "Baloncesto", "Pádel", "Ciclismo", "Natación", "Tenis"];
$errores_producto = [];

// ELIMINAR
if(isset($_GET['eliminar'])){
    $id_eliminar = (int) $_GET['eliminar']; // sanear como entero
    $conexion->prepare("DELETE FROM productos WHERE id_producto = ?")->execute([$id_eliminar]);
    header("Location: productos.php");
    exit();
}

// CREAR
if(isset($_POST['crear'])){
    $resultado = validar_producto($_POST, $_FILES);
    $errores_producto = $resultado['errores'];
    $datos = $resultado['datos'];

    if(empty($errores_producto)){
        $imagen = '';
        if(!empty($_FILES['imagen']['name'])){
            $ext    = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $imagen = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../multimedia/productos/" . $imagen);
        }

        $conexion->prepare(
            "INSERT INTO productos (nombre, descripcion, precio, deporte, stock, imagen) VALUES (?, ?, ?, ?, ?, ?)"
        )->execute([
            $datos['nombre'],
            $datos['descripcion'],
            $datos['precio'],
            $datos['deporte'],
            $datos['stock'],
            $imagen,
        ]);
        header("Location: productos.php");
        exit();
    }
}

// EDITAR
if(isset($_POST['editar'])){
    $id = (int) $_POST['id_producto']; // sanear como entero
    $resultado = validar_producto($_POST, $_FILES);
    $errores_producto = $resultado['errores'];
    $datos = $resultado['datos'];

    if(empty($errores_producto)){
        if(!empty($_FILES['imagen']['name'])){
            $ext    = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $imagen = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagen']['tmp_name'], "../multimedia/productos/" . $imagen);
            $conexion->prepare(
                "UPDATE productos SET nombre=?, descripcion=?, precio=?, deporte=?, stock=?, imagen=? WHERE id_producto=?"
            )->execute([
                $datos['nombre'], $datos['descripcion'], $datos['precio'],
                $datos['deporte'], $datos['stock'], $imagen, $id,
            ]);
        } else {
            $conexion->prepare(
                "UPDATE productos SET nombre=?, descripcion=?, precio=?, deporte=?, stock=? WHERE id_producto=?"
            )->execute([
                $datos['nombre'], $datos['descripcion'], $datos['precio'],
                $datos['deporte'], $datos['stock'], $id,
            ]);
        }
        header("Location: productos.php");
        exit();
    }
}

include("../includes/header.php");

// Obtener producto a editar
$editando = null;
if(isset($_GET['editar'])){
    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
    $stmt->execute([(int)$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Listar productos
$productos = $conexion->query("SELECT * FROM productos ORDER BY deporte, fecha_creacion DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-box me-2"></i>Gestión de Productos
        </h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Volver al panel
        </a>
    </div>

    <!-- Formulario crear / editar -->
    <div class="card border-0 shadow-sm p-4 mb-5">
        <h5 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
            <?php echo $editando ? 'Editar producto' : 'Añadir nuevo producto'; ?>
        </h5>
        <?php if(!empty($errores_producto)): ?>
            <div class="alert alert-danger py-2 small">
                <ul class="mb-0">
                    <?php foreach($errores_producto as $msg): ?>
                        <li><?php echo htmlspecialchars($msg); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <?php if($editando): ?>
                <input type="hidden" name="id_producto" value="<?php echo $editando['id_producto']; ?>">
            <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($editando['nombre'] ?? ''); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Precio (€)</label>
                    <input type="number" name="precio" class="form-control" step="0.01" min="0" value="<?php echo $editando['precio'] ?? ''; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Stock</label>
                    <input type="number" name="stock" class="form-control" min="0" value="<?php echo $editando['stock'] ?? ''; ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Deporte</label>
                    <select name="deporte" class="form-select" required>
                        <option value="" disabled <?php echo !$editando ? 'selected' : ''; ?>>Selecciona</option>
                        <?php foreach($deportes as $d): ?>
                            <option value="<?php echo $d; ?>" <?php echo ($editando['deporte'] ?? '') === $d ? 'selected' : ''; ?>>
                                <?php echo $d; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <?php if(!empty($editando['imagen'])): ?>
                        <small class="text-muted">Imagen actual: <?php echo $editando['imagen']; ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2"><?php echo htmlspecialchars($editando['descripcion'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" name="<?php echo $editando ? 'editar' : 'crear'; ?>" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                        <i class="fa-solid fa-floppy-disk me-2"></i><?php echo $editando ? 'Guardar cambios' : 'Añadir producto'; ?>
                    </button>
                    <?php if($editando): ?>
                        <a href="productos.php" class="btn btn-outline-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Listado de productos -->
    <?php if(empty($productos)): ?>
        <div class="alert alert-info">No hay productos todavía.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--rojo-mezquita); color: white;">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Deporte</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $p): ?>
                    <tr>
                        <td>
                            <?php if($p['imagen']): ?>
                                <img src="/proyectoCGS/multimedia/productos/<?php echo htmlspecialchars($p['imagen']); ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <i class="fa-solid fa-image" style="color: var(--dorado-mezquita);"></i>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo $p['deporte']; ?></td>
                        <td><?php echo number_format($p['precio'], 2); ?> €</td>
                        <td>
                            <span class="badge <?php echo $p['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $p['stock']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="productos.php?editar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="productos.php?eliminar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(this)">
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