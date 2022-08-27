$(document).ready(function(){
    $(document).on('click','#btn_agregar_empleado',function(){
        Empleados.mostrar_formulario_empleado();
        $('#btn_reiniciar_form').trigger('click');
        $('#tbody_listado_datos_contacto').html('');
        $('#campo_id_empleado').remove();
    });

    $(document).on('click','.btn_modificar_empleado',function(){
        //obtener el empleado codificador y convertirlo a un objeto json
        var empleado = JSON.parse(atob($(this).data('str_empleado')));
        //setear los datos del empleado al formulario de registro
        var id = '<input type="hidden" id="id" name="id_empleado" value="'+empleado.id+'">';
        $('#form_empleado').append(id);
        $('#campo_nombre').val(empleado.nombre);
        $('#campo_paterno').val(empleado.paterno);
        $('#campo_materno').val(empleado.materno);
        $('#campo_direccion').val(empleado.direccion);
        $('#campo_fecha_nacimiento').val(empleado.fecha_nacimiento);
        empleado.genero == 'h' ? $('#campo_genero_h').attr('checked',true) : $('#campo_genero_m').attr('checked',true);
        //actualizar el tablero de los datos de contacto
        var contador_dc = 0;
        $('#tbody_listado_datos_contacto').html('');
        empleado.datos_contacto.forEach(function (elemento){
            Empleados.agregar_row_dato_contacto();
            //$('#tbody_listado_datos_contacto').append(html_dato_contacto);
            var renglon = $('#tbody_listado_datos_contacto').find('tr')[contador_dc];
            $(renglon).find('select.campo_catalogo_tipo_contacto').val(elemento.catalogo_tipo_contacto_id);
            $(renglon).find('input.campo_dato_contacto').val(elemento.dato_contacto);
            contador_dc++;
        });
        Empleados.mostrar_formulario_empleado();
    });

    $(document).on('click','.btn_eliminar_empleado',function(){
        //obtenemos el id del empleado que esta en el data id_empleado del boton
        var id = $(this).data('id');
        var confirmacion = confirm('¿Esta seguro de eliminar el empleado?');
        if(confirmacion){
            Empleados.eliminar(id);
        }
    });

    $(document).on('click','#btn_guardar_empleado',function(){
        //if(Empleados.validar_formulario_empleado()){
        var actualizar = $('#id').length != 0 ? true : false;
        //}
        Empleados.guardar_empleado(actualizar);
    });

    $(document).on('click','#btn_agregar_row_contacto',function(){
        Empleados.agregar_row_dato_contacto();
    });

    $(document).on('click','.btn_eliminar_dato_contacto',function(){
        $(this).closest('tr').remove();
    });

    $(document).on('click','#btn_cancelar_registro_empleado',function(){
        Empleados.mostrar_tablero_empleado();
    });

    Empleados.listado_empleados();

});

//variable que sera tratada como una clase en programacion
var Empleados = {

    mostrar_formulario_empleado : function(){
        $('#contenedor_tablero_empleado').fadeOut();//hide() - JS
        $('#contenedor_formulario_empleado').fadeIn();//show() - JS
        $('#btn_agregar_empleado').fadeOut();
        $('#seccion_filtro_tablero').hide();
    },

    mostrar_tablero_empleado : function(){
        $('#contenedor_tablero_empleado').fadeIn();//hide() - JS
        $('#contenedor_formulario_empleado').fadeOut();//show() - JS
        $('#btn_agregar_empleado').fadeIn();
        $('#seccion_filtro_tablero').show();
    },

    /*listado_empleados : function(){
        $('#tbodyTableroEmpleados').html('<tr><td colspan="5" style="text-align: center"><span class="spinner-border"></span>Procesando Datos</td></tr>');
        $.ajax({
            type : 'post',
            url : URL_BACKEND + 'peticion=empleado&funcion=listado', // url de consumo del servicio
            data : {},
            dataType : 'json',
            success : function(respuestaAjax){
                if(respuestaAjax.success){
                    var html_listado_empleados = '';
                    respuestaAjax.data.empleados.forEach(function(empleado){
                        var stringEmpleado = btoa(JSON.stringify(empleado));//almacenar en una cadena de string y codificarla
                        var html_datos_contacto_empleado = '';
                        empleado.datos_contacto.forEach(function(contacto){
                          html_datos_contacto_empleado += '<li>'+contacto.tipo_contacto+': '+ contacto.dato_contacto+'</li>'; //contacto.nombre && contacto.contacto
                        });
                        html_listado_empleados += '<tr>' +
                                '<td>'+empleado.id+'</td>' +
                                '<td>'+empleado.nombre +' '+empleado.paterno+ ' '+empleado.materno+'</td>' +
                                //'<td>'+empleado.direccion+'</td>' +//
                                '<td>'+empleado.fecha_nacimiento+'</td>' +
                                '<td>'+html_datos_contacto_empleado+'</td>' +
                                '<td>' +
                                    '<button type="button" data-str_empleado="'+stringEmpleado+'" class="btn btn-outline-warning btn-sm btn_modificar_empleado">Editar</button>\n' +
                                    '<button type="button" data-id_empleado="'+empleado.id+'" class="btn btn-outline-danger btn-sm btn_eliminar_empleado">Eliminar</button>' +
                                '</td>' +
                            '</tr>';
                    });
                    $('#tbodyTableroEmpleados').html(html_listado_empleados);
                }else{
                    var html_msg_error = '<div class="alert alert-warning">';
                    respuestaAjax.msg.forEach(function(elemento){
                        html_msg_error += '<li>'+elemento+'</li>';
                    });
                    html_msg_error += '</div>';
                    $('#mensajes_sistema').html(html_msg_error);
                    setTimeout(function(){
                        $('#mensajes_sistema').html('');
                    },5000);
                }
            },error : function(error){
                console.log(error);
                alert('error en el catalogo');
            }
        });
    },*/

    listado_empleados : function(){
        $('#tbodyTableroEmpleados').html('<tr><td colspan="5" style="text-align: center"><span class="spinner-border"></span>Procesando Datos</td></tr>');
        $.ajax({
            type : 'POST',
            url : URL_BACKEND + 'peticion=empleado&funcion=listado', // url de consumo del servicio
            data : {},
            dataType : 'json',
            success : function(respuestaAjax){
                if(respuestaAjax.success){
                    var html_listado_empleados = '';
                    respuestaAjax.data.empleados.forEach(function(empleado){
                        var stringEmpleado = btoa(JSON.stringify(empleado));//almacenar en una cadena de string y codificarla
                        var html_datos_contacto_empleado = '';
                        empleado.datos_contacto.forEach(function(contacto){
                          html_datos_contacto_empleado += '<li>'+contacto.tipo_contacto+': '+ contacto.dato_contacto+'</li>'; //contacto.nombre && contacto.contacto
                        });
                        html_listado_empleados += '<tr>' +
                                '<td>'+empleado.id+'</td>' +
                                '<td>'+empleado.nombre +' '+empleado.paterno+ ' '+empleado.materno+'</td>' +
                                //'<td>'+empleado.direccion+'</td>' +//
                                '<td>'+empleado.fecha_nacimiento+'</td>' +
                                '<td>'+html_datos_contacto_empleado+'</td>' +
                                '<td>' +
                                    '<button type="button" data-str_empleado="'+stringEmpleado+'" class="btn btn-outline-warning btn-sm btn_modificar_empleado">Editar</button>\n' +
                                    '<button type="button" data-id="'+empleado.id+'" class="btn btn-outline-danger btn-sm btn_eliminar_empleado">Eliminar</button>' +
                                '</td>' +
                            '</tr>';
                    });
                    $('#tbodyTableroEmpleados').html(html_listado_empleados);
                }else{
                    var html_msg_error = '<div class="alert alert-warning">';
                    respuestaAjax.msg.forEach(function(elemento){
                        html_msg_error += '<li>'+elemento+'</li>';
                    });
                    html_msg_error += '</div>';
                    $('#mensajes_sistema').html(html_msg_error);
                    setTimeout(function(){
                        $('#mensajes_sistema').html('');
                    },5000);
                }
            },error : function(error){
                console.log(error);
                alert('error en el catalogo');
            }
        });
    },

    //Este redirije a un carpeta que no ocupa el otro codigo
    validar_formulario_empleado : function(){
        var validacion = {
            status : true,
            msg : ''
        };
        if($('#campo_nombre').val() == ''){
            validacion.status = false;
            validacion.msg += '<li>El campo nombre es requerido</li>';
        }if($('#campo_paterno').val() == ''){
            validacion.status = false;
            validacion.msg += '<li>El campo apellido paterno es requerido</li>';
        }if($('#campo_materno').val() == ''){
            validacion.status =  false;
            validacion.msg += '<li>El campo apellido materno es requerido</li>'
        }if($('#campo_fecha_nacimiento').val() == ''){
            validacion.status =  false;
            validacion.msg += '<li>El campo fecha nacimiento es requerido</li>'
        }
        return validacion;
    },

    guardar_empleado : function(actualizar = false){
        var url = actualizar ? URL_BACKEND + 'peticion=empleado&funcion=actualizar' : URL_BACKEND + 'peticion=empleado&funcion=agregar';
        $.ajax({
            type : 'POST',
            url : url,
            data : $('#form_empleado').serialize(),
            // data : {
            //     nombre : $('#campo_nombre').val(),
            //     paterno : $('#campo_paterno').val(),
            // }
            dataType: 'json',
            success : function(respuestaAjax){
                if(respuestaAjax.success){
                    Empleados.listado_empleados();
                    Empleados.mostrar_tablero_empleado()
                    ;
                }else{
                    var html_msg_error = '<div class="alert alert-warning">';
                    respuestaAjax.msg.forEach(function(elemento){
                        html_msg_error += '<li>'+elemento+'</li>';
                    });
                    html_msg_error += '</div>';
                    $('#mensajes_sistema').html(html_msg_error);
                    setTimeout(function(){
                        $('#mensajes_sistema').html('');
                    },5000);
                }
            },error : function(error){
                alert('Ocurrio un error en la peticion');
            }
        });
    },

    eliminar : function(id){
        $.ajax({
            type : 'post',
            url : URL_BACKEND + 'peticion=empleado&funcion=eliminar',
            data : {
                id : id
            },
            dataType : 'json',
            success : function(respuestaAjax){
                if(respuestaAjax.success){
                    Empleados.listado_empleados();
                }
                var html_msg_error = '<div class="alert alert-warning">';
                respuestaAjax.msg.forEach(function(elemento){
                    html_msg_error += '<li>'+elemento+'</li>';
                });
                html_msg_error += '</div>';
                $('#mensajes_sistema').html(html_msg_error);
                setTimeout(function(){
                    $('#mensajes_sistema').html('');
                },5000);
            },error : function(error){
                alert('Ocurrio un error en la peticion');
            }
        });
    },

    agregar_row_dato_contacto : function(){
        var numero_datos_contacto = $('#tbody_listado_datos_contacto').find('tr').length;
        var html_row_dato_contacto = '<tr>' +
                '<td>' +
                    '<select class="form-select campo_catalogo_tipo_contacto" required name="listado_datos_contacto['+numero_datos_contacto+'][catalogo_tipo_contacto_id]">' +
                        Catalogos.html_catalogos+
                    '</select>' +
                '</td>' +
                '<td><input type="text" class="form-control campo_dato_contacto" required name="listado_datos_contacto['+numero_datos_contacto+'][dato_contacto]" placeholder="Ingresa el dato de contacto"></td>' +
                '<td><button type="button" class="btn btn-outline-danger btn-sm btn_eliminar_dato_contacto">Eliminar</button></td>' +
            '</tr>';
        $('#tbody_listado_datos_contacto').append(html_row_dato_contacto);
    },

}