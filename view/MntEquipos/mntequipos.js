var tabla;

function init() {
    $("#colaborador_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#colaborador_form")[0]);
    $.ajax({
        url: "../../controller/equipo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);
            $('#colaborador_form')[0].reset();
            $("#modalmantenimiento").modal('hide');
            tabla.ajax.reload();  // Usar la variable `tabla`
            swal({
                title: "HelpDesk!",
                text: "Completado.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
}

$(document).ready(function () {
    // Inicialización de DataTable
    tabla = $('#colaborador_data').DataTable({
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
            url: '../../controller/equipo.php?op=listar',
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

function editar(equipo_id) {
    $('#mdltitulo').html('Editar Registro');
    $.post("../../controller/equipo.php?op=mostrar", { equipo_id: equipo_id }, function (data) {
        data = JSON.parse(data);
        $('#equipo_id').val(data.equipo_id);
        $('#nombre_equipo').val(data.nombre_equipo);
        $('#marca').val(data.marca);
        $('#modelo').val(data.modelo);
        $('#serie').val(data.serie);
        $('#datos').val(data.datos).trigger('change');


    });
    $('#modalmantenimiento').modal('show');
}

function eliminar(equipo_id) {
    var table = $('#colaborador_data').DataTable(); // Asegurarse de que la tabla esté inicializada
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
            $.post("../../controller/equipo.php?op=eliminar", { equipo_id: equipo_id }, function (data) {
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

function generarqr(equipo_id) {
    $.ajax({
        url: "../../controller/equipo.php?op=generar_qr",
        type: "POST",
        data: { equipo_id: equipo_id },
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



function verqr(equipo_id) {
    console.log("Equipo ID recibido:", equipo_id); // Verificar el ID antes de la solicitud

    $.ajax({
        url: "../../controller/equipo.php?op=get_qr",
        type: "post",
        data: { action: "get_qr", equipo_id: equipo_id },
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



$(document).on("click", "#btnnuevo", function () {
    $('#mdltitulo').html('Nuevo Registro');
    $('#colaborador_form')[0].reset();
    $('#modalmantenimiento').modal('show');
});



init();
