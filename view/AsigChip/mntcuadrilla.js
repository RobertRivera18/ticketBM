var tabla;
function init() {
    $("#cuadrilla_form").on("submit", function (e) {

        listarColaboradores();
        listarEquipos();
    });
}


$(document).ready(function () {

    tabla = $('#cuadrilla_data').DataTable({
        "lengthMenu": [5, 10, 25, 75, 100],
        "aProcessing": true,
        "aServerSide": true,
        dom: '<Bl<f>rtip>',
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
            url: '../../controller/cuadrilla_asig.php?op=listar',
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

function eliminar(cua_id) {
    swal({
        title: "HelpDesk",
        text: "Esta seguro de Eliminar el registro?",
        type: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        closeOnConfirm: false
    },
        function (isConfirm) {
            if (isConfirm) {
                $.post("../../controller/cuadrilla_asig.php?op=eliminar", { cua_id: cua_id }, function (data) {
                });

                $('#cuadrilla_data').DataTable().ajax.reload();

                swal({
                    title: "HelpDesk!",
                    text: "Registro Eliminado.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
            }
        });
}

function listarColaboradores() {
    tabla = $('#tblcolaboradores').dataTable(
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
                url: '../../controller/cuadrilla_asig.php?op=combo',
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


let currentCuaId;

function agregar(cua_id) {
    currentCuaId = cua_id;
    listarColaboradores();
    $("#modalasignar").modal('show');
}

function agregarEquipo(cua_id) {
    currentCuaId = cua_id;
    listarEquipos();
    $("#modalequipos").modal('show');
}

function asignar(col_id) {
    $.ajax({
        url: "../../controller/cuadrilla_asig.php?op=asignar",
        type: "POST",
        data: { cua_id: currentCuaId, col_id: col_id },
        success: function (response) {
            const res = JSON.parse(response);
            if (res.status === "success") {
                swal({
                    title: "HelpDesk!",
                    text: "Colaborador Asignado Correctamente",
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


function eliminarItem(cua_id, col_id) {
    // Llamada AJAX para eliminar el colaborador de la cuadrilla
    $.ajax({
        url: "../../controller/cuadrilla_asig.php?op=eliminarColaborador",
        type: "POST",
        data: { cua_id: cua_id, col_id: col_id },
        success: function (response) {
            if (response) {
                swal({
                    title: "HelpDesk!",
                    text: "Colaborador eliminado correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });

            }
            $('#cuadrilla_data').DataTable().ajax.reload();
        }
    });
}

function eliminarItems(cua_id, equipo_id) {
    // Cerrar cualquier modal abierto antes de mostrar el nuevo modal
    $(".modal").modal("hide");

    // Asegurarse de que el modal correcto se abre
    $("#modalEliminacion").modal("show");

    $("#confirmarEliminar").off("click").on("click", function () {
        let motivo = $("#motivo").val().trim();

        if (motivo === "") {
            swal({
                title: "Error",
                text: "Debe ingresar un motivo de eliminación.",
                type: "warning",
                confirmButtonClass: "btn-warning"
            });
            return;
        }

        $("#modalEliminacion").modal('hide');
        $.ajax({
            url: "../../controller/cuadrilla_asig.php?op=eliminarEquipo",
            type: "POST",
            data: { cua_id: cua_id, equipo_id: equipo_id, motivo: motivo },
            success: function (response) {
                if (response) {
                    swal({
                        title: "HelpDesk!",
                        text: "Equipo eliminado correctamente.",
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });
                }

                $('#cuadrilla_data').DataTable().ajax.reload();
            }
        });
    });
}

function procesarArchivo(cua_id) {
    $(".modal").modal("hide");  // Cerrar cualquier modal abierto antes de mostrar el nuevo modal

    $("#cua_id").val(cua_id);
    $("#modalmantenimiento").modal("show");

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
        formData.append('cua_id', cua_id);

        $.ajax({
            url: '../../controller/cuadrilla_asig.php?op=subirComprobanteCuadrilla',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#guardarArchivo').prop('disabled', true).text('Subiendo...');
            },
            success: function (response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    console.log('Datos procesados:', data);

                    if (data.success) {
                        alert(data.message);
                        $('#modalmantenimiento').modal('hide');
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









//Funcion Asigancion de Equipos a cuadrilla
function asignarEquipo(equipo_id) {
    console.log(equipo_id);
    $.ajax({
        url: "../../controller/cuadrilla_asig.php?op=asignarEquipo",
        type: "POST",
        data: { cua_id: currentCuaId, equipo_id: equipo_id },
        success: function (response) {
            console.log("Equipo asignado:", response);

            if (response) {
                swal({
                    title: "HelpDesk!",
                    text: "Equipo asignado correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
                $("#modalequipos").modal('hide'); // Cerrar modal tras asignar
                $('#cuadrilla_data').DataTable().ajax.reload(); // Recargar tabla
            } else {
                console.error("Error al asignar equipo.");
            }
        },
        error: function (error) {
            console.error("Error en la asignación del equipo", error);
        }
    });
}


//Funcion para generar Acta de entrega Chip Cuadrilla
function generar(cua_id) {
    fetch('../../controller/cuadrilla_asig.php?op=generar_word&cua_id=' + cua_id)
        .then(response => response.json())
        .then(data => {
            if (data.status === "error") {
                swal({
                    title: "Error",
                    text: data.message,
                    icon: "error",
                    button: "Aceptar"
                });
            } else {
                swal({
                    title: "HelpDesk!",
                    text: "El documento se ha generado correctamente.",
                    icon: "success",
                    button: "Descargar"
                }).then(() => {
                    window.location.href = '../../public/actas/chipsCuadrillas/' + data.file_name;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            swal({
                title: "Error",
                text: "Ocurrió un problema al generar el documento.",
                icon: "error",
                button: "Aceptar"
            });
        });
}



//Funcion para marcar las recargas realizadas
$(document).on('change', 'input[type="checkbox"]', function () {
    var cua_id = $(this).attr('id').split('_')[1]; // Obtener el ID de la cuadrilla desde el checkbox
    var recargas = $(this).prop('checked') ? 'true' : 'false'; // Determinar si el checkbox está marcado o desmarcado

    $.ajax({
        url: '../../controller/cuadrilla_asig.php?op=marcarRecarga',
        type: 'POST',
        data: {
            cua_id: cua_id,
            recargas: recargas
        },
        success: function (response) {
            var res = JSON.parse(response);
            if (res.status === 'success') {
                console.log('Recarga actualizada');
            } else {
                console.log('Error al actualizar recarga');
            }
        }
    });
});

// Función para desmarcar todos los checkboxes
function refresh() {
    swal({
        title: "Procesando...",
        text: "Por favor, espere mientras se resetean las recargas.",
        content: {
            element: "div",
            attributes: {
                innerHTML: '<div class="loader" style="margin: auto;"></div>',
            },
        },
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
    });

    // Simular un retraso antes de iniciar la solicitud
    setTimeout(function () {
        $.ajax({
            url: '../../controller/cuadrilla_asig.php?op=desmarcarTodas',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    console.log('Recargas reseteadas');
                    // Desmarcar todas las casillas
                    $('input[type="checkbox"]').prop('checked', false);
                }
            }, complete: function () {
                swal.close();
            }
        });
    }, 1000);
}



//Genera excel de cuadrillas y recargas
$('#exportarRecargas').on('click', function () {
    window.location.href = '../../controller/cuadrilla_asig.php?op=exportarRecargas';
});





//Funcion acta de descargo de equipos por cuadrillas
function descargarNota(cua_id) {
    window.location.href = '../../controller/cuadrilla_asig.php?op=generar_word_descargo&cua_id=' + cua_id;
    swal({
        title: "Acta de descarga generada!" + cua_id,
        text: "Completado.",
        type: "info",
        confirmButtonClass: "btn-success"
    });
}

//Descargar comprobante firmado por colaborador entrega chip
function descargarComprobante(cua_id) {
    $.ajax({
        url: '../../controller/cuadrilla_asig.php?op=obtenerRutaArchivo',
        type: 'POST',
        data: { cua_id: cua_id },
        success: function (response) {
            try {
                var data = JSON.parse(response);
                if (data.success && data.ruta) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../../controller/cuadrilla_asig.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta));
                    xhr.onload = function () {
                        if (xhr.status === 404) {
                            swal({
                                title: "Error",
                                text: "El archivo no existe o ha sido eliminado",
                                icon: "error",
                                button: "Aceptar"
                            });
                        } else {
                            window.location.href = '../../controller/cuadrilla_asig.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta);
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




