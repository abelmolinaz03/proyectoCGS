<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("../includes/db.php");
include("../includes/header.php");
?>

<main style="flex: 1;">

    <section class="contact-hero text-center">
        <div class="container">
            <h1 class="fw-bold mb-2">CONTÁCTANOS</h1>
            <hr class="divider-gold mx-auto" style="width: 80px;">
            <p class="mb-0" style="color: var(--dorado-mezquita); font-size: 1.1rem;">
                Estamos aquí para ayudarte. Escríbenos y te responderemos en menos de 24 horas.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">

                <div class="col-lg-4">
                    <div class="card contact-card p-4 h-100">
                        <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">INFORMACIÓN</h4>

                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="info-text">
                                <h6>Dirección</h6>
                                <p>C/ La Marina, 9<br>14810 Carcabuey, Córdoba</p>
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

                <div class="col-lg-8">
                    <div class="card contact-card p-4 h-100 d-flex flex-column">
                        <h4 class="fw-bold mb-4" style="color: var(--rojo-mezquita);">ENVÍANOS UN MENSAJE</h4>

                        <div data-fs-success style="display:none;" class="alert alert-success text-center fw-bold">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            ¡Mensaje enviado! Te responderemos en breve.
                        </div>
                        <div data-fs-error style="display:none;" class="alert alert-danger small"></div>

                        <form id="contacto-form" class="d-flex flex-column flex-grow-1">
                            <div class="row g-3 flex-grow-1">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase" style="font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Nombre completo</label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Tu nombre" required data-fs-field>
                                    <span data-fs-error="nombre" class="text-danger small"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase" style="font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="ejemplo@email.com" required data-fs-field>
                                    <span data-fs-error="email" class="text-danger small"></span>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase" style="font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Asunto</label>
                                    <select name="asunto" class="form-select" required data-fs-field>
                                        <option value="" disabled selected>Selecciona un asunto</option>
                                        <option value="Información general">Información general</option>
                                        <option value="Soporte técnico">Soporte técnico</option>
                                        <option value="Colaboraciones">Colaboraciones y patrocinios</option>
                                        <option value="Incidencias">Incidencias con la cuenta</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    <span data-fs-error="asunto" class="text-danger small"></span>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-uppercase" style="font-family: 'Montserrat', sans-serif; font-size: 0.75rem;">Mensaje</label>
                                    <textarea name="mensaje" class="form-control" rows="5" style="resize:none;" placeholder="Escribe tu mensaje aquí..." required data-fs-field></textarea>
                                    <span data-fs-error="mensaje" class="text-danger small"></span>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-contacto px-4" data-fs-submit-btn>
                                        <i class="fa-solid fa-paper-plane me-2"></i>Enviar mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="mt-4 mapa-placeholder">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3146.0!2d-4.280000!3d37.440000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6ce6b77f1f9d6b%3A0x0!2sCarcabuey%2C%20C%C3%B3rdoba!5e0!3m2!1ses!2ses!4v1715000000001"
                    width="100%" height="280" style="border:0; display:block;" allowfullscreen loading="lazy">
                </iframe>
            </div>
        </div>
    </section>

</main>

<script>
    document.getElementById('contacto-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const form = e.target;
        const btn = form.querySelector('[type="submit"]');
        const successDiv = document.querySelector('[data-fs-success]');
        const errorDiv = document.querySelector('[data-fs-error]');

        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Enviando...';
        errorDiv.style.display = 'none';

        try {
            const res = await fetch('https://formspree.io/f/xaqvrjod', {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json' }
            });

            if (res.ok) {
                form.style.display = 'none';
                successDiv.style.display = 'block';
                setTimeout(() => {
                    successDiv.style.display = 'none';
                    form.reset();
                    form.style.display = '';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Enviar mensaje';
                }, 4000);
            } else {
                const data = await res.json();
                const msg = data.errors?.map(err => err.message).join(' ') || 'Error al enviar. Inténtalo de nuevo.';
                errorDiv.textContent = msg;
                errorDiv.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Enviar mensaje';
            }
        } catch (_) {
            errorDiv.textContent = 'Error de conexión. Comprueba tu red e inténtalo de nuevo.';
            errorDiv.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane me-2"></i>Enviar mensaje';
        }
    });
</script>

<?php include("../includes/footer.php"); ?>