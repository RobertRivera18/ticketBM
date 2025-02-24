var tabla;


function init() {
    $("#documento_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}



$(document).ready(function () {
    $("#tabla_cuadrillas, #tabla_equipos").hide();
    $(document).on("click", "#btnnuevo", function () {
        $('#modalmantenimiento').modal('show');
        $("#tipo_acta").val(1).trigger('change');
        $("#tabla_cuadrillas, #tabla_equipos").hide();
    });


    $("#tipo_acta").on("change", function () {
        let tipo = $(this).val();
        // Acta de Entrega Credencial
        if (tipo == "2") {
            $("#tabla_cuadrillas").show();
            $("#tabla_equipos").hide();
            listarColaboradores();
            // Acta de Entrega Equipo
        } else if (tipo == "3") {
            $("#tabla_cuadrillas, #tabla_equipos").show();
            listarColaboradores();
            listarEquipos();
        } else {
            $("#tabla_cuadrillas, #tabla_equipos").hide();
        }
    });

    // Cuando se cierra el modal, restablecer los valores
    $('#modalmantenimiento').on('hidden.bs.modal', function () {
        $("#tipo_acta").val("").trigger('change');
        $("#tabla_cuadrillas, #tabla_equipos").hide();
    });
});

function asignar(col_id, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    $("#col_id").val(col_id);
    swal({ 
        title: "HelpDesk!",
        text: "Colaborador seleccionado correctamente.",
        icon: "info",
        buttons: false, 
        timer: 2000 
    });
    
}






function asignarEquipo(equipo_id, event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    $("#equipo_id").val(equipo_id);
    swal({ 
        title: "HelpDesk!",
        text: "Equipo seleccionado correctamente.",
        icon: "info",
        buttons: false, 
        timer: 2000 
    });
    
}

function guardaryeditar(e) {
    e.preventDefault();

    // Obtener los valores de los campos del formulario
    let tipo_acta = $("#tipo_acta").val();
    let col_id = $("#col_id").val();
    let equipo_id = $("#equipo_id").val();

    // Validar que col_id no esté vacío
    if (!col_id || col_id.trim() === "") {
        swal({
            title: "Error",
            text: "Por favor, selecciona un colaborador antes de guardar.",
            icon: "error",
            confirmButtonClass: "btn-danger"
        });
        return;
    }

   
    let url = "../../controller/acta.php?op=asignar";  // Caso por defecto
    let data = { tipo_acta, col_id };

    // Si tipo_acta es igual a 3, cambiar la URL y agregar equipo_id a los datos
    if (tipo_acta == 3) {
        url = "../../controller/acta.php?op=asignarEquipo";
        data.equipo_id = equipo_id;  // Añadir equipo_id solo si tipo_acta es 3
    }

    // Realizar la solicitud AJAX
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function (datos) {
            console.log(datos);
            // Limpiar el formulario y actualizar la tabla después de la operación
            $('#documento_form')[0].reset();
            $("#col_id").val("");
            tabla.ajax.reload();

            // Mostrar un mensaje de éxito
            swal({
                title: "HelpDesk!",
                text: "Acta  Registrada.",
                icon: "success",
                confirmButtonClass: "btn-success"
            });

            // Cerrar el modal y recargar la tabla de datos
            $('#modalmantenimiento').modal('hide');
            $('#documento_data').DataTable().ajax.reload();
        
        },
        error: function () {
            swal({
                title: "Error",
                text: "Ocurrió un problema al guardar los datos.",
                icon: "error",
                confirmButtonClass: "btn-danger"
            });
        }
    });
}



function listarEquipos() {
    tabla = $('#tblequipos').dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [
            ],
            "ajax":
            {
                url: '../../controller/equipo.php?op=combo',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]],//Ordenar (columna,orden)
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        }).DataTable();

}
function listarColaboradores() {
    tabla = $('#tblcuadrillas').dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [

            ],
            "ajax":
            {
                url: '../../controller/colaborador.php?op=combo',
                type: "get",
                dataType: "json",
                error: function (e) {
                    console.log(e.responseText);
                }
            },
            "bDestroy": true,
            "iDisplayLength": 5,//Paginación
            "order": [[0, "desc"]],//Ordenar (columna,orden)
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sSearch": "Buscar:",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                }
            }
        }).DataTable();

}



/* function asignar(col_id) {
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
        url: "../../controller/acta.php?op=asignar",
        type: "POST",
        data: {
            tipo_acta: tipo_acta,
            col_id: col_id
        },
        success: function (response) {
            try {
                var res = JSON.parse(response);

                if (res.status === "success") {
                    swal({
                        title: "HelpDesk!",
                        text: "Se ha asignado Correctamente.",
                        type: "success"
                    });

                    $('#documento_data').DataTable().ajax.reload();
                } else {
                    swal({
                        title: "Error",
                        text: res.message || "Ocurrió un problema al asignar el colaborador.",
                        type: "error",
                        confirmButtonClass: "btn-danger"
                    });
                }
            } catch (error) {
                console.error("Error al procesar la respuesta:", error);
                swal({
                    title: "Error",
                    text: "La respuesta del servidor no es válida.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        }
    });

} */

// Función para seleccionar el colaborador
// function asignar(col_id, e) {
//     e.preventDefault(); // Previene cualquier acción predeterminada (por si hay un evento de submit)
//     $("#col_id").val(col_id); // Guarda el ID del colaborador seleccionado en el input

// }

// Función para seleccionar el equipo


// Función para asignar colaborador y equipo
// Función para seleccionar colaborador sin cerrar el modal








// // Función para asignar el equipo al colaborador y registrar el acta
// function guardarActa(e) {
//     e.preventDefault();
//     if ($(e.originalEvent.submitter).attr("id") === "guardarBtn") {
//         var tipo_acta = $("#tipo_acta").val();
//         var col_id = $("#col_id").val();
//         var equipo_id = $("#equipo_id").val();
//         console.log(col_id);
//         console.log(equipo_id)

//         if (!col_id) {
//             swal({
//                 title: "Error",
//                 text: "Debe seleccionar un colaborador antes de continuar.",
//                 type: "warning",
//                 confirmButtonClass: "btn-warning"
//             });
//             return;
//         }

//         if (!equipo_id) {
//             swal({
//                 title: "Error",
//                 text: "Debe seleccionar un equipo antes de continuar.",
//                 type: "warning",
//                 confirmButtonClass: "btn-warning"
//             });
//             return;
//         }

//         // Enviar los datos al backend
//         $.ajax({
//             url: "../../controller/acta.php?op=asignarEquipo",
//             type: "POST",
//             data: {
//                 tipo_acta: tipo_acta,
//                 col_id: col_id,
//                 equipo_id: equipo_id
//             },
//             success: function (response) {
//                 try {
//                     var res = JSON.parse(response);

//                     if (res.status === "success") {
//                         swal({
//                             title: "¡Éxito!",
//                             text: "Acta registrada correctamente.",
//                             type: "success",
//                             confirmButtonClass: "btn-success"
//                         });

//                         // Recargar las tablas sin cerrar el modal
//                         $('#documento_data').DataTable().ajax.reload();
//                         $('#tblcuadrillas').DataTable().ajax.reload();
//                         $('#tblequipos').DataTable().ajax.reload();

//                         // Limpiar los campos para una nueva asignación
//                         $("#col_id").val('');
//                         $("#equipo_id").val('');
//                     } else {
//                         swal({
//                             title: "Error",
//                             text: res.message || "Ocurrió un problema al registrar el acta.",
//                             type: "error",
//                             confirmButtonClass: "btn-danger"
//                         });
//                     }
//                 } catch (error) {
//                     console.error("Error al procesar la respuesta:", error);
//                 }
//             },
//             error: function (error) {
//                 console.error("Error en la asignación:", error);
//                 swal({
//                     title: "Error",
//                     text: "No se pudo completar la asignación.",
//                     type: "error",
//                     confirmButtonClass: "btn-danger"
//                 });
//             }
//         });
//     }
// }




//Funcion para eliminar un acta 

function eliminarActa(id_acta) {
    var table = $('#documento_data').DataTable(); // Asegurarse de que la tabla esté inicializada$('#documento_data').DataTable().ajax.reload();// Asegurarse de que la tabla esté inicializada
    swal({
        title: "HelpDesk",
        text: "¿Está seguro de eliminar el registro?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.post("../../controller/acta.php?op=eliminarActa", { id_acta: id_acta }, function (data) {
                table.ajax.reload(); // Recargar la tabla
                swal({
                    title: "HelpDesk!",
                    text: "Registro Eliminado.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
            });
        }
    });
}

//Lista las Actas
$(document).ready(function () {
    tabla = $('#documento_data').DataTable({
        "lengthMenu": [5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/acta.php?op=listar',
            type: "post",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 100,
        "autoWidth": false,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });
});


function generar(id_acta) {
    window.location.href = '../../controller/acta.php?op=generar_word&id_acta=' + id_acta;
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}

function procesarArchivo(id_acta) {
    $('#cargarArchivo').modal('show');

    // Reset form on modal show
    $('#archivo_form')[0].reset();

    $('#archivo_form').off('submit').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData();
        var fileInput = $('#archivo')[0].files[0];

        if (!fileInput) {
            alert("Por favor, seleccione un archivo");
            return false;
        }

        formData.append('archivo', fileInput);
        formData.append('id_acta', id_acta);

        $.ajax({
            url: '../../controller/acta.php?op=subirArchivo',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#guardarArchivo').prop('disabled', true).text('Subiendo...');
            },
            success: function (response) {
                console.log('Respuesta del servidor:', response);
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.success) {
                        alert(data.message);
                        $('#cargarArchivo').modal('hide');
                    } else {
                        alert(data.message || 'Error al subir el archivo');
                    }
                } catch (e) {
                    console.error('Error al procesar respuesta:', e);
                    alert('Error al procesar la respuesta del servidor');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error AJAX:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
                alert('Error en la comunicación con el servidor');
            },
            complete: function () {
                $('#guardarArchivo').prop('disabled', false).text('Guardar');
            }
        });
    });
}

function descargarComprobante(id_acta) {
    $.ajax({
        url: '../../controller/acta.php?op=obtenerRutaArchivo',
        type: 'POST',
        data: { id_acta: id_acta },
        success: function (response) {
            try {
                var data = JSON.parse(response);
                if (data.success && data.ruta) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../../controller/acta.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta));
                    xhr.onload = function () {
                        if (xhr.status === 404) {
                            swal({
                                title: "Error",
                                text: "El archivo no existe o ha sido eliminado",
                                icon: "error",
                                button: "Aceptar"
                            });
                        } else {
                            window.location.href = '../../controller/acta.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta);
                        }
                    };
                    xhr.send();
                } else {
                    swal({
                        title: "Error",
                        text: "No se encontró información del archivo",
                        icon: "error",
                        button: "Aceptar"
                    });
                }
            } catch (e) {
                swal({
                    title: "Error",
                    text: "Error al procesar la respuesta",
                    icon: "error",
                    button: "Aceptar"
                });
            }
        }
    });
}



init();
