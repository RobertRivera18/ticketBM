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
            url: '../../controller/cuadrilla_asignacionEquipo.php?op=listar',
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
                $.post("../../controller/cuadrilla_asignacionEquipo?op=eliminar", { cua_id: cua_id }, function (data) {
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
                url: '../../controller/cuadrilla_asignacionEquipo.php?op=combo',
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


let currentCuaId; // Declarada global para usar en colaboradores y equipos

function agregar(cua_id) {
    currentCuaId = cua_id; // Asignar cua_id a la variable global
    listarColaboradores(); // Listar colaboradores para asignar
    $("#modalasignar").modal('show'); // Mostrar modal de colaboradores
}

function agregarEquipo(cua_id) {
    currentCuaId = cua_id; // Asignar cua_id a la variable global
    listarEquipos(); // Listar equipos para asignar
    $("#modalequipos").modal('show'); // Mostrar modal de equipos
}




function asignar(col_id) {
    $.ajax({
        url: "../../controller/cuadrilla_asignacionEquipo.php?op=asignar",
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
        url: "../../controller/cuadrilla_asignacionEquipo?op=eliminarColaborador",
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
            url: "../../controller/cuadrilla_asignacionEquipo.php?op=eliminarEquipo",
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
            url: '../../controller/cuadrilla_asignacionEquipo.php?op=subirComprobanteCuadrillaEquipo',
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


function asignarEquipo(equipo_id) {
    console.log(equipo_id);
    $.ajax({
        url: "../../controller/cuadrilla_asignacionEquipo.php?op=asignarEquipo",
        type: "POST",
        data: { cua_id: currentCuaId, equipo_id: equipo_id }, // Usar currentCuaId
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

function generar(cua_id) {
    window.location.href = '../../controller/cuadrilla_asignacionEquipo.php?op=generar_word&cua_id=' + cua_id;
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}


function descargarNota(cua_id){
    window.location.href = '../../controller/cuadrilla_asignacionEquipo.php?op=generar_word_descargo&cua_id=' + cua_id;
    swal({
        title: "Acta de descarga generada!"+cua_id,
        text: "Completado.",
        type: "info",
        confirmButtonClass: "btn-success"
    });
}

function descargarComprobante(cua_id) {
    $.ajax({
        url: '../../controller/cuadrilla_asignacionEquipo.php?op=obtenerRutaArchivo',
        type: 'POST',
        data: { cua_id: cua_id },
        success: function (response) {
            try {
                var data = JSON.parse(response);
                if (data.success && data.ruta) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', '../../controller/cuadrilla_asignacionEquipo.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta));
                    xhr.onload = function () {
                        if (xhr.status === 404) {
                            swal({
                                title: "Error",
                                text: "El archivo no existe o ha sido eliminado",
                                icon: "error",
                                button: "Aceptar"
                            });
                        } else {
                            window.location.href = '../../controller/cuadrilla_asignacionEquipo.php?op=descargarArchivo&ruta=' + encodeURIComponent(data.ruta);
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











