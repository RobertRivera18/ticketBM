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

function procesarArchivo() {
    $("#modalmantenimiento").modal('show');
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





















