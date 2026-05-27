// Editar y eliminar marcas deportivas
function confirmDelete(el) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta marca se eliminará permanentemente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#8b1a1a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = el.href;
        }
    });
    return false;
}

function confirmEdit() {
    Swal.fire({
        title: '¿Guardar cambios?',
        text: 'Se actualizará esta marca.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#8b1a1a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEditar').submit();
        }
    });
}

// Botón volver arriba
window.addEventListener('scroll', function() {
    const btn = document.getElementById('btnArriba');
    if (btn) {
        btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
    }
});