var tabla;

function init() {
    $("#inspeccion_form").on("submit", function (e) {
        guardaryeditar(e);

    });
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
                url: '../../controller/colaborador.php?op=comboOperadores',
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

function guardaryeditar(e) {
    e.preventDefault();

    if ($(e.originalEvent.submitter).attr("id") === "guardarBtn") {

        var colaboradorSeleccionado = $("#col_id").val().trim();
        if (!colaboradorSeleccionado) {
            swal({
                title: "Error",
                text: "Por favor, selecciona un colaborador antes de guardar.",
                type: "error",
                confirmButtonClass: "btn-danger"
            });
            return;
        }

        var formData = new FormData($("#inspeccion_form")[0]);

        $.ajax({
            url: "../../controller/inspeccion.php?op=guardaryeditar",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (datos) {
                console.log(datos);

                $('#inspeccion_form')[0].reset(); // Limpiar el formulario
                $("#col_id").val(""); // Restablecer col_id a vacío

                tabla.ajax.reload(); // Recargar tabla

                swal({
                    title: "HelpDesk!",
                    text: "Solicitud Registrada.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });

                $('#modalmantenimiento').modal('hide'); // Cerrar modal
                $('#inspeccion_data').DataTable().ajax.reload();
            },
            error: function () {
                swal({
                    title: "Error",
                    text: "Ocurrió un problema al guardar los datos.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
            }
        });
    }
}

function asignar(col_id) {
    $("#col_id").val(col_id);
    swal({
        title: "HelpDesk!",
        text: "Se ha asignado Correctamente.",
        type: "success"
    });
    $('#modalmantenimiento').on('hidden.bs.modal', function () {
        $("#col_id").val("");
    });
}





$(document).ready(function () {
    // Inicialización de DataTable
    tabla = $('#inspeccion_data').DataTable({
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
            url: '../../controller/inspeccion.php?op=listar',
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


function ver(inspeccion_id){
    window.open('http://localhost/soporte/view/DetalleInspeccion/?ID='+ inspeccion_id +'');
}


function generar(inspeccion_id) {
    window.location.href = '../../controller/inspeccion.php?op=generar_word&inspeccion_id=' + inspeccion_id;
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}

function eliminar(inspeccion_id) {
    var table = $('#inspeccion_data').DataTable(); 
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
            $.post("../../controller/inspeccion.php?op=eliminar", { inspeccion_id: inspeccion_id }, function (data) {
                table.ajax.reload();
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
    $('#inspeccion_form')[0].reset();
    $('#modalmantenimiento').modal('show');
    listarColaboradores();
});

init();
