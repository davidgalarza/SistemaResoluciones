$(document).ready(function(){
    var gra = document.getElementById("cargador"); 
    gra.style.display = "none";
    cargarFormatosInicio();
    graficarBarras();
    $("#Carreras").change(function(){
    var carreraID = $(this).val();
    $.get('formatoPorCarrera/'+carreraID, function(data){

       var formatoSeleccionado='';
            for (var i=0; i<data.length;i++)
            formatoSeleccionado+='<option value="'+data[i].id+'">'+data[i].nombre+'</option>';

            $("#Formatos").html(formatoSeleccionado);
            graficarBarras();
        });
    });

    $("#Formatos").change(function(){
        graficarBarras();
    });
   
});

function cargarFormatosInicio(){
    var combo = document.getElementById("Carreras").value;
        $.get('formatoPorCarrera/'+combo, function(data){
               
            var formatoSeleccionado='';
                for (var i=0; i<data.length;i++)
                formatoSeleccionado+='<option value="'+data[i].id+'">'+data[i].nombre+'</option>';
    
                $("#Formatos").html(formatoSeleccionado);
               
});
    
}

function graficarBarras(){

    var combo = document.getElementById("Carreras");
    var nombreCarrera = combo.options[combo.selectedIndex].text;
    var combo2 = document.getElementById("Formatos");
    var nombreFormatoA = combo2.options[combo2.selectedIndex].text;
    var gra = document.getElementById("cargador"); 
    gra.style.display = "block";
    $.get('graficaBarras/'+nombreCarrera+'/'+nombreFormatoA, function(datosGrafica){
        console.log(datosGrafica.length);
        if(datosGrafica.length>0){
            var data2 = [['Fecha', 'Numero resoluciones']];
            for (i = 0; i < datosGrafica.length; i++) {
                   data2[i + 1] = [datosGrafica[i].fecha_consejo, datosGrafica[i].totalR];  
            }
           
            var graficaAux = document.getElementById("top_x_div"); 
            graficaAux.style.display = "block";
            var smsAlerta1 = document.getElementById("smsAlerta1"); 
            smsAlerta1.style.display = "none";
            renderGrafico(data2);
        }else{
            var graficaAux = document.getElementById("top_x_div"); 
            graficaAux.style.display = "none";

            var carga = document.getElementById("cargador"); 
            carga.style.display = "none";

            var smsAlerta1 = document.getElementById("smsAlerta1"); 
            smsAlerta1.style.display = "block";
        }
       
    });
}

function renderGrafico( data2){
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawStuff);

    function drawStuff() {
      var data = new google.visualization.arrayToDataTable(data2);

      var options = {
        width: '100%',
        height: '500px',
        legend: { position: 'none' },
        chart: {
          //title: 'Reportes',
          //subtitle: 'Reportes por carrera'
         },
        axes: {
          x: {
            0: { side: 'top', label: 'Fechas'} // Top x-axis.
          }
        },
        bar: { groupWidth: "90%" }
      };

      var chart = new google.charts.Bar(document.getElementById('top_x_div'));
      // Convert the Classic options to Material options.
      chart.draw(data, google.charts.Bar.convertOptions(options));
    };
}