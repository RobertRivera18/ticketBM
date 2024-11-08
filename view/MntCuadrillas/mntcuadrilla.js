var tabla;
function init() {
    // Evento submit del formulario
    $("#cuadrilla_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    
    var formData = new FormData($("#cuadrilla_form")[0]);
    // Verificar los datos que se están enviando
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]); // Esto imprimirá los datos que se están enviando
    }

    $.ajax({
        url: "../../controller/cuadrilla.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);  // Muestra los datos recibidos del servidor
            var response = JSON.parse(datos);
            if (response.status === "success") {
                $('#cuadrilla_form')[0].reset();
                $("#modalmantenimiento").modal('hide');
                tabla.ajax.reload();  // Recargar la tabla
                swal({
                    title: "HelpDesk!",
                    text: "Completado.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
            } else {
                swal({
                    title: "Error",
                    text: response.message || "No se pudo completar la operación",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        },
        error: function(xhr, status, error) {
            console.log("Error en AJAX: ", error);
            swal({
                title: "Error",
                text: "Hubo un problema al procesar la solicitud",
                type: "error",
                confirmButtonClass: "btn-danger"
            });
        }
    });
}


$(document).ready(function () {
    // Inicialización de DataTable con la variable `tabla`
    tabla = $('#cuadrilla_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/cuadrilla.php?op=listar',
            type: "post",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
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

function editar(cua_id) {
    $('#mdltitulo').html('Editar Registro');
    $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        $('#cua_id').val(data.cua_id);
        $('#cua_nombre').val(data.cua_nombre);
    });
    $('#modalmantenimiento').modal('show');
}

function eliminar(cua_id) {
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
            $.post("../../controller/cuadrilla.php?op=eliminar", { cua_id: cua_id }, function (data) {
                
                tabla.ajax.reload();  // Usar la variable `tabla`
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

$(document).on("click", "#btnnuevo", function () {
    $('#mdltitulo').html('Nuevo Registro');
    $('#cuadrilla_form')[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();


/* var tabla;

function init() {
    // Evento submit del formulario
    $("#cuadrilla_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#cuadrilla_form")[0]);

    // Depurar FormData
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Primero, hacer la solicitud para guardar o editar la cuadrilla
    $.ajax({
        url: "../../controller/cuadrilla.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log("Respuesta del servidor:", datos);
            $('#cuadrilla_form')[0].reset();
            $("#modalmantenimiento").modal('hide');

            // Verificar si la respuesta fue exitosa antes de proceder
            if (datos.status === "success") {
                // Proceder con la asignación del colaborador
                var cua_id = $('#cua_id').val();  // Obtener el ID de la cuadrilla
                var col_id = $('#col_id').val();  // Obtener el ID del colaborador

                var formDataAsignacion = new FormData();
                formDataAsignacion.append('cua_id', cua_id);
                formDataAsignacion.append('col_id', col_id);

                // Ahora, hacer la solicitud para asignar el colaborador
                $.ajax({
                    url: "../../controller/cuadrilla.php?op=asignar",
                    type: "POST",
                    data: formDataAsignacion,
                    contentType: false,
                    processData: false,
                    success: function (datos) {
                        console.log(datos);  // Verificar la respuesta
                        var cua_id = $('#cua_id').val();
                        $.post("../../controller/email.php?op=ticket_asignado", { tick_id: cua_id }, function (data) {
                            // Acción después de enviar el email
                        });

                        swal("Correcto!", "Asignado Correctamente", "success");
                        $("#modalasignar").modal('hide');
                        $('#cuadrilla_data').DataTable().ajax.reload();
                    }
                });
            } else {
                swal("Error", "No se pudo guardar la cuadrilla", "error");
            }
        },
        error: function(xhr, status, error) {
            console.log("Error en AJAX:", error);
        }
    });
}


$(document).ready(function () {
    // Cargar el combo de colaboradores
    $.post("../../controller/colaborador.php?op=combo", function (data) {
        console.log("Combo de colaboradores:", data); // Verificar los datos devueltos
        $('#col_id').html(data);
    });

    // Inicialización de DataTable
    tabla = $('#cuadrilla_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        "searching": true,
        lengthChange: false,
        colReorder: true,
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "ajax": {
            url: '../../controller/cuadrilla.php?op=listar',
            type: "post",
            dataType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
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



function editar(cua_id) {
    $('#mdltitulo').html('Editar Registro');
    $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        $('#cua_id').val(data.cua_id);
        $('#cua_nombre').val(data.cua_nombre);
    });
    $('#modalmantenimiento').modal('show');
}

function asignar(cua_id) {
    $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        $('#cua_id').val(data.cua_id);
        $('#mdltitulo').html('Asignar Colaboradores');
        $("#modalasignar").modal('show');
    });
}

function eliminar(cua_id) {
    var table = $('#cuadrilla_data').DataTable(); // Asegurarse de que la tabla esté inicializada
    swal({
        title: "HelpDesk",
        text: "¿Está seguro de eliminar el registro?",
        icon: "error",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Sí",
        cancelButtonText: "No",
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            $.post("../../controller/cuadrilla.php?op=eliminar", { cua_id: cua_id }, function (data) {
                table.ajax.reload(); // Recargar la tabla
                swal({
                    title: "HelpDesk!",
                    text: "Registro Eliminado.",
                    icon: "success",
                    confirmButtonClass: "btn-success"
                });
            });
        }
    });
}

$(document).on("click", "#btnnuevo", function () {
    $('#mdltitulo').html('Nuevo Registro');
    $('#cuadrilla_form')[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();
 */