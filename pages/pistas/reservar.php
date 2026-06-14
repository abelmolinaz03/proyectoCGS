<?php
include("../../includes/auth.php");
include("../../includes/db.php");
include("../../includes/validaciones.php");
include("../../includes/header.php");

$id_pista = $_GET['id'] ?? null;
$id_usuario = $_SESSION['usuario_id'];

if(!$id_pista){
    header("Location: index.php");
    exit();
}

$stmt = $conexion->prepare("SELECT * FROM pistas WHERE id_pista = ?");
$stmt->execute([$id_pista]);
$pista = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$pista){
    header("Location: index.php");
    exit();
}

// Franjas horarias disponibles
$franjas = [
    '08:00' => '08:00 - 09:00',
    '09:00' => '09:00 - 10:00',
    '10:00' => '10:00 - 11:00',
    '11:00' => '11:00 - 12:00',
    '12:00' => '12:00 - 13:00',
    '16:00' => '16:00 - 17:00',
    '17:00' => '17:00 - 18:00',
    '18:00' => '18:00 - 19:00',
    '19:00' => '19:00 - 20:00',
    '20:00' => '20:00 - 21:00',
];

$error = '';
$exito = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Saneamiento y validación centralizados en validaciones.php
    $resultado   = validar_reserva($_POST);
    $errores     = $resultado['errores'];
    $datos       = $resultado['datos'];

    if(empty($errores)){
        $fecha       = $datos['fecha'];
        $hora_inicio = $datos['hora_inicio'];
        $hora_fin    = date('H:i:s', strtotime($hora_inicio) + 3600);

        // Comprobar disponibilidad con sentencia preparada (PDO)
        $stmt2 = $conexion->prepare(
            "SELECT * FROM reservas WHERE id_pista = ? AND fecha_reserva = ? AND hora_inicio = ?"
        );
        $stmt2->execute([$id_pista, $fecha, $hora_inicio]);
        $ocupada = $stmt2->fetch();

        if($ocupada){
            $errores['franja'] = "Esa franja ya está reservada. Elige otra hora o fecha.";
        } else {
            $stmt3 = $conexion->prepare(
                "INSERT INTO reservas (id_usuario, id_pista, fecha_reserva, hora_inicio, hora_fin) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt3->execute([$id_usuario, $id_pista, $fecha, $hora_inicio, $hora_fin]);
            $exito = true;
        }
    }
}

// Obtener reservas de la pista para los próximos 7 días (para mostrar disponibilidad)
$stmt4 = $conexion->prepare("SELECT fecha_reserva, hora_inicio FROM reservas WHERE id_pista = ? AND fecha_reserva >= CURDATE() ORDER BY fecha_reserva, hora_inicio");
$stmt4->execute([$id_pista]);
$reservas_existentes = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$ocupadas = [];
foreach($reservas_existentes as $r){
    $ocupadas[$r['fecha_reserva']][] = $r['hora_inicio'];
}
?>

<main style="flex: 1;">
<div class="container py-5" style="max-width: 700px;">

    <a href="index.php" class="btn btn-outline-secondary btn-sm mb-4">
        <i class="fa-solid fa-arrow-left me-1"></i>Volver a pistas
    </a>

    <?php if($pista['imagen']): ?>
        <img src="/proyectoCGS/multimedia/pistas/<?php echo htmlspecialchars($pista['imagen']); ?>"
             class="w-100 mb-4" style="height: 220px; object-fit: cover; border-radius: 15px;" alt="">
    <?php endif; ?>

    <h2 class="fw-bold mb-1" style="color: var(--rojo-mezquita);"><?php echo htmlspecialchars($pista['nombre_pista']); ?></h2>
    <p class="text-muted mb-4">
        <i class="fa-solid fa-location-dot me-1"></i><?php echo htmlspecialchars($pista['direccion']); ?>
    </p>

    <?php if($exito): ?>
        <div class="alert alert-success text-center fw-bold">
            <i class="fa-solid fa-circle-check me-2"></i>¡Reserva realizada con éxito!
            <div class="mt-2">
                <a href="reservasuser.php" class="btn btn-sm text-white" style="background-color: var(--rojo-mezquita);">Ver mis reservas</a>
            </div>
        </div>
    <?php else: ?>

        <?php if(!empty($errores)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars(implode(' ', $errores)); ?>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">
                <i class="fa-solid fa-calendar-plus me-2"></i>Hacer una reserva
            </h5>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" name="fecha" class="form-control" 
                           min="<?php echo date('Y-m-d'); ?>" 
                           max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                           required id="fecha-input">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Franja horaria</label>
                    <select name="hora_inicio" class="form-select" required id="franja-select">
                        <option value="" disabled selected>Selecciona una franja</option>
                        <?php foreach($franjas as $hora => $label): ?>
                            <option value="<?php echo $hora; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted" id="disponibilidad-msg"></small>
                </div>

                <button type="submit" class="btn text-white w-100" style="background-color: var(--rojo-mezquita);">
                    <i class="fa-solid fa-calendar-check me-2"></i>Confirmar reserva
                </button>
            </form>
        </div>

        <!-- Ocupación próximos días -->
        <?php if(!empty($ocupadas)): ?>
        <div class="card border-0 shadow-sm p-4 mt-4">
            <h6 class="fw-bold mb-3" style="color: var(--rojo-mezquita);">Franjas ya reservadas</h6>
            <?php foreach($ocupadas as $fecha => $horas): ?>
                <div class="mb-2">
                    <span class="fw-semibold small"><?php echo date('d/m/Y', strtotime($fecha)); ?>:</span>
                    <?php foreach($horas as $h): ?>
                        <span class="badge ms-1" style="background-color: var(--rojo-mezquita);">
                            <?php echo substr($h, 0, 5); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    <?php endif; ?>

</div>
</main>

<script>
const ocupadas = <?php echo json_encode($ocupadas); ?>;

document.getElementById('fecha-input').addEventListener('change', function(){
    actualizarFranjas(this.value);
});

document.getElementById('franja-select').addEventListener('change', function(){
    const fecha = document.getElementById('fecha-input').value;
    const hora = this.value;
    const msg = document.getElementById('disponibilidad-msg');
    if(fecha && hora && ocupadas[fecha] && ocupadas[fecha].includes(hora + ':00')){
        msg.textContent = 'Esta franja ya está ocupada.';
        msg.style.color = 'red';
    } else {
        msg.textContent = fecha && hora ? 'Disponible' : '';
        msg.style.color = 'green';
    }
});

function actualizarFranjas(fecha){
    const select = document.getElementById('franja-select');
    const msg = document.getElementById('disponibilidad-msg');
    select.value = '';
    msg.textContent = '';
    Array.from(select.options).forEach(opt => {
        if(!opt.value) return;
        const horaCompleta = opt.value + ':00';
        if(ocupadas[fecha] && ocupadas[fecha].includes(horaCompleta)){
            opt.disabled = true;
            opt.textContent = opt.textContent.replace(' ✓','').replace(' (ocupada)','') + ' (ocupada)';
        } else {
            opt.disabled = false;
            opt.textContent = opt.textContent.replace(' ✓','').replace(' (ocupada)','');
        }
    });
}
</script>

<?php include("../../includes/footer.php"); ?>