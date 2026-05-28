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
$total_productos = $conexion->query("SELECT COUNT(*) FROM productos")->fetchColumn();

$rutinas = $conexion->query("SELECT * FROM rutinas WHERE tipo = 'oficial' ORDER BY fecha_creacion DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$productos = $conexion->query("SELECT * FROM productos ORDER BY fecha_creacion DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

include("../includes/header.php");
?>

<main style="flex: 1;">
<div class="container py-5">

    <!-- Cabecera -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-gauge me-2"></i>Panel de Administración
            </h2>
            <p class="text-muted mb-0">Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></p>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center h-100">
                <div class="icon-box mx-auto mb-3"><i class="fa-solid fa-users"></i></div>
                <h3 class="fw-bold mb-0"><?php echo $total_usuarios; ?></h3>
                <p class="text-muted small mb-0">Usuarios registrados</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center h-100">
                <div class="icon-box mx-auto mb-3"><i class="fa-solid fa-dumbbell"></i></div>
                <h3 class="fw-bold mb-0"><?php echo $total_rutinas; ?></h3>
                <p class="text-muted small mb-0">Rutinas oficiales</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center h-100">
                <div class="icon-box mx-auto mb-3"><i class="fa-solid fa-trophy"></i></div>
                <h3 class="fw-bold mb-0"><?php echo $total_marcas; ?></h3>
                <p class="text-muted small mb-0">Marcas registradas</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm p-4 text-center h-100">
                <div class="icon-box mx-auto mb-3"><i class="fa-solid fa-box"></i></div>
                <h3 class="fw-bold mb-0"><?php echo $total_productos; ?></h3>
                <p class="text-muted small mb-0">Productos en tienda</p>
            </div>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
        <i class="fa-solid fa-bolt me-2"></i>Accesos rápidos
    </h4>
    <div class="row g-3 mb-5">
        <div class="col-md-4 col-sm-6">
            <a href="/proyectoCGS/admin/rutinas/admincrea.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm p-3 text-center h-100" style="border-left: 4px solid var(--rojo-mezquita) !important;">
                    <i class="fa-solid fa-plus fa-lg mb-2" style="color: var(--rojo-mezquita);"></i>
                    <p class="fw-bold mb-0 small">Nueva rutina oficial</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="/proyectoCGS/admin/pistas.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm p-3 text-center h-100" style="border-left: 4px solid var(--rojo-mezquita) !important;">
                    <i class="fa-solid fa-map-location-dot fa-lg mb-2" style="color: var(--rojo-mezquita);"></i>
                    <p class="fw-bold mb-0 small">Gestionar pistas</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="/proyectoCGS/admin/productos.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm p-3 text-center h-100" style="border-left: 4px solid var(--dorado-mezquita) !important;">
                    <i class="fa-solid fa-box fa-lg mb-2" style="color: var(--dorado-mezquita);"></i>
                    <p class="fw-bold mb-0 small">Gestionar productos</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="/proyectoCGS/admin/usuarios.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm p-3 text-center h-100" style="border-left: 4px solid var(--rojo-mezquita) !important;">
                    <i class="fa-solid fa-users fa-lg mb-2" style="color: var(--rojo-mezquita);"></i>
                    <p class="fw-bold mb-0 small">Gestionar usuarios</p>
                </div>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="/proyectoCGS/index.php" class="text-decoration-none">
                <div class="card border-0 shadow-sm p-3 text-center h-100" style="border-left: 4px solid var(--dorado-mezquita) !important;">
                    <i class="fa-solid fa-globe fa-lg mb-2" style="color: var(--dorado-mezquita);"></i>
                    <p class="fw-bold mb-0 small">Ver página web</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-5">

        <!-- Rutinas oficiales -->
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-dumbbell me-2"></i>Últimas rutinas
                </h5>
                <a href="/proyectoCGS/admin/rutinas/admincrea.php" class="btn btn-sm text-white" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-plus me-1"></i>Nueva
                </a>
            </div>
            <?php if(empty($rutinas)): ?>
                <div class="alert alert-info">No hay rutinas oficiales todavía.</div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: var(--rojo-mezquita); color: white;">
                                <tr>
                                    <th>Título</th>
                                    <th>Deporte</th>
                                    <th>Dificultad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($rutinas as $rutina): ?>
                                <tr>
                                    <td class="fw-semibold small"><?php echo htmlspecialchars($rutina['titulo']); ?></td>
                                    <td class="small"><?php echo $rutina['deporte']; ?></td>
                                    <td>
                                        <span class="badge" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                                            <?php echo $rutina['dificultad']; ?>
                                        </span>
                                    </td>
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
                </div>
            <?php endif; ?>
        </div>

        <!-- Productos -->
        <div class="col-lg-6">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" style="color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-box me-2"></i>Últimos productos
                </h5>
                <a href="/proyectoCGS/admin/productos.php" class="btn btn-sm text-white" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-plus me-1"></i>Gestionar
                </a>
            </div>
            <?php if(empty($productos)): ?>
                <div class="alert alert-info">No hay productos todavía.</div>
            <?php else: ?>
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: var(--rojo-mezquita); color: white;">
                                <tr>
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
                                    <td class="fw-semibold small"><?php echo htmlspecialchars($p['nombre']); ?></td>
                                    <td class="small"><?php echo $p['deporte']; ?></td>
                                    <td class="small"><?php echo number_format($p['precio'], 2); ?> €</td>
                                    <td>
                                        <span class="badge <?php echo $p['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $p['stock']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/proyectoCGS/admin/productos.php?editar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="/proyectoCGS/admin/productos.php?eliminar=<?php echo $p['id_producto']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete(this)">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
</main>
<?php include("../includes/footer.php"); ?>