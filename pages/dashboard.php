<?php 
session_start();
include("../includes/db.php");

// 1. Verificamos que el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /proyectoCGS/pages/login.php");
    exit();
}

// 2. Obtenemos el deporte del usuario desde la base de datos (o de la sesión)
// Usamos lo que guardamos en el login para saber qué deporte mostrar
$miDeporte = $_SESSION['deporte_usuario'] ?? 'atletismo'; // Si no se encuentra, por defecto 'Atletismo'

// Limpiamos el nombre para que coincida con las claves del array (ej: 'Natación' -> 'natacion')
$miDeporteKey = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], mb_strtolower($miDeporte));

$deportes = [
    'atletismo' => [
        'nombre' => 'Atletismo',
        'frase' => 'Supera tus marcas en las pistas de El Fontanar.',
        'img' => '/proyectoCGS/multimedia/atletismo.jpg'
    ],
    'natacion' => [
        'nombre' => 'Natación',
        'frase' => 'Entrena duro en las piscinas.',
        'img' => '/proyectoCGS/multimedia/natacion.jpg'
    ],
    'ciclismo' => [
        'nombre' => 'Ciclismo',
        'frase' => 'Conquista las rutas.',
        'img' => '/proyectoCGS/multimedia/ciclismo.jpg'
    ],
    'futbol' => [
        'nombre' => 'Fútbol',
        'frase' => 'Domina el balón en los campos de fútbol de Córdoba.',
        'img' => '/proyectoCGS/multimedia/futbol.jpg'
    ],
    'baloncesto' => [
        'nombre' => 'Baloncesto',
        'frase' => 'Encesta en las canchas de baloncesto de Córdoba.',
        'img' => '/proyectoCGS/multimedia/baloncesto.jpg'
    ],
    'tenis' => [
        'nombre' => 'Tenis',
        'frase' => 'Mejora tu saque en las pistas de tenis de Córdoba.',
        'img' => '/proyectoCGS/multimedia/tenis.jpg'
    ],
    'padel' => [
        'nombre' => 'Pádel',
        'frase' => 'Juega al pádel en las mejores pistas de Córdoba.',
        'img' => '/proyectoCGS/multimedia/padel.jpg'
    ],

];

$info = $deportes[$miDeporteKey] ?? $deportes['atletismo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - <?php echo $info['nombre']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/proyectoCGS/css/estilos.css">
</head>
<body>

<?php include("../includes/header.php"); ?>

<main class="container my-5">
    <section class="hero-section text-center" style="background-image: url('<?php echo $info['img']; ?>');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="display-3 fw-bold mb-3"><?php echo $info['nombre']; ?></h1>
            <p class="lead mb-0"><?php echo $info['frase']; ?></p>
        </div>
    </section>

    <div class="row g-4 justify-content-center text-center">
        <div class="col-md-4">
            <div class="card card-custom h-100 p-4 shadow-sm border-0">
                <div class="icon-box"><i class="fa-solid fa-stopwatch"></i></div>
                <h3 class="h5 fw-bold">Mis Marcas</h3>
                <p class="text-muted small">Registra tus récords en <?php echo $info['nombre']; ?>.</p>
                <a href="/proyectoCGS/pages/marcas/index.php" class="btn btn-success mt-auto">Ver Récords</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom h-100 p-4 shadow-sm border-0">
                <div class="icon-box"><i class="fa-solid fa-dumbbell"></i></div>
                <h3 class="h5 fw-bold">Entrenamiento</h3>
                <p class="text-muted small">Planes específicos para tu disciplina.</p>
                <a href="/proyectoCGS/pages/rutinas/index.php" class="btn btn-success mt-auto">Ver Plan</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom h-100 p-4 shadow-sm border-0">
                <div class="icon-box"><i class="fa-solid fa-cart-shopping"></i></div>
                <h3 class="h5 fw-bold">Tienda</h3>
                <p class="text-muted small">Equipación oficial de <?php echo $info['nombre']; ?>.</p>
                <a href="/proyectoCGS/pages/tienda/index.php" class="btn btn-success mt-auto">Ir a Comprar</a>
            </div>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>

</body>
</html>