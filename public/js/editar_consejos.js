let idEstudiante = null;
let idConsejo = null;

$(document).ready(() => {

    $('#boton_buscar_estudiante').click(() =>{

        var post_url = '/estudiantes/obtener';
        $('#cedula').prop( "disabled", true );

        idConsejo = $('#id_consejo').val()

        $.ajax({
            type : 'GET',
            url : post_url,
            data: { cedula: $('#cedula').val() },
        
            async: true,
            beforeSend:function(){
                // //add a loading gif so the broswer can see that something is happening
                // $('#modal_content').html('<div class="loading"><img scr="loading.gif"></div>');
            },
            success : function(data){

                let datosEstudiante = JSON.parse(data);
                let idCarrera = datosEstudiante['carrera_id'];
                idEstudiante = datosEstudiante['id'];

    
                $('#tipo_resolucion').children().each(function (){
                    if(idCarrera != $(this).data('carrera')){
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                    
                });

                $('#tipo_resolucion').prop( "disabled", false );

            },
            error : function() {
                //$('#modal_content').html('<p class="error">Error in submit</p>');
            }
        });


    });

    $('#tipo_resolucion').change(function() {
        let idFormato =  $('#tipo_resolucion').val();
        console.log({
            idFormato, 
            idConsejo,
            idEstudiante
        });
        $('#contenedor_formulario').html(`
        <iframe id="inlineFrameExample"
            title="Inline Frame Example"
            frameborder="0" scrolling="auto" class="iframe-full-height"
            width="100%"
            src="http://sistemaresoluciones.test/resoluciones/${idConsejo}/${idFormato}/${idEstudiante}/formulario">
        </iframe>

        `)
        $('iframe').on('load',function () {
            $('iframe').height($('iframe').contents().height());
        });
    });

    

})

