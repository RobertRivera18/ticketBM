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
      
function asignar() {
        $("#modalasignar").modal('show');
}




