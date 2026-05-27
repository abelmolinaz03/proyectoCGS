<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<?php
// Si el usuario está logueado pero la sesión no tiene el rol (sesiones antiguas), lo cargamos de la BD
if (isset($_SESSION['usuario_id']) && !isset($_SESSION['rol'])) {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/proyectoCGS/includes/db.php');
    $stmt = $conexion->prepare("SELECT rol FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['rol'] = $fila['rol'] ?? 'usuario';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportHub Córdoba</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">    
    <link rel="stylesheet" href="/proyectoCGS/css/estilos.css">
    <link rel="icon" type="image/png" href="/proyectoCGS/multimedia/logoblanco.png">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color: var(--rojo-mezquita); border-bottom: 3px solid var(--dorado-mezquita);">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/proyectoCGS/index.php">
                <img src="/proyectoCGS/multimedia/logoblanco.png" alt="SportHub Córdoba" style="height: 48px; width: auto;">
                <span style="border-left: 2px solid var(--dorado-mezquita); padding-left: 10px;">
                    SPORTHUB <span style="color: var(--dorado-mezquita);">CÓRDOBA</span>
                </span>
            </a>            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/proyectoCGS/pages/marcas/index.php">Marcas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/proyectoCGS/pages/rutinas/index.php">Rutinas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/proyectoCGS/pages/pistas/index.php">Reservas</a></li>
                    <li class="nav-item"><a class="nav-link" href="/proyectoCGS/pages/tienda/index.php">Tienda</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="/proyectoCGS/pages/contacto.php">Contacto</a></li>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-icon-circle">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenu">
                            <?php if(!isset($_SESSION['usuario_id'])): ?>
                                <li><a class="dropdown-item py-2" href="/proyectoCGS/pages/login.php">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i> Iniciar Sesión</a>
                                </li>
                                <li><a class="dropdown-item py-2" href="/proyectoCGS/pages/registro.php">
                                    <i class="fa-solid fa-user-plus me-2"></i> Crear Cuenta</a>
                                </li>
                            <?php else: ?>
                                <li class="px-3 py-2 small text-muted border-bottom">
                                    Hola, <strong><?php echo $_SESSION['nombre']; ?></strong>
                                </li>
                                <li><a class="dropdown-item py-2" href="/proyectoCGS/pages/dashboard.php">
                                    <i class="fa-solid fa-gauge me-2"></i> Mi Dashboard</a>
                                </li>
                                <?php if(($_SESSION['rol'] ?? '') === 'admin'): ?>
                                <li><a class="dropdown-item py-2" href="/proyectoCGS/admin/index.php">
                                    <i class="fa-solid fa-screwdriver-wrench me-2"></i> Panel de Admin</a>
                                </li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="/proyectoCGS/pages/logout.php">
                                    <i class="fa-solid fa-power-off me-2"></i> Cerrar Sesión</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>