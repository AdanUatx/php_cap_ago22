$(document).ready(function(){

    if(Catalogos.html_catalogos == ''){
        Catalogos.obtener_catalogo_tipo_contacto();
    }

});

var Catalogos = {

    html_catalogos : '',

    obtener_catalogo_tipo_contacto : function(){
        //peticion ajax del catalogo
        $.ajax({
            type : 'post', //tipo de peticion que es del backend POST-GET-PUT-DELETE....
            url : URL_BACKEND + 'peticion=catalogos&funcion=tipo_contacto', // url de consumo del servicio
            data : {}, //datos que van hacia la ruta del backed
            dataType : 'json', //la respuesta que me devuel del server JSON,HTML,XML,....
            success : function(respuestaAjax){
                if(respuestaAjax.success){
                    var html_catalogo_tipo_contacto = '<option value="">--Seleccione--</option>';
                    respuestaAjax.data.catalogo_tipo_contacto.forEach(function(elemento){
                        html_catalogo_tipo_contacto += '<option value="'+elemento.id+'">'+elemento.tipo_contacto+'</option>';
                    });
                    Catalogos.html_catalogos = html_catalogo_tipo_contacto;
                }
            },error : function (error){
                console.log(error);
                alert('error en el catalogo');
            }
        });
    }

}