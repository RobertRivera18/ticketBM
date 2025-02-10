var tabla;
function init() {
    $("#cuadrilla_form").on("submit", function (e) {
        e.preventDefault();
        listarEquipos();
    });
}


$(document).ready(function () {
    tabla = $('#cuadrilla_data').DataTable({
        "lengthMenu": [5, 10, 25, 75, 100],
        "aProcessing": true,
        "aServerSide": true,
        "order": [],  // Desactiva ordenamiento automático
        dom: '<Bl<f>rtip>',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/usuario_equipo.php?op=listar',
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


function listarEquipos() {
    tabla = $('#tblequipos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
        ],
        "ajax":
        {
            url: '../../controller/equipo.php?op=comboEquipos',
            type: "get",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5,
        "order": [[0, "desc"]],
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

$(document).on("click", "#btnnuevo", function () {
    $('#mdltitulo').html('Nuevo Registro');
    $('#cuadrilla_form')[0].reset();
});
init();

let currentUsuId;
function agregarEquipo(usu_id) {
    currentUsuId = usu_id;
    listarEquipos();
    $("#modalequipos").modal('show'); // Mostrar modal de equipos
}



function eliminarItem(usu_id, equipo_id) {
    // Llamada AJAX para eliminar el equipo del usuario
    $.ajax({
        url: "../../controller/usuario_equipo.php?op=eliminarEquipo",
        type: "POST",
        data: { usu_id: usu_id, equipo_id: equipo_id },
        success: function (response) {
            if (response) {
                swal({
                    title: "HelpDesk!",
                    text: "Equipo eliminado correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
                // Recargar tabla
            }
            $('#cuadrilla_data').DataTable().ajax.reload();
        }

    });
}

function asignarEquipo(equipo_id) {
    $.ajax({
        url: "../../controller/usuario_equipo.php?op=asignarEquipo",
        type: "POST",
        data: { usu_id: currentUsuId, equipo_id: equipo_id },
        success: function (response) {
            const res = JSON.parse(response);
            if (res.status === "success") {
                swal({
                    title: "HelpDesk!",
                    text: res.message,
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
                $("#modalequipos").modal('hide');
                // Actualiza la tabla sin recargar toda la página
                $('#cuadrilla_data').DataTable().ajax.reload(null, false);
            }
        }
    });
}


function generar(usu_id) {
    window.location.href = '../../controller/usuario_equipo.php?op=generar_word&usu_id=' + usu_id;
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}

function procesarArchivo(usu_id) {
    $("#cargarArchivo").modal('show');
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
         formData.append('usu_id', usu_id);
         
         $.ajax({
             url: '../../controller/usuario_equipo.php?op=subirArchivo',
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


function descargarComprobante(usu_id) {
    $.ajax({
        url: '../../controller/usuario_equipo.php?op=obtenerRutaArchivo',
        type: 'POST',
        data: { usu_id: usu_id },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success && data.ruta) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../../controller/usuario_equipo.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta));
                    xhr.onload = function() {
                        if (xhr.status === 404) {
                            swal({
                                title: "Error",
                                text: "El archivo no existe o ha sido eliminado",
                                icon: "error",
                                button: "Aceptar"
                            });
                        } else {
                            window.location.href = '../../controller/usuario_equipo.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta);
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


function generarqr(usu_id) {
    $.ajax({
        url: "../../controller/usuario_equipo.php?op=generar_qr",
        type: "POST",
        data: { usu_id: usu_id },
        dataType: "json",
        success: function (data) {
            if (data.status === "success") {
                swal({
                    title: "Código QR Generado!",
                    text: "El código QR se ha guardado correctamente en la base de datos.",
                    icon: "success",
                    button: "OK"
                });
                tabla.ajax.reload();
            } else {
                swal("Error", data.message, "error");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud:", xhr.responseText);
            swal("Error", "Hubo un problema en la solicitud AJAX.", "error");
        }
    });
}



function verqr(usu_id) {
    console.log("Equipo ID recibido:", usu_id); // Verificar el ID antes de la solicitud

    $.ajax({
        url: "../../controller/usuario_equipo.php?op=get_qr",
        type: "post",
        data: { action: "get_qr", usu_id: usu_id },
        dataType: "json",
        success: function (response) {
            console.log("Respuesta del servidor:", response); // Imprimir la respuesta en consola
            if (response.status === "success") {

                var qrPath = "../" + response.qr_codigo;

                // Asignamos la ruta concatenada al atributo src de la imagen
                $("#imagen_qr").attr("src", qrPath);
                // Mostramos el modal
                $("#modalmantenimiento1").modal("show")
            } else {
                $("#qrContainer").html('<p>' + response.message + '</p>');
            }
            $("#modalmantenimiento1").modal("show");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("Error en AJAX:", textStatus, errorThrown); // Imprimir error en consola
            console.log("Respuesta del servidor:", jqXHR.responseText); // Ver respuesta en caso de error

            $("#qrContainer").html('<p>Error al obtener el código QR.</p>');
            $("#modalmantenimiento1").modal("show");
        }
    });
}
$("#cuadrilla_form").on("submit", function (e) {
    e.preventDefault();

    const fileInput = document.getElementById("fileElem");
    const file = fileInput.files[0];
    console.log(file)

    if (!file) {
        swal("Error", "Por favor selecciona un archivo.", "error");
        return;
    }

    const fileName = file.name;

    // Opcional: Mostrar el nombre del archivo cargado
    console.log("Archivo seleccionado:", fileName);

    // Crear un objeto FormData para enviar el archivo
    const formData = new FormData(this);

    $.ajax({
        url: "../../controller/usuario_equipo.php?op=subirArchivo",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            const res = JSON.parse(response);
            if (res.success) {
                swal("Éxito", "Archivo subido correctamente: " + fileName, "success");
                $("#modalmantenimiento").modal("hide");
                $("#cuadrilla_data").DataTable().ajax.reload(null, false);
            } else {
                swal("Error", res.message || "Error al subir el archivo.", "error");
            }
        },
        error: function (error) {
            console.error("Error en la solicitud:", error);
            swal("Error", "Error inesperado al procesar el archivo.", "error");
        },
    });
});





















