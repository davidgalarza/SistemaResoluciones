$(document).ready(() => {

    $('.boton_eliminar').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();//     <--- This line.
        $('#modal_eliminar').modal('show').on('click', '#delete', function(e) {
            $form.trigger('submit');
        });;
    });
})



