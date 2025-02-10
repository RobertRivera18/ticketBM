function init() {

}


var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};
$(document).ready(function () {
    var inspeccion_id = getUrlParameter('ID');
    console.log("ID obtenido de la URL:", inspeccion_id); // Verifica el ID en consola
    listardetalle(inspeccion_id);
});


const styles = `
<style>
.checkbox-toggle input[type="checkbox"]:checked + label {
    background-color: #36a9e1 !important;
    color: white !important;
    font-weight: bold;
}

.checkbox-toggle input[type="checkbox"]:checked + label:before {
    background-color: #fff !important;
}

.checkbox-toggle input[type="checkbox"]:checked + label:after {
    background-color: #fff !important;
}
</style>
`;

function listardetalle(inspeccion_id) {
    $('#spinner').show();
    $.post("../../controller/inspeccion.php?op=mostrar", { inspeccion_id: inspeccion_id }, function (data) {
        try {
            $('#spinner').hide();

            if (typeof data === "string") {
                data = JSON.parse(data); // Parseo de la respuesta en caso de que sea una cadena
            }

            $('#lblidinspeccion').html("Detalle Inspección - " + data.inspeccion_id);
            $('#col_nombre').val(data.col_nombre);
            $('#trabajo').val(data.trabajo).trigger('change');
            $('#ubicacion').val(data.ubicacion);
            $('#numero_orden').val(data.numero_orden);
            $('#fecha').val(data.fecha);

            $('#zona_resbaladiza').prop('checked', data.zona_resbaladiza === 'SI').prop('disabled', data.zona_resbaladiza !== 'SI');
            $('#zona_con_desnivel').prop('checked', data.zona_con_desnivel === 'SI').prop('disabled', data.zona_con_desnivel !== 'SI');
            $('#hueco_piso_danado').prop('checked', data.hueco_piso_danado === 'SI').prop('disabled', data.hueco_piso_danado !== 'SI');
            $('#instalacion_mal_estado').prop('checked', data.instalacion_mal_estado === 'SI').prop('disabled', data.instalacion_mal_estado !== 'SI');
            $('#cables_desconectados_expuestos').prop('checked', data.cables_desconectados_expuestos === 'SI').prop('disabled', data.cables_desconectados_expuestos !== 'SI');
            $('#escalera_buen_estado').prop('checked', data.escalera_buen_estado === 'SI').prop('disabled', data.escalera_buen_estado !== 'SI');
            $('#senaletica_instalada').prop('checked', data.senaletica_instalada === 'SI').prop('disabled', data.senaletica_instalada !== 'SI');

            $('#botas').prop('checked', data.botas === 'SI').prop('disabled', data.botas !== 'SI');
            $('#chaleco').prop('checked', data.chaleco === 'SI').prop('disabled', data.chaleco !== 'SI');
            $('#proteccion_auditiva').prop('checked', data.proteccion_auditiva === 'SI').prop('disabled', data.proteccion_auditiva !== 'SI');
            $('#proteccion_visual').prop('checked', data.proteccion_visual === 'SI').prop('disabled', data.proteccion_visual !== 'SI');
            $('#linea_vida').prop('checked', data.linea_vida === 'SI').prop('disabled', data.linea_vida !== 'SI');
            $('#arnes').prop('checked', data.arnes === 'SI').prop('disabled', data.arnes !== 'SI');
            $('#otros_equipos').val(data.otros_equipos);
            $('#estado_inspeccion').val(data.aprobacion);

            if (data.aprobacion === 'rechazado') {
                $('#btn-rechazar').hide();
                $('#btn-aprobar').hide();

            } else if (data.aprobacion === 'aprobado') {
                $('#btn-aprobar').hide();
                $('#btn-rechazar').hide();
                $('#container-rechazo').hide();
            } else {
                $('#btn-aprobar').show();
                $('#btn-rechazar').show();
                $('#container-rechazo').hide();
            }


            $('#motivo_rechazo').val(data.motivo_rechazo);

            if (data.imagen) {
                var qrPath = "../" + data.imagen;
                $('#imagen_inspeccion').attr("src", qrPath);
                console.log("URL de la imagen:", qrPath);

                var img = new Image(); // Crear una nueva instancia de Image
                img.onerror = function () {
                    console.error("Error al cargar la imagen");
                    $('#imagen_inspeccion').hide();
                    // Opcional: Mostrar una imagen por defecto
                    // $('#imagen_inspeccion').attr('src', '../../assets/img/no-image.png').show();
                };
                img.src = qrPath;
            } else {
                $('#imagen_inspeccion').hide();
            }

            console.log("Datos recibidos:", data);

            console.log("Datos recibidos:", data);

        } catch (error) {
            console.error("Error al parsear JSON:", error);
            alert("Hubo un error al obtener los datos.");
        }
    });
}
//Aprobar una inspeccion
$('#btn-aprobar').click(function () {
    var inspeccionId = $('#lblidinspeccion').text().split('-')[1].trim();

    $.ajax({
        type: 'POST',
        url: "../../controller/inspeccion.php?op=aprobar",
        data: {
            inspeccion_id: inspeccionId
        },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                $('#estado_inspeccion').val('Aprobado');
                setTimeout(function () {
                    location.reload();
                }, 500);
            } else {
                alert('Error al aprobar la inspección');
            }
        },
        error: function () {
            alert('Error al comunicarse con el servidor');
        }
    });
});


$('#btn-rechazar').click(function () {
    $('#motivo-rechazo-container').show(); // Mostrar el contenedor del motivo
});

$('#btn-confirmar-rechazo').click(function () {
    var inspeccionId = $('#lblidinspeccion').text().split('-')[1].trim();
    var motivoRechazo = $('#motivo-rechazo').val();

    // Validar que el motivo no esté vacío
    if (motivoRechazo.trim() === "") {
        alert("Por favor, ingrese el motivo del rechazo.");
        return;
    }

    $.ajax({
        type: 'POST',
        url: "../../controller/inspeccion.php?op=rechazar",
        data: {
            inspeccion_id: inspeccionId,
            motivo_rechazo: motivoRechazo
        },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.success) {
                swal({
                    title: "Error",
                    text: "Se rehazo la inspeccion de forma correcta.",
                    type: "error",
                    confirmButtonClass: "btn-danger"
                });
                location.reload();
            } else {
                alert('Error al rechazar la inspección');
            }
        },
        error: function () {
            alert('Error al comunicarse con el servidor');
        }
    });
});


init();
