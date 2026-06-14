<?php
// 1. Conexión a la base de datos
include("../includes/db.php");
include("../includes/validaciones.php");

$errores = [];
$registro_exitoso = false;

// Lista de deportes
$deportes_disponibles = ["Atletismo", "Fútbol", "Baloncesto", "Pádel", "Ciclismo", "Natación", "Tenis"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Saneamiento y validación centralizados en validaciones.php
    $resultado = validar_registro($_POST);
    $errores   = $resultado['errores'];
    $datos     = $resultado['datos'];

    if (empty($errores)) {
        try {
            // Comprobar si el email ya existe
            $stmt_check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
            $stmt_check->execute([$datos['email']]);

            if ($stmt_check->rowCount() > 0) {
                $errores['email'] = "Este email ya está registrado.";
            } else {
                // Insertar nuevo usuario con PDO (sentencia preparada)
                $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

                $stmt = $conexion->prepare(
                    "INSERT INTO usuarios (nombre, apellidos, email, password, deporte_principal) VALUES (?, ?, ?, ?, ?)"
                );

                if ($stmt->execute([
                    $datos['nombre'],
                    $datos['apellidos'],
                    $datos['email'],<
                    $passwordHash,
                    $datos['deporte'],
                ])) {
                    $registro_exitoso = true;
                    $errores = [];
                }
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la base de datos: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - SportHub Córdoba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        /* Estilo botón rojo */
        .btn-registro {
            background-color: var(--rojo-mezquita) !important;
            color: white !important;
            border: none;
        }
        .btn-registro:hover {
            filter: brightness(1.2);
        }
    </style>
</head>

<body style="background-color: var(--crema-mezquita);">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 400px; border-radius: 15px; border: none;">
        <h3 class="text-center mb-3 fw-bold" style="color: var(--rojo-mezquita);">Registro de Atleta</h3>

        <?php foreach($errores as $error): ?>
            <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
        <?php endforeach; ?>

        <?php if($registro_exitoso): ?>
            <div class="alert alert-success text-center">¡Registro completado con éxito!</div>
            <div class="text-center"><a href="login.php" class="btn btn-sm btn-outline-success">Ir al Login</a></div>
        <?php endif; ?>

        <form method="POST" action="registro.php" class="<?php echo $registro_exitoso ? 'd-none' : ''; ?>">
            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" maxlength="50" required>
            <input type="text" name="apellidos" class="form-control mb-2" placeholder="Apellidos" maxlength="100" required>
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
            
            <select name="deporte" class="form-select mb-3" required>
                <option value="" disabled selected>Selecciona tu deporte principal</option>
                <?php foreach($deportes_disponibles as $opcion): ?>
                    <option value="<?php echo $opcion; ?>"><?php echo $opcion; ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-registro w-100 py-2 fw-bold mb-2">Registrarse</button>
            <a href="javascript:history.back()" class="btn btn-outline-secondary w-100">← Volver atrás</a>
        </form>

        <p class="mt-3 text-center mb-0">
            ¿Ya tienes cuenta? <a href="login.php" style="color: var(--rojo-mezquita);">Inicia sesión</a>
        </p>
    </div>
</div>

</body>
</html>