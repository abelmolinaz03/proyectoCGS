<?php

// ============================================================
//  VALIDACIONES.PHP — SportHub Córdoba
//  Saneamiento + Validación segura para todos los formularios
//  Orden: 1) Sanear  2) Validar  3) Devolver errores detallados
// ============================================================


// ============================================================
//  UTILIDADES GENERALES
// ============================================================

/**
 * Sanea un texto libre:
 * - Elimina espacios al inicio/final
 * - Previene XSS con htmlspecialchars (para mostrar en HTML)
 * Úsalo en campos como nombre, apellidos, descripción, etc.
 */
function sanear_texto(string $valor): string {
    $valor = trim($valor);
    return htmlspecialchars($valor, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Sanea un email: elimina espacios y caracteres ilegales.
 * No aplica htmlspecialchars porque el email va a la BD, no al HTML directamente.
 */
function sanear_email(string $valor): string {
    $valor = trim($valor);
    return filter_var($valor, FILTER_SANITIZE_EMAIL);
}

/**
 * Sanea un número entero (elimina todo lo que no sea dígito o signo).
 */
function sanear_entero(mixed $valor): int {
    return (int) filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanea un número decimal (precio, etc.).
 */
function sanear_decimal(mixed $valor): float {
    return (float) filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Valida que los valores de texto no superen la longitud máxima.
 * Compatible con el uso anterior en registro y rutinas.
 *
 * @param array $campos  Array de arrays con la forma: [valor, max_chars, etiqueta]
 * @return array  Lista de mensajes de error
 */
function validar_longitudes(array $campos): array {
    $errores = [];
    foreach ($campos as [$valor, $max, $etiqueta]) {
        if (mb_strlen($valor) > $max) {
            $errores[] = "\"$etiqueta\" no puede superar los $max caracteres.";
        }
    }
    return $errores;
}


// ============================================================
//  FORMULARIO: REGISTRO DE USUARIO (pages/registro.php)
// ============================================================

/**
 * Sanea y valida los datos del formulario de registro.
 *
 * @param array $post  El array $_POST del formulario
 * @return array       ['datos' => [...], 'errores' => [...]]
 *
 * Uso en registro.php:
 *   $resultado = validar_registro($_POST);
 *   $errores   = $resultado['errores'];
 *   $datos     = $resultado['datos'];   // usar en el INSERT
 */
function validar_registro(array $post): array {

    $errores = [];

    // --- 1. SANEAMIENTO ---
    $nombre    = sanear_texto($post['nombre']    ?? '');
    $apellidos = sanear_texto($post['apellidos'] ?? '');
    $email     = sanear_email($post['email']     ?? '');
    $password  = $post['password'] ?? '';          // la contraseña NO se sanea ni se imprime
    $deporte   = sanear_texto($post['deporte']   ?? '');

    $deportes_validos = ["Atletismo","Fútbol","Baloncesto","Pádel","Ciclismo","Natación","Tenis"];

    // --- 2. VALIDACIÓN ---

    // Campos obligatorios
    if (empty($nombre))    $errores['nombre']    = "El nombre es obligatorio.";
    if (empty($apellidos)) $errores['apellidos'] = "Los apellidos son obligatorios.";
    if (empty($email))     $errores['email']     = "El email es obligatorio.";
    if (empty($password))  $errores['password']  = "La contraseña es obligatoria.";
    if (empty($deporte))   $errores['deporte']   = "Debes seleccionar un deporte.";

    // Longitudes máximas (columnas de la BD)
    if (!empty($nombre)    && mb_strlen($nombre)    > 50)  $errores['nombre']    = "El nombre no puede superar 50 caracteres.";
    if (!empty($apellidos) && mb_strlen($apellidos) > 100) $errores['apellidos'] = "Los apellidos no pueden superar 100 caracteres.";

    // Formato de email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El formato del email no es válido.";
    }

    // Seguridad de contraseña: mínimo 8 caracteres
    if (!empty($password) && mb_strlen($password) < 8) {
        $errores['password'] = "La contraseña debe tener al menos 8 caracteres.";
    }

    // Deporte debe ser uno de los permitidos
    if (!empty($deporte) && !in_array($deporte, $deportes_validos, true)) {
        $errores['deporte'] = "El deporte seleccionado no es válido.";
    }

    return [
        'datos'   => compact('nombre', 'apellidos', 'email', 'password', 'deporte'),
        'errores' => $errores,
    ];
}


// ============================================================
//  FORMULARIO: LOGIN (pages/login.php)
// ============================================================

/**
 * Sanea y valida el formulario de login.
 *
 * @param array $post  El array $_POST
 * @return array       ['datos' => [...], 'errores' => [...]]
 */
function validar_login(array $post): array {

    $errores = [];

    // --- 1. SANEAMIENTO ---
    $email    = sanear_email($post['email']    ?? '');
    $password = $post['password'] ?? '';   // la contraseña no se toca

    // --- 2. VALIDACIÓN ---
    if (empty($email))    $errores['email']    = "El email es obligatorio.";
    if (empty($password)) $errores['password'] = "La contraseña es obligatoria.";

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = "El formato del email no es válido.";
    }

    return [
        'datos'   => compact('email', 'password'),
        'errores' => $errores,
    ];
}


// ============================================================
//  FORMULARIO: RESERVA DE PISTA (pages/pistas/reservar.php)
// ============================================================

/**
 * Sanea y valida el formulario de reserva de pista.
 *
 * @param array $post  El array $_POST
 * @return array       ['datos' => [...], 'errores' => [...]]
 */
function validar_reserva(array $post): array {

    $errores = [];

    // --- 1. SANEAMIENTO ---
    // Las fechas y horas solo deben contener caracteres seguros
    $fecha      = trim(strip_tags($post['fecha']       ?? ''));
    $hora_inicio = trim(strip_tags($post['hora_inicio'] ?? ''));

    $franjas_validas = ['08:00','09:00','10:00','11:00','12:00','16:00','17:00','18:00','19:00','20:00'];

    // --- 2. VALIDACIÓN ---
    if (empty($fecha)) {
        $errores['fecha'] = "La fecha es obligatoria.";
    } else {
        // Formato YYYY-MM-DD
        $dt = DateTime::createFromFormat('Y-m-d', $fecha);
        if (!$dt || $dt->format('Y-m-d') !== $fecha) {
            $errores['fecha'] = "El formato de fecha no es válido.";
        } elseif ($dt < new DateTime('today')) {
            $errores['fecha'] = "No puedes reservar en una fecha pasada.";
        } elseif ($dt > new DateTime('+30 days')) {
            $errores['fecha'] = "Solo puedes reservar con un máximo de 30 días de antelación.";
        }
    }

    if (empty($hora_inicio)) {
        $errores['hora_inicio'] = "La franja horaria es obligatoria.";
    } elseif (!in_array($hora_inicio, $franjas_validas, true)) {
        $errores['hora_inicio'] = "La franja horaria seleccionada no es válida.";
    }

    return [
        'datos'   => compact('fecha', 'hora_inicio'),
        'errores' => $errores,
    ];
}


// ============================================================
//  FORMULARIO: CREAR / EDITAR RUTINA (admin/rutinas/)
// ============================================================

/**
 * Sanea y valida el formulario de creación/edición de rutinas.
 *
 * @param array $post  El array $_POST
 * @return array       ['datos' => [...], 'errores' => [...]]
 */
function validar_rutina(array $post): array {

    $errores = [];

    $deportes_validos    = ["Todos","Atletismo","Fútbol","Baloncesto","Pádel","Ciclismo","Natación","Tenis"];
    $dificultades_validas = ["Principiante","Intermedio","Avanzado"];

    // --- 1. SANEAMIENTO ---
    $titulo      = sanear_texto($post['titulo']             ?? '');
    $descripcion = sanear_texto($post['descripcion']        ?? '');
    $deporte     = sanear_texto($post['deporte']            ?? '');
    $dificultad  = sanear_texto($post['dificultad']         ?? '');
    $duracion    = $post['duracion_minutos'] !== '' ? sanear_entero($post['duracion_minutos'] ?? '') : null;

    // Saneamiento de ejercicios (arrays)
    $ej_nombres   = array_map('sanear_texto',  $post['ejercicio_nombre']       ?? []);
    $ej_series    = array_map('sanear_entero', $post['ejercicio_series']       ?? []);
    $ej_reps      = array_map('sanear_texto',  $post['ejercicio_repeticiones'] ?? []);
    $ej_descansos = array_map('sanear_entero', $post['ejercicio_descanso']     ?? []);

    // --- 2. VALIDACIÓN ---
    if (empty($titulo))     $errores['titulo']     = "El título es obligatorio.";
    if (empty($deporte))    $errores['deporte']    = "El deporte es obligatorio.";
    if (empty($dificultad)) $errores['dificultad'] = "La dificultad es obligatoria.";

    if (!empty($titulo) && mb_strlen($titulo) > 100) {
        $errores['titulo'] = "El título no puede superar 100 caracteres.";
    }

    if (!empty($deporte) && !in_array($deporte, $deportes_validos, true)) {
        $errores['deporte'] = "El deporte seleccionado no es válido.";
    }

    if (!empty($dificultad) && !in_array($dificultad, $dificultades_validas, true)) {
        $errores['dificultad'] = "La dificultad seleccionada no es válida.";
    }

    if ($duracion !== null && ($duracion < 1 || $duracion > 600)) {
        $errores['duracion_minutos'] = "La duración debe estar entre 1 y 600 minutos.";
    }

    // Validar ejercicios: cada nombre no puede superar 100 caracteres
    foreach ($ej_nombres as $i => $nombre_ej) {
        if (!empty($nombre_ej) && mb_strlen($nombre_ej) > 100) {
            $errores["ejercicio_$i"] = "El nombre del ejercicio " . ($i + 1) . " no puede superar 100 caracteres.";
        }
    }

    return [
        'datos' => [
            'titulo'      => $titulo,
            'descripcion' => $descripcion,
            'deporte'     => $deporte,
            'dificultad'  => $dificultad,
            'duracion'    => $duracion,
            'ejercicios'  => [
                'nombres'   => $ej_nombres,
                'series'    => $ej_series,
                'reps'      => $ej_reps,
                'descansos' => $ej_descansos,
            ],
        ],
        'errores' => $errores,
    ];
}


// ============================================================
//  FORMULARIO: CREAR / EDITAR PRODUCTO (admin/productos.php)
// ============================================================

/**
 * Sanea y valida el formulario de productos de la tienda.
 *
 * @param array $post   El array $_POST
 * @param array $files  El array $_FILES (para validar imagen)
 * @return array        ['datos' => [...], 'errores' => [...]]
 */
function validar_producto(array $post, array $files = []): array {

    $errores = [];

    $deportes_validos = ["Atletismo","Fútbol","Baloncesto","Pádel","Ciclismo","Natación","Tenis"];
    $extensiones_validas = ['jpg', 'jpeg', 'png', 'webp'];

    // --- 1. SANEAMIENTO ---
    $nombre      = sanear_texto($post['nombre']      ?? '');
    $descripcion = sanear_texto($post['descripcion'] ?? '');
    $precio      = sanear_decimal($post['precio']    ?? 0);
    $deporte     = sanear_texto($post['deporte']     ?? '');
    $stock       = sanear_entero($post['stock']      ?? 0);

    // --- 2. VALIDACIÓN ---
    if (empty($nombre))  $errores['nombre']  = "El nombre del producto es obligatorio.";
    if (empty($deporte)) $errores['deporte'] = "El deporte es obligatorio.";

    if (!empty($nombre) && mb_strlen($nombre) > 100) {
        $errores['nombre'] = "El nombre no puede superar 100 caracteres.";
    }

    if ($precio <= 0) {
        $errores['precio'] = "El precio debe ser mayor que 0.";
    }

    if ($stock < 0) {
        $errores['stock'] = "El stock no puede ser negativo.";
    }

    if (!empty($deporte) && !in_array($deporte, $deportes_validos, true)) {
        $errores['deporte'] = "El deporte seleccionado no es válido.";
    }

    // Validar imagen si se ha subido una
    if (!empty($files['imagen']['name'])) {
        $ext = strtolower(pathinfo($files['imagen']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $extensiones_validas, true)) {
            $errores['imagen'] = "La imagen debe ser JPG, PNG o WEBP.";
        }
        if ($files['imagen']['size'] > 2 * 1024 * 1024) {
            $errores['imagen'] = "La imagen no puede superar 2MB.";
        }
    }

    return [
        'datos'   => compact('nombre', 'descripcion', 'precio', 'deporte', 'stock'),
        'errores' => $errores,
    ];
}
