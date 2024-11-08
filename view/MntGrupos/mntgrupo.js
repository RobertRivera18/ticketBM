var tabla;

function init() {
    $("#grupo_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#grupo_form")[0]);
    $.ajax({
        url: "../../controller/grupo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);
            $('#grupo_form')[0].reset();
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
    tabla = $('#grupo_data').DataTable({
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
            url: '../../controller/grupo.php?op=listar',
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

function editar(grupo_id) {
    $('#mdltitulo').html('Editar Registro');
    $.post("../../controller/grupo.php?op=mostrar", { grupo_id: grupo_id }, function (data) {
        data = JSON.parse(data);
        $('#grupo_id').val(data.grupo_id);
        $('#grupo_nombre').val(data.grupo_nombre);
    });
    $('#modalmantenimiento').modal('show');
}

function eliminar(grupo_id) {
    var table = $('#grupo_data').DataTable();
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
            $.post("../../controller/grupo.php?op=eliminar", { grupo_id: grupo_id }, function (data) {
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
    $('#grupo_form')[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();
