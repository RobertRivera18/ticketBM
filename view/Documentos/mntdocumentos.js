var tabla;


function listarColaboradores() {
    tabla = $('#tblequipos').dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [

            ],
            "ajax":
            {
                url: '../../controller/cuadrilla.php?op=combo',
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
function asignar(cua_id) {
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
        url: "../../controller/acta.php?op=asignar",
        type: "POST",
        data: {
            tipo_acta: tipo_acta,
            cua_id: cua_id
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
}

$(document).on("click", "#btnnuevo", function () {
    $('#mdltitulo').html('Nuevo Registro');
    $('#modalmantenimiento').modal('show');
    listarColaboradores();
});


