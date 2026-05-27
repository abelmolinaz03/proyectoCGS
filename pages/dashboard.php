<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
$miDeporte = $_SESSION['deporte_usuario'] ?? 'atletismo';
$miDeporteKey = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], mb_strtolower($miDeporte));

$deportes = [
    'atletismo' => [
        'nombre' => 'Atletismo',
        'frase' => 'Supera tus marcas en las pistas de El Fontanar.',
        'img' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1400&q=80'
    ],
    'natacion' => [
        'nombre' => 'Natación',
        'frase' => 'Entrena duro en las piscinas.',
        'img' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?w=1400&q=80'
    ],
    'ciclismo' => [
        'nombre' => 'Ciclismo',
        'frase' => 'Conquista las rutas.',
        'img' => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1400&q=80'
    ],
    'futbol' => [
        'nombre' => 'Fútbol',
        'frase' => 'Domina el balón en los campos de fútbol de Córdoba.',
        'img' => 'https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?w=1400&q=80'
    ],
    'baloncesto' => [
        'nombre' => 'Baloncesto',
        'frase' => 'Encesta en las canchas de baloncesto de Córdoba.',
        'img' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1400&q=80'
    ],
    'tenis' => [
        'nombre' => 'Tenis',
        'frase' => 'Mejora tu saque en las pistas de tenis de Córdoba.',
        'img' => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=1400&q=80'
    ],
    'padel' => [
        'nombre' => 'Pádel',
        'frase' => 'Juega al pádel en las mejores pistas de Córdoba.',
        'img' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=1400&q=80'
    ],
];

$info = $deportes[$miDeporteKey] ?? $deportes['atletismo'];

// Última marca
$stmt = $conexion->prepare("SELECT * FROM marcas_deportivas WHERE id_usuario = ? ORDER BY fecha_registro DESC LIMIT 1");
$stmt->execute([$id_usuario]);
$ultima_marca = $stmt->fetch(PDO::FETCH_ASSOC);

// Última rutina personal
$stmt_rutina = $conexion->prepare("SELECT * FROM rutinas WHERE id_usuario = ? AND tipo = 'personal' ORDER BY fecha_creacion DESC LIMIT 1");
$stmt_rutina->execute([$id_usuario]);
$ultima_rutina = $stmt_rutina->fetch(PDO::FETCH_ASSOC);

// Última reserva
$stmt_reserva = $conexion->prepare("
    SELECT r.*, p.nombre_pista 
    FROM reservas r 
    JOIN pistas p ON r.id_pista = p.id_pista 
    WHERE r.id_usuario = ? AND r.fecha_reserva >= CURDATE() 
    ORDER BY r.fecha_reserva ASC, r.hora_inicio ASC 
    LIMIT 1
");
$stmt_reserva->execute([$id_usuario]);
$proxima_reserva = $stmt_reserva->fetch(PDO::FETCH_ASSOC);

// Total marcas
$stmt2 = $conexion->prepare("SELECT COUNT(*) FROM marcas_deportivas WHERE id_usuario = ?");
$stmt2->execute([$id_usuario]);
$total_marcas = $stmt2->fetchColumn();

// Total rutinas personales
$stmt3 = $conexion->prepare("SELECT COUNT(*) FROM rutinas WHERE id_usuario = ? AND tipo = 'personal'");
$stmt3->execute([$id_usuario]);
$total_rutinas = $stmt3->fetchColumn();

include("../includes/header.php");
?>

<main style="flex: 1;">

    <!-- Hero fullwidth -->
    <div class="hero-section" style="background-image: url('<?php echo $info['img']; ?>'); min-height: 400px; border-radius: 0; margin-bottom: 0;">
        <div class="hero-overlay"></div>
        <div class="hero-content text-center">
            <p class="mb-2 text-uppercase" style="color: var(--dorado-mezquita); letter-spacing: 3px; font-size: 0.9rem;">Bienvenido de vuelta</p>
            <h1 class="display-2 fw-bold mb-2"><?php echo $_SESSION['nombre']; ?></h1>
            <div style="width: 80px; height: 3px; background: var(--dorado-mezquita); margin: 0 auto 15px;"></div>
            <p class="lead mb-0"><?php echo $info['frase']; ?></p>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div style="background-color: var(--rojo-mezquita); border-bottom: 3px solid var(--dorado-mezquita);">
        <div class="container">
            <div class="row text-white text-center py-3">
                <div class="col-4 border-end border-secondary">
                    <div class="fw-bold fs-4" style="color: var(--dorado-mezquita);"><?php echo $total_marcas; ?></div>
                    <div class="small text-uppercase" style="letter-spacing: 1px;">Marcas</div>
                </div>
                <div class="col-4 border-end border-secondary">
                    <div class="fw-bold fs-4" style="color: var(--dorado-mezquita);"><?php echo $total_rutinas; ?></div>
                    <div class="small text-uppercase" style="letter-spacing: 1px;">Rutinas</div>
                </div>
                <div class="col-4">
                    <div class="fw-bold fs-4" style="color: var(--dorado-mezquita);"><?php echo strtoupper($miDeporte); ?></div>
                    <div class="small text-uppercase" style="letter-spacing: 1px;">Deporte</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">

        <!-- Última marca -->
        <?php if($ultima_marca): ?>
        <div class="alert border-0 shadow-sm mb-3 d-flex align-items-center gap-3 p-4" style="background-color: white; border-left: 5px solid var(--dorado-mezquita) !important;">
            <i class="fa-solid fa-trophy fa-2x" style="color: var(--dorado-mezquita);"></i>
            <div>
                <p class="mb-0 small text-muted text-uppercase" style="letter-spacing: 1px;">Última marca registrada</p>
                <p class="fw-bold mb-0 fs-5"><?php echo htmlspecialchars($ultima_marca['tiempo_o_puntuacion']); ?>
                    <span class="text-muted fw-normal fs-6">— <?php echo date('d/m/Y', strtotime($ultima_marca['fecha_registro'])); ?></span>
                </p>
            </div>
            <a href="/proyectoCGS/pages/marcas/index.php" class="btn btn-sm ms-auto text-white" style="background-color: var(--rojo-mezquita);">Ver todas</a>
        </div>
        <?php endif; ?>

        <!-- Última rutina -->
        <?php if($ultima_rutina): ?>
        <div class="alert border-0 shadow-sm mb-5 d-flex align-items-center gap-3 p-4" style="background-color: white; border-left: 5px solid var(--rojo-mezquita) !important;">
            <i class="fa-solid fa-dumbbell fa-2x" style="color: var(--rojo-mezquita);"></i>
            <div>
                <p class="mb-0 small text-muted text-uppercase" style="letter-spacing: 1px;">Última rutina creada</p>
                <p class="fw-bold mb-0 fs-5"><?php echo htmlspecialchars($ultima_rutina['titulo']); ?>
                    <span class="text-muted fw-normal fs-6">— <?php echo date('d/m/Y', strtotime($ultima_rutina['fecha_creacion'])); ?></span>
                </p>
            </div>
            <a href="/proyectoCGS/pages/rutinas/ver.php?id=<?php echo $ultima_rutina['id_rutina']; ?>" class="btn btn-sm ms-auto text-white" style="background-color: var(--rojo-mezquita);">Ver rutina</a>
        </div>
        <?php endif; ?>

        <!-- Próxima reserva -->
        <?php if($proxima_reserva): ?>
        <div class="alert border-0 shadow-sm mb-5 d-flex align-items-center gap-3 p-4" style="background-color: white; border-left: 5px solid var(--dorado-mezquita) !important;">
            <i class="fa-solid fa-calendar-check fa-2x" style="color: var(--dorado-mezquita);"></i>
            <div>
                <p class="mb-0 small text-muted text-uppercase" style="letter-spacing: 1px;">Próxima reserva</p>
                <p class="fw-bold mb-0 fs-5"><?php echo htmlspecialchars($proxima_reserva['nombre_pista']); ?>
                    <span class="text-muted fw-normal fs-6">— <?php echo date('d/m/Y', strtotime($proxima_reserva['fecha_reserva'])); ?> · <?php echo substr($proxima_reserva['hora_inicio'], 0, 5); ?></span>
                </p>
            </div>
            <a href="/proyectoCGS/pages/pistas/reservasuser.php" class="btn btn-sm ms-auto text-white" style="background-color: var(--dorado-mezquita); color: #1a1a1a !important;">Ver reservas</a>
        </div>
        <?php elseif($ultima_marca): ?>
            <?php // ya tiene mb-5 el bloque de marca si no hay reserva ?>
        <?php endif; ?>
        <!-- Tarjetas -->
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border-0 shadow overflow-hidden" style="border-radius: 15px;">
                    <div style="height: 120px; background: linear-gradient(135deg, var(--rojo-mezquita), #5a0000); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-stopwatch fa-3x text-white opacity-75"></i>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Mis Marcas</h5>
                        <p class="text-muted small mb-3">Registra y consulta tus récords en <?php echo $info['nombre']; ?>.</p>
                        <a href="/proyectoCGS/pages/marcas/index.php" class="btn text-white w-100" style="background-color: var(--rojo-mezquita);">
                            <i class="fa-solid fa-arrow-right me-2"></i>Ver Récords
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow overflow-hidden" style="border-radius: 15px;">
                    <div style="height: 120px; background: linear-gradient(135deg, var(--dorado-mezquita), #8b6000); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-dumbbell fa-3x text-white opacity-75"></i>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Entrenamiento</h5>
                        <p class="text-muted small mb-3">Rutinas oficiales y personales para tu disciplina.</p>
                        <a href="/proyectoCGS/pages/rutinas/index.php" class="btn text-white w-100" style="background-color: var(--dorado-mezquita); color: #1a1a1a !important;">
                            <i class="fa-solid fa-arrow-right me-2"></i>Ver Plan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow overflow-hidden" style="border-radius: 15px;">
                    <div style="height: 120px; background: linear-gradient(135deg, #1a3a5c, #0d2240); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-map-location-dot fa-3x text-white opacity-75"></i>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Instalaciones</h5>
                        <p class="text-muted small mb-3">Reserva pistas de <?php echo $info['nombre']; ?> en Córdoba.</p>
                        <a href="/proyectoCGS/pages/pistas/index.php" class="btn text-white w-100" style="background-color: #1a3a5c;">
                            <i class="fa-solid fa-arrow-right me-2"></i>Ver pistas
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow overflow-hidden" style="border-radius: 15px;">
                    <div style="height: 120px; background: linear-gradient(135deg, #2c211a, #1a1a1a); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-cart-shopping fa-3x text-white opacity-75"></i>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-1">Tienda</h5>
                        <p class="text-muted small mb-3">Productos personalizados de <?php echo $info['nombre']; ?>!!!</p>
                        <a href="/proyectoCGS/pages/tienda/index.php" class="btn text-white w-100" style="background-color: #2c211a;">
                            <i class="fa-solid fa-arrow-right me-2"></i>Ir a Comprar
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include("../includes/footer.php"); ?>