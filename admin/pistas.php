<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");
include("../includes/header.php");

if(!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin'){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

$deportes = ["Atletismo", "Fútbol", "Baloncesto", "Pádel", "Ciclismo", "Natación", "Tenis"];

// ELIMINAR
if(isset($_GET['eliminar'])){
    $conexion->prepare("DELETE FROM pistas WHERE id_pista = ?")->execute([$_GET['eliminar']]);
    header("Location: pistas.php");
    exit();
}

// CREAR
if(isset($_POST['crear'])){
    $nombre = trim($_POST['nombre_pista']);
    $deporte = $_POST['deporte_asociado'];
    $direccion = trim($_POST['direccion']);
    $imagen = '';

    if(!empty($_FILES['imagen']['name'])){
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../multimedia/pistas/" . $imagen);
    }

    $conexion->prepare("INSERT INTO pistas (nombre_pista, deporte_asociado, direccion, imagen) VALUES (?, ?, ?, ?)")
             ->execute([$nombre, $deporte, $direccion, $imagen]);
    header("Location: pistas.php");
    exit();
}

// EDITAR
if(isset($_POST['editar'])){
    $id = $_POST['id_pista'];
    $nombre = trim($_POST['nombre_pista']);
    $deporte = $_POST['deporte_asociado'];
    $direccion = trim($_POST['direccion']);

    if(!empty($_FILES['imagen']['name'])){
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagen = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], "../multimedia/pistas/" . $imagen);
        $conexion->prepare("UPDATE pistas SET nombre_pista=?, deporte_asociado=?, direccion=?, imagen=? WHERE id_pista=?")
                 ->execute([$nombre, $deporte, $direccion, $imagen, $id]);
    } else {
        $conexion->prepare("UPDATE pistas SET nombre_pista=?, deporte_asociado=?, direccion=? WHERE id_pista=?")
                 ->execute([$nombre, $deporte, $direccion, $id]);
    }
    header("Location: pistas.php");
    exit();
}

$editando = null;
if(isset($_GET['editar'])){
    $stmt = $conexion->prepare("SELECT * FROM pistas WHERE id_pista = ?");
    $stmt->execute([$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

$pistas = $conexion->query("SELECT * FROM pistas ORDER BY deporte_asociado, nombre_pista")->fetchAll(PDO::FETCH_ASSOC);
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-map-location-dot me-2"></i>Gestión de Pistas
        </h2>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Volver al panel
        </a>
    </div>

    <!-- Formulario crear / editar -->
    <div class="card border-0 shadow-sm p-4 mb-5">
        <h5 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
            <?php echo $editando ? 'Editar pista' : 'Añadir nueva pista'; ?>
        </h5>
        <form method="POST" enctype="multipart/form-data">
            <?php if($editando): ?>
                <input type="hidden" name="id_pista" value="<?php echo $editando['id_pista']; ?>">
            <?php endif; ?>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Nombre de la pista</label>
                    <input type="text" name="nombre_pista" class="form-control" value="<?php echo htmlspecialchars($editando['nombre_pista'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Deporte</label>
                    <select name="deporte_asociado" class="form-select" required>
                        <option value="" disabled <?php echo !$editando ? 'selected' : ''; ?>>Selecciona</option>
                        <?php foreach($deportes as $d): ?>
                            <option value="<?php echo $d; ?>" <?php echo ($editando['deporte_asociado'] ?? '') === $d ? 'selected' : ''; ?>>
                                <?php echo $d; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Imagen</label>
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                    <?php if(!empty($editando['imagen'])): ?>
                        <small class="text-muted">Actual: <?php echo $editando['imagen']; ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($editando['direccion'] ?? ''); ?>" required>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" name="<?php echo $editando ? 'editar' : 'crear'; ?>" class="btn text-white" style="background-color: var(--rojo-mezquita);">
                        <i class="fa-solid fa-floppy-disk me-2"></i><?php echo $editando ? 'Guardar cambios' : 'Añadir pista'; ?>
                    </button>
                    <?php if($editando): ?>
                        <a href="pistas.php" class="btn btn-outline-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Listado -->
    <?php if(empty($pistas)): ?>
        <div class="alert alert-info">No hay pistas todavía.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead style="background-color: var(--rojo-mezquita); color: white;">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Deporte</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pistas as $p): ?>
                    <tr>
                        <td>
                            <?php if($p['imagen']): ?>
                                <img src="/proyectoCGS/multimedia/pistas/<?php echo htmlspecialchars($p['imagen']); ?>"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <i class="fa-solid fa-image" style="color: var(--dorado-mezquita);"></i>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?php echo htmlspecialchars($p['nombre_pista']); ?></td>
                        <td><?php echo $p['deporte_asociado']; ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($p['direccion']); ?></td>
                        <td>
                            <a href="pistas.php?editar=<?php echo $p['id_pista']; ?>" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="pistas.php?eliminar=<?php echo $p['id_pista']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(this)">
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