var tabla;
function init() {
    $("#cuadrilla_form").on("submit", function (e) {
        guardaryeditar(e);
        listarColaboradores();
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#cuadrilla_form")[0]);
    $.ajax({
        url: "../../controller/cuadrilla.php?op=guardaryeditar",
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
                text: "Completado.",
                type: "success",
                confirmButtonClass: "btn-success"
            });
        }
    });
}
$(document).ready(function () {
     listarColaboradores()
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
    $.post("../../controller/cuadrilla.php?op=mostrar", {cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        $('#cua_id').val(data.cua_id);
        $('#cua_nombre').val(data.cua_nombre);
      
    });
    $('#modalmantenimiento').modal('show');
}
function eliminar(cua_id){
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
    function(isConfirm) {
        if (isConfirm) {
            $.post("../../controller/cuadrilla.php?op=eliminar", {cua_id : cua_id}, function (data) {
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

function listarColaboradores()
{
	tabla=$('#tblcolaboradores').dataTable(
	{
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: 'Bfrtip',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../../controller/colaborador.php?op=combo',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}


$(document).on("click","#btnnuevo", function(){
    $('#mdltitulo').html('Nuevo Registro');
    $('#cuadrilla_form')[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();


// $(document).ready(function () {
//     // Cargar el combo de colaboradores
//     // $.post("../../controller/colaborador.php?op=combo", function (data) {
//     //     console.log("Combo de colaboradores:", data); // Verificar los datos devueltos
//     //     $('#col_id').html(data);
//     //     $('#col_nombre').html(data);
//     //     $('#col_cedula').html(data);
//     // });
//     listarColaboradores();

    
//         });
    
            
function asignar(cua_id) {
    $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
        data = JSON.parse(data);
        $('#cua_id').val(data.cua_id);
        $('#mdltitulo').html('Asignar Colaboradores');
        $("#modalasignar").modal('show');
    });
}







// var tabla;

// $(document).ready(function () {
//     // Inicialización de DataTable
//     tabla = $('#cuadrilla_data').DataTable({
//         "aProcessing": true,
//         "aServerSide": true,
//         dom: 'Bfrtip',
//         "searching": true,
//         lengthChange: false,
//         colReorder: true,
//         buttons: [
//             'copyHtml5',
//             'excelHtml5',
//             'csvHtml5',
//             'pdfHtml5'
//         ],
//         "ajax": {
//             url: '../../controller/cuadrilla.php?op=listar',
//             type: "post",
//             dataType: "json",
//             error: function (e) {
//                 console.log(e.responseText);
//             }
//         },
//         "bDestroy": true,
//         "responsive": true,
//         "bInfo": true,
//         "iDisplayLength": 10,
//         "autoWidth": false,
//         "language": {
//             "sProcessing": "Procesando...",
//             "sLengthMenu": "Mostrar _MENU_ registros",
//             "sZeroRecords": "No se encontraron resultados",
//             "sEmptyTable": "Ningún dato disponible en esta tabla",
//             "sInfo": "Mostrando un total de _TOTAL_ registros",
//             "sInfoEmpty": "Mostrando un total de 0 registros",
//             "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
//             "sSearch": "Buscar:",
//             "oPaginate": {
//                 "sFirst": "Primero",
//                 "sLast": "Último",
//                 "sNext": "Siguiente",
//                 "sPrevious": "Anterior"
//             }
//         }
//     });

    // Cargar el combo de colaboradores
    // $.post("../../controller/colaborador.php?op=combo", function (data) {
    //     console.log("Combo de colaboradores:", data);
    //     $('#col_id').html(data);
    // });
    
    // listarColaboradores();

    // Evento submit del formulario
//     $("#cuadrilla_form").on("submit", function (e) {
//         guardaryeditar(e);
//     });
// });

// function listarColaboradores() {
//     $('#tblcolaboradores').dataTable({
//                 "aProcessing": true,//Activamos el procesamiento del datatables
//                 "aServerSide": true,//Paginación y filtrado realizados por el servidor
//                 dom: 'Bfrtip',//Definimos los elementos del control de tabla
//                 buttons: [		          
                            
//                         ],
//                 "ajax":
//                         {
//                             url: '../../controller/colaborador.php?op=combo',
//                             type : "get",
//                             dataType : "json",						
//                             error: function(e){
//                                 console.log(e.responseText);	
//                             }
//                         },
//                 "bDestroy": true,
//                 "iDisplayLength": 5,//Paginación
//                 "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
//             }).DataTable();
//         }
    


// Función para guardar o editar la cuadrilla
// function guardaryeditar(e) {
//     e.preventDefault();
//     var formData = new FormData($("#cuadrilla_form")[0]);

//     $.ajax({
//         url: "../../controller/cuadrilla.php?op=guardaryeditar",
//         type: "POST",
//         data: formData,
//         contentType: false,
//         processData: false,
//         success: function (datos) {
//             console.log("Respuesta del servidor:", datos);
//             $('#cuadrilla_form')[0].reset();
//             $("#modalmantenimiento").modal('hide');

//             // Verificar si la respuesta fue exitosa
//             if (datos.status === "success") {
//                 asignarColaborador(); // Llamar a la función para asignar colaborador
//             } else {
//                 swal("Error", "No se pudo guardar la cuadrilla", "error");
//             }
//         },
//         error: function(xhr, status, error) {
//             console.log("Error en AJAX:", error);
//         }
//     });
// }

// Función para asignar un colaborador
// function asignarColaborador() {
//     var cua_id = $('#cua_id').val();
//     var col_id = $('#col_id').val();

//     var formDataAsignacion = new FormData();
//     formDataAsignacion.append('cua_id', cua_id);
//     formDataAsignacion.append('col_id', col_id);

//     $.ajax({
//         url: "../../controller/cuadrilla.php?op=asignar",
//         type: "POST",
//         data: formDataAsignacion,
//         contentType: false,
//         processData: false,
//         success: function (datos) {
//             console.log(datos);
//             enviarEmailAsignacion(cua_id); // Enviar email tras la asignación

//             swal("Correcto!", "Asignado Correctamente", "success");
//             $("#modalasignar").modal('hide');
//             tabla.ajax.reload();
//         }
//     });
// }

// Función para enviar el correo tras la asignación
// function enviarEmailAsignacion(cua_id) {
//     $.post("../../controller/email.php?op=ticket_asignado", { tick_id: cua_id }, function (data) {
//         console.log("Email enviado:", data);
//     });
// }

// Función para editar un registro de cuadrilla
// function editar(cua_id) {
//     $('#mdltitulo').html('Editar Registro');
//     $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
//         data = JSON.parse(data);
//         $('#cua_id').val(data.cua_id);
//         $('#cua_nombre').val(data.cua_nombre);
//     });
//     $('#modalmantenimiento').modal('show');
// }

// Función para asignar un colaborador a una cuadrilla
// function asignar(cua_id) {
//     $.post("../../controller/cuadrilla.php?op=mostrar", { cua_id: cua_id }, function (data) {
//         data = JSON.parse(data);
//         $('#cua_id').val(data.cua_id);
//         $('#mdltitulo').html('Asignar Colaboradores');
//         $("#modalasignar").modal('show');
//     });
// }

// Función para eliminar un registro de cuadrilla
// function eliminar(cua_id) {
//     swal({
//         title: "HelpDesk",
//         text: "¿Está seguro de eliminar el registro?",
//         icon: "error",
//         buttons: {
//             confirm: { text: "Sí", className: "btn-danger" },
//             cancel: "No"
//         }
//     }).then((isConfirm) => {
//         if (isConfirm) {
//             $.post("../../controller/cuadrilla.php?op=eliminar", { cua_id: cua_id }, function (data) {
//                 tabla.ajax.reload();
//                 swal("HelpDesk!", "Registro Eliminado.", "success");
//             });
//         }
//     });
// }

// Evento para abrir el modal de nuevo registro
// $(document).on("click", "#btnnuevo", function () {
//     $('#mdltitulo').html('Nuevo Registro');
//     $('#cuadrilla_form')[0].reset();
//     $('#modalmantenimiento').modal('show');
// });



