<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");

if(!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin'){
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

// ELIMINAR USUARIO
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];
    // No elimina al admin
    if($id != $_SESSION['usuario_id']){
        $conexion->prepare("DELETE FROM usuarios WHERE id_usuario = ?")->execute([$id]);
    }
    header("Location: /proyectoCGS/admin/usuarios.php");
    exit();
}

// CAMBIAR ROL
if(isset($_GET['rol']) && isset($_GET['id'])){
    $id = $_GET['id'];
    $rol = $_GET['rol'] === 'admin' ? 'admin' : 'usuario';
    if($id != $_SESSION['usuario_id']){
        $conexion->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?")->execute([$rol, $id]);
    }
    header("Location: /proyectoCGS/admin/usuarios.php");
    exit();
}

include("../includes/header.php");

// FILTROS
$filtro_deporte = $_GET['deporte'] ?? '';
$filtro_rol = $_GET['filtro_rol'] ?? '';
$busqueda = $_GET['busqueda'] ?? '';

$sql = "SELECT * FROM usuarios WHERE 1=1";
$params = [];

if($filtro_deporte){
    $sql .= " AND deporte_principal = ?";
    $params[] = $filtro_deporte;
}
if($filtro_rol){
    $sql .= " AND rol = ?";
    $params[] = $filtro_rol;
}
if($busqueda){
    $sql .= " AND (nombre LIKE ? OR apellidos LIKE ? OR email LIKE ?)";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
    $params[] = "%$busqueda%";
}

$sql .= " ORDER BY id_usuario ASC";
$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_usuarios = $conexion->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_admins = $conexion->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
$total_normales = $conexion->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'usuario'")->fetchColumn();

$deportes = ["Atletismo", "Fútbol", "Baloncesto", "Pádel", "Ciclismo", "Natación", "Tenis"];
?>

<main style="flex: 1;">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color: var(--rojo-mezquita);">
            <i class="fa-solid fa-users me-2"></i>Gestión de Usuarios
        </h2>
        <a href="/proyectoCGS/admin/index.php" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i>Volver al panel
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="fw-bold fs-3" style="color: var(--rojo-mezquita);"><?php echo $total_usuarios; ?></div>
                <p class="text-muted small mb-0">Total usuarios</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="fw-bold fs-3" style="color: var(--dorado-mezquita);"><?php echo $total_admins; ?></div>
                <p class="text-muted small mb-0">Administradores</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center">
                <div class="fw-bold fs-3" style="color: var(--rojo-mezquita);"><?php echo $total_normales; ?></div>
                <p class="text-muted small mb-0">Usuarios normales</p>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm p-4 mb-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Buscar</label>
                <input type="text" name="busqueda" class="form-control form-control-sm" 
                       placeholder="Nombre, apellidos o email..."
                       value="<?php echo htmlspecialchars($busqueda); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Filtrar por deporte</label>
                <select name="deporte" class="form-select form-select-sm">
                    <option value="">Todos los deportes</option>
                    <?php foreach($deportes as $d): ?>
                        <option value="<?php echo $d; ?>" <?php echo $filtro_deporte === $d ? 'selected' : ''; ?>>
                            <?php echo $d; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Filtrar por rol</label>
                <select name="filtro_rol" class="form-select form-select-sm">
                    <option value="">Todos los roles</option>
                    <option value="usuario" <?php echo $filtro_rol === 'usuario' ? 'selected' : ''; ?>>Usuario</option>
                    <option value="admin" <?php echo $filtro_rol === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm text-white w-100" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-filter me-1"></i>Filtrar
                </button>
                <a href="/proyectoCGS/admin/usuarios.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de usuarios -->
    <?php if(empty($usuarios)): ?>
        <div class="alert alert-info">No se encontraron usuarios con los filtros seleccionados.</div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: var(--rojo-mezquita); color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Deporte</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $u): ?>
                        <tr <?php echo $u['id_usuario'] == $_SESSION['usuario_id'] ? 'class="table-warning"' : ''; ?>>
                            <td class="small text-muted">#<?php echo $u['id_usuario']; ?></td>
                            <td>
                                <p class="fw-semibold mb-0 small"><?php echo htmlspecialchars($u['nombre'] . ' ' . $u['apellidos']); ?></p>
                            </td>
                            <td class="small text-muted"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td>
                                <span class="badge" style="background-color: var(--dorado-mezquita); color: #1a1a1a;">
                                    <?php echo htmlspecialchars($u['deporte_principal']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($u['rol'] === 'admin'): ?>
                                    <span class="badge" style="background-color: var(--rojo-mezquita);">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Usuario</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($u['id_usuario'] != $_SESSION['usuario_id']): ?>
                                    <!-- Cambiar rol -->
                                    <?php if($u['rol'] === 'usuario'): ?>
                                        <a href="/proyectoCGS/admin/usuarios.php?id=<?php echo $u['id_usuario']; ?>&rol=admin"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Hacer admin"
                                           onclick="return confirm('¿Hacer admin a este usuario?')">
                                            <i class="fa-solid fa-user-shield"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="/proyectoCGS/admin/usuarios.php?id=<?php echo $u['id_usuario']; ?>&rol=usuario"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Quitar admin"
                                           onclick="return confirm('¿Quitar permisos de admin?')">
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                    <?php endif; ?>
                                    <!-- Eliminar -->
                                    <a href="/proyectoCGS/admin/usuarios.php?eliminar=<?php echo $u['id_usuario']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirmDelete(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="small text-muted">Tu cuenta</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="text-muted small mt-2">Mostrando <?php echo count($usuarios); ?> de <?php echo $total_usuarios; ?> usuarios.</p>
    <?php endif; ?>

</div>
</main>

<?php include("../includes/footer.php"); ?>