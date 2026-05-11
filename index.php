<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: /proyectoCGS/pages/dashboard.php');
    exit();
}
include("includes/header.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportHub Córdoba | Tu portal deportivo</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/proyectoCGS/css/estilos.css">

</head>
<body>

    <main>
        <section style="border-bottom: 6px solid var(--dorado-mezquita);">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Atletismo">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Entrena con la fuerza de nuestra historia.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1543326727-cf6c39e8f84c?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Fútbol">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Gestiona tus marcas y rutinas en un solo lugar.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1530549387789-4c1017266635?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Natación">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Supera tus récords en Córdoba.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Ciclismo">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Conquista las rutas de la Sierra Cordobesa.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Baloncesto">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Encesta en las canchas de Córdoba.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Tenis">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Perfecciona tu saque en Córdoba.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                    <div class="carousel-item">
                        <img src="https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=1400&q=80" class="d-block w-100" style="height: calc(100vh - 70px); object-fit: cover; filter: brightness(0.55);" alt="Pádel">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100">
                            <h1 class="display-2 fw-bold mb-3">SPORT<span style="color: var(--dorado-mezquita);">HUB</span> CÓRDOBA</h1>
                            <p class="lead fs-3 mb-4">Juega al pádel en las mejores pistas.</p>
                            <div class="d-flex gap-3">
                                <a href="pages/registro.php" class="btn btn-success btn-lg px-5 py-3 shadow">UNIRME AHORA</a>
                                <a href="pages/login.php" class="btn btn-outline-light btn-lg px-5 py-3">INICIAR SESIÓN</a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Controles -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>

                <!-- Indicadores -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="5"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="6"></button>
                </div>
            </div>
        </section>
        
        <section class="py-5" style="background-color: var(--crema-mezquita);">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold" style="color: var(--rojo-mezquita);">DISCIPLINAS CALIFAS</h2>
                    <div style="width: 80px; height: 4px; background: var(--dorado-mezquita); margin: 20px auto;"></div>
                </div>

                <div class="row g-4 justify-content-center">
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <i class="fa-solid fa-person-running feature-icon"></i>
                            <h4 class="fw-bold">Atletismo</h4>
                            <p class="small">Control de series y records en El Fontanar.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <i class="fa-solid fa-futbol feature-icon"></i>
                            <h4 class="fw-bold">Fútbol</h4>
                            <p class="small">Rutas por las sierras de nuestra provincia.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <i class="fa-solid fa-person-swimming feature-icon"></i>
                            <h4 class="fw-bold">Natación</h4>
                            <p class="small">Tiempos en piscinas cordobesas.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <i class="fa-solid fa-bicycle feature-icon"></i>
                            <h4 class="fw-bold">Ciclismo</h4>
                            <p class="small">Rutas por las sierras de nuestra provincia.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <i class="fa-solid fa-basketball feature-icon"></i>
                            <h4 class="fw-bold">Baloncesto</h4>
                            <p class="small">Rutas por las sierras de nuestra provincia.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <svg class="feature-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="65" height="65" fill="currentColor" style="display: block; margin: 0 auto 1rem;">
                                <ellipse cx="38" cy="35" rx="26" ry="28" fill="none" stroke="currentColor" stroke-width="5"/>
                                <line x1="28" y1="8" x2="26" y2="62" stroke="currentColor" stroke-width="2"/>
                                <line x1="38" y1="7" x2="38" y2="63" stroke="currentColor" stroke-width="2"/>
                                <line x1="48" y1="8" x2="50" y2="62" stroke="currentColor" stroke-width="2"/>
                                <line x1="13" y1="25" x2="63" y2="25" stroke="currentColor" stroke-width="2"/>
                                <line x1="12" y1="35" x2="64" y2="35" stroke="currentColor" stroke-width="2"/>
                                <line x1="13" y1="45" x2="63" y2="45" stroke="currentColor" stroke-width="2"/>
                                <path d="M30 61 L34 74 L42 74 L46 61" fill="currentColor" stroke="currentColor" stroke-width="1"/>
                                <rect x="34" y="73" width="8" height="20" rx="4" fill="currentColor"/>
                                <rect x="33" y="85" width="10" height="3" rx="1.5" fill="none" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <h4 class="fw-bold">Tenis</h4>
                            <p class="small">Perfecciona tu saque y controla tus partidos en Córdoba.</p>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="card card-custom h-100 shadow-sm border-0 text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="65" height="65" class="feature-icon" style="display: block; margin: 0 auto 1rem; transition: transform 0.3s ease;" fill="currentColor">
                                <rect x="20" y="5" width="60" height="60" rx="20" ry="20"/>
                                <circle cx="38" cy="22" r="5" fill="var(--crema-mezquita)"/>
                                <circle cx="62" cy="22" r="5" fill="var(--crema-mezquita)"/>
                                <circle cx="38" cy="38" r="5" fill="var(--crema-mezquita)"/>
                                <circle cx="62" cy="38" r="5" fill="var(--crema-mezquita)"/>
                                <circle cx="38" cy="54" r="5" fill="var(--crema-mezquita)"/>
                                <circle cx="62" cy="54" r="5" fill="var(--crema-mezquita)"/>
                                <rect x="42" y="65" width="16" height="28" rx="6"/>
                                <rect x="41" y="80" width="18" height="4" rx="2" fill="var(--crema-mezquita)"/>
                            </svg>
                            <h4 class="fw-bold">Padel</h4>
                            <p class="small">Rutas por las sierras de nuestra provincia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 text-white" style="background-color: var(--rojo-mezquita);">
            <div class="container">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h2 class="display-4 stat-number">+500</h2>
                        <p class="text-uppercase">Atletas</p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="display-4 stat-number">7</h2>
                        <p class="text-uppercase">Deportes</p>
                    </div>
                    <div class="col-md-4">
                        <h2 class="display-4 stat-number">100%</h2>
                        <p class="text-uppercase">Local</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>