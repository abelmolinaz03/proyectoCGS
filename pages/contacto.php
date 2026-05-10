<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");

$enviado = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre  = trim($_POST['nombre'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $asunto  = trim($_POST['asunto'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        $error = "Por favor, completa todos los campos.";
    } else {
        // Aquí iría el envío real del correo (mail() o PHPMailer)
        $enviado = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - SportHub Córdoba</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/proyectoCGS/css/estilos.css">
    <style>
        .contact-hero {
            background-color: var(--rojo-mezquita);
            color: var(--crema-mezquita);
            padding: 60px 0 40px;
            border-bottom: 4px solid var(--dorado-mezquita);
        }
        .contact-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 25px;
        }
        .info-icon {
            width: 45px;
            height: 45px;
            background-color: var(--rojo-mezquita);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
            font-size: 1rem;
        }
        .info-text h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            color: var(--rojo-mezquita);
            margin-bottom: 2px;
        }
        .info-text p {
            margin: 0;
            color: var(--texto-oscuro);
            font-size: 0.95rem;
        }
        .divider-gold {
            height: 3px;
            background: linear-gradient(90deg, var(--dorado-mezquita), transparent);
            border: none;
            margin: 20px 0;
        }
        .form-control:focus {
            border-color: var(--dorado-mezquita);
            box-shadow: 0 0 0 0.2rem rgba(197,160,89,0.25);
        }
        .btn-contacto {
            background-color: var(--rojo-mezquita);
            color: white;
            border: none;
        }
        .btn-contacto:hover {
            background-color: #6b0000;
            color: white;
        }
        .mapa-placeholder {
            border-radius: 12px;
            overflow: hidden;
            border: 3px solid var(--dorado-mezquita);
        }
    </style>
</head>
<body>

<?php include("../includes/header.php"); ?>

<!-- Hero -->
<section class="contact-hero text-center">
    <div class="container">
        <h1 class="fw-bold mb-2">CONTÁCTANOS</h1>
        <hr class="divider-gold mx-auto" style="width: 80px;">
        <p class="mb-0" style="color: var(--dorado-mezquita); font-size: 1.1rem;">
            Estamos aquí para ayudarte. Escríbenos y te responderemos en menos de 24 horas.
        </p>
    </div>
</section>

<!-- Contenido principal -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 align-items-stretch">

            <!-- Información de contacto -->
            <div class="col-lg-4">
                <div class="card contact-card p-4 h-100" style="background-color: var(--crema-mezquita);">
                    <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">INFORMACIÓN</h4>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="info-text">
                            <h6>Dirección</h6>
                            <p>C/ Real, 5<br>14810 Carcabuey, Córdoba</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="info-text">
                            <h6>Teléfono</h6>
                            <p>+34 651 45 58 04</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="info-text">
                            <h6>Email</h6>
                            <p>abelmolinaz.03@gmail.com</p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fa-solid fa-clock"></i></div>
                        <div class="info-text">
                            <h6>Horario de atención</h6>
                            <p>Lun – Vie: 9:00 – 19:00<br>Sáb: 10:00 – 14:00</p>
                        </div>
                    </div>

                    <hr class="divider-gold">

                    <h6 class="fw-bold mb-3" style="color: var(--rojo-mezquita); font-family: 'Montserrat', sans-serif; text-transform: uppercase; font-size: 0.8rem;">Síguenos</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-sm btn-contacto"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn btn-sm btn-contacto"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="btn btn-sm btn-contacto"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="col-lg-8">
                <div class="card contact-card p-4 h-100 d-flex flex-column" style="background-color: var(--crema-mezquita);">
                    <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">ENVÍANOS UN MENSAJE</h4>

                    <?php if ($enviado): ?>
                        <div class="alert alert-success text-center fw-bold">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            ¡Mensaje enviado! Te responderemos en breve.
                        </div>
                    <?php else: ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger small"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="contacto.php" class="d-flex flex-column flex-grow-1">
                            <div class="row g-3 flex-grow-1">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase" style="color: var(--texto-oscuro); font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Nombre completo</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase" style="color: var(--texto-oscuro); font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="ejemplo@email.com" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase" style="color: var(--texto-oscuro); font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Asunto</label>
                                    <select name="asunto" class="form-select" required>
                                        <option value="" disabled <?php echo empty($_POST['asunto']) ? 'selected' : ''; ?>>Selecciona un asunto</option>
                                        <option value="Información general" <?php echo ($_POST['asunto'] ?? '') === 'Información general' ? 'selected' : ''; ?>>Información general</option>
                                        <option value="Soporte técnico" <?php echo ($_POST['asunto'] ?? '') === 'Soporte técnico' ? 'selected' : ''; ?>>Soporte técnico</option>
                                        <option value="Colaboraciones" <?php echo ($_POST['asunto'] ?? '') === 'Colaboraciones' ? 'selected' : ''; ?>>Colaboraciones y patrocinios</option>
                                        <option value="Incidencias" <?php echo ($_POST['asunto'] ?? '') === 'Incidencias' ? 'selected' : ''; ?>>Incidencias con la cuenta</option>
                                        <option value="Otro" <?php echo ($_POST['asunto'] ?? '') === 'Otro' ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase" style="color: var(--texto-oscuro); font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Mensaje</label>
                                    <textarea name="mensaje" class="form-control flex-grow-1" rows="5" style="resize:none;" placeholder="Escribe tu mensaje aquí..." required><?php echo htmlspecialchars($_POST['mensaje'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" class="btn btn-contacto px-4">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Enviar mensaje
                                    </button>
                                </div>
                            </div>
                        </form>

                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Mapa -->
        <div class="mt-4 mapa-placeholder">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3146.0!2d-4.280000!3d37.440000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6ce6b77f1f9d6b%3A0x0!2sCarcabuey%2C%20C%C3%B3rdoba!5e0!3m2!1ses!2ses!4v1715000000001"
                width="100%" height="280" style="border:0; display:block;" allowfullscreen loading="lazy">
            </iframe>
        </div>
    </div>
</section>

<?php include("../includes/footer.php"); ?>
