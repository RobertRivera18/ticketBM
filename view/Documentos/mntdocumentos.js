var tabla;

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
function asignar(col_id) {
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
                // Intentamos parsear la respuesta JSON
                var res = JSON.parse(response);

                if (res.status === "success") {
                    swal({
                        title: "¡Éxito!",
                        text: "Colaborador asignado correctamente.",
                        type: "success",
                        confirmButtonClass: "btn-success"
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
        },
        error: function (error) {
            console.error("Error en la asignación:", error);
            swal({
                title: "Error",
                text: "No se pudo completar la asignación del colaborador.",
                type: "error",
                confirmButtonClass: "btn-danger"
            });
        }
    });
}


function asignarEquipo(equipo_id) {
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
        url: "../../controller/acta.php?op=asignarEquipo",
        type: "POST",
        data: {
            tipo_acta: tipo_acta,
            equipo_id: equipo_id
        },
        success: function (response) {
            try {
                var res = JSON.parse(response);

                if (res.status === "success") {
                    swal({
                        title: "¡Éxito!",
                        text: "Colaborador asignado correctamente.",
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });

                    // Actualizamos la tabla para reflejar cambios
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
            }
        },
        error: function (error) {
            console.error("Error en la asignación:", error);
            swal({
                title: "Error",
                text: "No se pudo completar la asignación del colaborador.",
                type: "error",
                confirmButtonClass: "btn-danger"
            });
        }
    });
}

function eliminarActa(id_acta) {
    $('#documento_data').DataTable().ajax.reload();// Asegurarse de que la tabla esté inicializada
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
                tabla.ajax.reload();
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


var tipo_acta = $("#tipo_acta").val();
$("#tipo_acta").on("change", function () {
    tipo_acta = $(this).val();
    if (tipo_acta == 1) {
        $('#mdltitulo').html('Nuevo Registro - Listar Colaboradores');
        $('#tablaequipos').hide();
        listarColaboradores(); // Recarga los colaboradores
    } else if (tipo_acta == 2) {
        // Si el tipo de acta no es 1, actualizar solo el título
        $('#mdltitulo').html('Nuevo Registro-Lista de Equipos');
        $('#tabla_cuadrillas').hide();
        listarEquipos();


    }
});

$(document).on("click", "#btnnuevo", function () {
    // Mostrar el modal al hacer clic en el botón de nuevo
    $('#modalmantenimiento').modal('show');
});

function procesarArchivo(id_acta) {
    $('#cargarArchivo').modal('show');
    
    // Reset form on modal show
    $('#archivo_form')[0].reset();
    
    $('#archivo_form').off('submit').on('submit', function(e) {
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
            beforeSend: function() {
                $('#guardarArchivo').prop('disabled', true).text('Subiendo...');
            },
            success: function(response) {
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
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.log('Status:', status);
                console.log('Response:', xhr.responseText);
                alert('Error en la comunicación con el servidor');
            },
            complete: function() {
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
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success && data.ruta) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../../controller/acta.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta));
                    xhr.onload = function() {
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



