var tabla;

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
                url: '../../controller/equipo.php?op=combo',
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
function asignar(col_id) {
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
        url: "../../controller/acta.php?op=asignar",
        type: "POST",
        data: {
            tipo_acta: tipo_acta,
            col_id: col_id
        },
        success: function (response) {
            try {
                // Intentamos parsear la respuesta JSON
                var res = JSON.parse(response);

                if (res.status === "success") {
                    swal({
                        title: "¡Éxito!",
                        text: "Colaborador asignado correctamente.",
                        type: "success",
                        confirmButtonClass: "btn-success"
                    });
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
                swal({
                    title: "Error",
                    text: "La respuesta del servidor no es válida.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
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


function asignarEquipo(equipo_id) {
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
        url: "../../controller/acta.php?op=asignarEquipo",
        type: "POST",
        data: {
            tipo_acta: tipo_acta,
            equipo_id: equipo_id
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
    swal({
        title: "HelpDesk!",
        text: "Completado.",
        type: "success",
        confirmButtonClass: "btn-success"
    });
}

var tipo_acta = $("#tipo_acta").val();
$("#tipo_acta").on("change", function () {
    tipo_acta = $(this).val();
    if (tipo_acta == 1) {
        $('#mdltitulo').html('Nuevo Registro - Listar Colaboradores');
        $('#tablaequipos').hide();
        listarColaboradores(); // Recarga los colaboradores
    } else if (tipo_acta == 2) {
        // Si el tipo de acta no es 1, actualizar solo el título
        $('#mdltitulo').html('Nuevo Registro-Lista de Equipos');
        $('#tabla_cuadrillas').hide();
        listarEquipos();


    }
});

$(document).on("click", "#btnnuevo", function () {
    // Mostrar el modal al hacer clic en el botón de nuevo
    $('#modalmantenimiento').modal('show');
});

function procesarArchivo(id_acta) {
    $('#id_acta').val(id_acta);
    $('#cargarArchivo').modal('show');

    // Escucha el cambio en el input de archivo
    $('#archivo').on('change', function () {
        var archivoInput = this.files[0];
        console.log(archivoInput ? "Archivo seleccionado: " + archivoInput.name : "No se seleccionó ningún archivo.");
    });

    // Maneja el envío del formulario
    $('#documento_form').off('submit').on('submit', function (e) {
        e.preventDefault();

        var archivoInput = $('#archivo')[0].files[0];
        if (!archivoInput) {
            alert("Por favor, selecciona un archivo antes de continuar.");
            return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: '../../controller/acta.php?op=subirArchivo',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                try {
                    var res = JSON.parse(response);
                    if (res.success) {
                        alert("Archivo cargado con éxito: " + res.nombre_guardado);
                        $('#cargarArchivo').modal('hide');
                        // Opcional: Recarga datos en la tabla
                    } else {
                        alert("Error: " + res.message);
                    }
                } catch (e) {
                    console.error("Error en la respuesta del servidor:", e);
                    alert("Ocurrió un error inesperado.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                alert("Error al cargar el archivo. Intenta nuevamente.");
            }
        });
    });
}






