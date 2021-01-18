$(document).ready(() => {
    let botonNuevo = $('#boton_nuevo');
    botonNuevo.on('click', function(e) {
        if(!botonNuevo.data('puede')){
            e.preventDefault();
            $('#modal_en_proceso').modal('show');
        }
    });
});