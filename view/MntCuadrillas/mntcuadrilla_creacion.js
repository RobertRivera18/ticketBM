var tabla;

function init() {
    $("#cuadrilla_form").on("submit", function (e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#cuadrilla_form")[0]);
    $.ajax({
        url: "../../controller/cuadrilla_creacion.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);
            $('#cuadrilla_form')[0].reset();
            $("#modalmantenimiento").modal('hide');
            tabla.ajax.reload();  
            swal({
                title: "HelpDesk!",
                text: "Completado Cuadrilla Registrada con exito.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
}

$(document).ready(function () {
    // Inicialización de DataTable
    tabla = $('#cuadrilla_data').DataTable({
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
            url: '../../controller/cuadrilla_creacion.php?op=listar',
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

function editar(cua_id) {
    $('#mdltitulo').html('Editar Registro');
    $.post("../../controller/cuadrilla_creacion.php?op=mostrar", { cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        console.log(data)
        $('#cua_id').val(data.cua_id);
        $('#cua_nombre').val(data.cua_nombre);     
        $('#cua_empresa').val(data.cua_empresa).trigger('change'); 
        $('#cua_ciudad').val(data.cua_ciudad).trigger('change'); 
    });
    $('#modalmantenimiento').modal('show');
}

function eliminar(cua_id) {
    var table = $('#cuadrilla_data').DataTable(); 
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
            $.post("../../controller/cuadrilla_creacion.php?op=eliminar", { cua_id: cua_id }, function (data) {
                table.ajax.reload();
                swal({
                    title: "HelpDesk!",
                    text: "Registro Eliminado con exito.",
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
