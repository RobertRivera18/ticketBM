var tabla;
function init() {
    $("#cuadrilla_form").on("submit", function (e) {
        guardar(e);
        listarColaboradores();
        listarEquipos();
    });
}

function guardar(e) {
    e.preventDefault();
    var formData = new FormData($("#cuadrilla_form")[0]);
    $.ajax({
        url: "../../controller/cuadrilla_asig.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (datos) {
            console.log(datos);
            $('#cuadrilla_form')[0].reset();
            $("#modalmantenimiento").modal('hide');
            $('#cuadrilla_data').DataTable().ajax.reload();

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

// $(document).on("click", "#btnnuevo", function () {
//     $('#mdltitulo').html('Nuevo Registro');
//     $('#cuadrilla_form')[0].reset();
// });
// init();


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
        url: "../../controller/cuadrilla_asig.php?op=asignar",
        type: "POST",
        data: { cua_id: currentCuaId, col_id: col_id },
        success: function (response) {
            console.log("Colaborador asignado:", response);

            if (response) {
                swal({
                    title: "HelpDesk!",
                    text: "Colaborador asignado correctamente.",
                    type: "success",
                    confirmButtonClass: "btn-success"
                });
                $("#modalasignar").modal('hide');
                $('#cuadrilla_data').DataTable().ajax.reload();
            } else {
                console.error("Error al asignar colaborador.");
            }
        },
        error: function (error) {
            console.error("Error en la asignación", error);
        }
    });
}


function asignarEquipo(equipo_id) {
    console.log(equipo_id);
    $.ajax({
        url: "../../controller/cuadrilla_asig.php?op=asignarEquipo",
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
    window.location.href = '../../controller/cuadrilla_asig.php?op=generar_word&cua_id=' + cua_id;
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}

$(document).on('change', 'input[type="checkbox"]', function() {
    var cua_id = $(this).attr('id').split('_')[1]; // Obtener el ID de la cuadrilla desde el checkbox
    var recargas = $(this).prop('checked') ? 'true' : 'false'; // Determinar si el checkbox está marcado o desmarcado
    
    $.ajax({
        url: '../../controller/cuadrilla_asig.php?op=marcarRecarga',
        type: 'POST',
        data: {
            cua_id: cua_id,
            recargas: recargas
        },
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status === 'success') {
                console.log('Recarga actualizada');
            } else {
                console.log('Error al actualizar recarga');
            }
        }
    });
});

//Funcion para desmarcar todos los checkBox
function refresh() {
    $.ajax({
        url: '../../controller/cuadrilla_asig.php?op=desmarcarTodas',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                console.log('Recargas reseteadas');
                location.reload();
            } else {
                console.error('Error al actualizar recarga');
            }
        }
    });
}
$('#exportarRecargas').on('click', function() {
    window.location.href = '../../controller/cuadrilla_asig.php?op=exportarRecargas';
});







