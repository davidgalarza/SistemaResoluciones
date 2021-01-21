let idEstudiante = null;
let idConsejo = null;
const boton_buscar = `
<button id="boton_buscar_estudiante" onclick="buscarEstudiante()" style="display: block" class="btn btn-primary  ">
                            <i class="fas fa-search"></i>  Buscar
                        </button>
`;

const boton_cancelar = `
<button onclick="cancelarResolucion()" style="display: block" class="btn btn-secondary  ">
<i class="fas fa-times"></i>  Cancelar
                        </button>
`;

const mensaje_resoluion_exitosa = `<div class="alert alert-success" role="alert">
<strong>Resolucion creada exitosamentes</strong>
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>`;


const empty_estudiante = ` <img src="/images/student.svg" style="max-width: 3rem" alt="" class="d-inline-block " srcset="">
<p class="font-weight-bold d-inline-block">Seleccione un estudiante</p>`;

var cargasIframe = 0;
var totalGeneradoModal = 0;

$(document).ready(() => {

    $('.boton_eliminar').on('click', function(e) {
        var $form = $(this).closest('form');
        e.preventDefault();//     <--- This line.
        $('#modal_eliminar').modal('show').on('click', '#delete', function(e) {
            $form.trigger('submit');
        });
    });

    $('#boton_actulizar').on('click', function(e) {
        var form = $(this).closest('form');
        let estado = $('#estado').val();
        console.log(estado);
        if(estado === 'FINALIZADO') {
            $('#modal_finalizar').modal('show').on('click', '#finalizar', function(e) {
                form.trigger('submit');
            });;
        }

        if(estado === 'CANCELADO') {
            $('#modal_cancelado').modal('show').on('click', '#cancelar', function(e) {
                form.trigger('submit');
            });;
        }
        if(estado === 'ENPROCESO') {
            
            form.trigger('submit');
           
        }
        e.preventDefault();
    });



    $("#error_cedula").hide();
    $('#contenedor_mensaje_modal').hide(600);

    // $('#tipo_resolucion').prop( "disabled", true );
    $('.selectpicker').prop('disabled', true);

    $('#boton_buscar_estudiante').click(() => {



    });

    $("#cedula").on('input', function () {
        $('#cedula').removeClass('is-invalid');
        $("#error_cedula").hide();
        $('#contenedor_mensaje_modal').hide(600);
    });

    $('#tipo_resolucion').change(function () {
        let idFormato = $('#tipo_resolucion').val();

        $('#contenedor_formulario').html(`
            <iframe id="inlineFrameExample"
                title="Inline Frame Example"
                frameborder="0" scrolling="auto" class="iframe-full-height"
                width="100%"
                src="/resoluciones/${idConsejo}/${idFormato}/${idEstudiante}/formulario">
            </iframe>

        `)
        $('iframe').on('load', function () {
            cargasIframe++;
            
            if (cargasIframe % 2 == 0) {
                cancelarResolucion();
                totalGeneradoModal++;

                $('#contenedor_mensaje_modal').html(mensaje_resoluion_exitosa);
                $('#contenedor_mensaje_modal').show(600);
            }
                $('iframe').height(parseInt($('iframe').contents().height()) + 100);
           $('iframe').resize(() =>{
                $('iframe').height(parseInt($('iframe').contents().height()) + 100 );
                console.log('RESIZE: ' + $('iframe').contents().height());
            });
        })
    });



    $('#modal_resolucion').on('hidden.bs.modal', function () {
        console.log('SE cierrra');
        
        $('#cedula').prop("disabled", false);
        $('#cedula').val("");
        $("#error_cedula").hide();
        $('.selectpicker').selectpicker('val', '');
        $('#contenedor_formulario').html(``);
        $('#contenedor_info_es').html(empty_estudiante);
        $('#contenedor_accion_es').html(boton_buscar);
        console.log('CARGAS DE IFRAME ON CERRAR: ' + totalGeneradoModal);
        if (totalGeneradoModal >= 1) {
            window.location.reload();
        }

        cargasIframe = 0;

        totalGeneradoModal = 0;



        $('.selectpicker').prop('disabled', true);
        $('.selectpicker').selectpicker('refresh');
    })


})


function buscarEstudiante() {

    var post_url = '/estudiantes/obtener';
    $('#cedula').prop("disabled", true);


    idConsejo = $('#id_consejo').val()

    $.ajax({
        type: 'GET',
        url: post_url,
        data: { cedula: $('#cedula').val() },

        async: true,
        beforeSend: function () {
            // //add a loading gif so the broswer can see that something is happening
            // $('#modal_content').html('<div class="loading"><img scr="loading.gif"></div>');
        },
        success: function (data) {

            let datosEstudiante = JSON.parse(data);
            let idCarrera = datosEstudiante['carrera_id'];
            idEstudiante = datosEstudiante['id'];

            console.log(datosEstudiante);
            $('#contenedor_accion_es').html(boton_cancelar);

            $('#contenedor_info_es').html(`
                <div class="row">
                    <div class="col-sm-4 ">
                        <p class="text-left"><span class="font-weight-bold">Cedula:</span> ${datosEstudiante['cedula']}</p>
                    </div>
                    <div class="col-sm-4 ">
                        <p class="text-left"><span class="font-weight-bold">Nombres:</span> ${datosEstudiante['nombres']}</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="text-left"><span class="font-weight-bold">Apellidos:</span> ${datosEstudiante['apellidos']}</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="text-left"><span class="font-weight-bold">Carrera:</span> ${datosEstudiante['carreraNombre']}</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="text-left"><span class="font-weight-bold">Folio:</span> ${datosEstudiante['folio']}</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="text-left"><span class="font-weight-bold">Matricula:</span> ${datosEstudiante['matricula']}</p>
                    </div>
                </div>
            `);

            $('#tipo_resolucion').children().each(function () {
                if (idCarrera != $(this).data('carrera')) {
                    $(this).hide();
                } else {
                    $(this).show();
                }

            });

            $('.selectpicker').prop('disabled', false);
            $('.selectpicker').selectpicker('refresh');
            $('a[role="option"]').click(() =>{
                cargasIframe =0;
                console.log('CLICK');
            });


        },
        error: function () {
            $('#cedula').addClass('is-invalid');
            $('#cedula').prop("disabled", false);
            $("#error_cedula").show();
            $('#contenedor_info_es').html(empty_estudiante);

        }
    });
}


function cancelarResolucion() {
    $('#cedula').prop("disabled", false);
    $('#cedula').val("");
    $("#error_cedula").hide();
    $('.selectpicker').selectpicker('val', '');
    $('#contenedor_formulario').html(``);
    $('#contenedor_accion_es').html(boton_buscar);
    $('#contenedor_info_es').html(empty_estudiante);
    $('#contenedor_mensaje_modal').hide(600);

    cargasIframe = 0;
    $('.selectpicker').prop('disabled', true);
    $('.selectpicker').selectpicker('refresh');
}
