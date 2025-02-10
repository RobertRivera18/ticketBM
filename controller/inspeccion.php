<?php
require_once("../config/conexion.php");
require_once("../models/Inspeccion.php");
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
$inspeccion = new Inspeccion();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["inspeccion_id"])) {
            // Primero insertamos la inspección principal y obtenemos su ID
            $inspeccion_id = $inspeccion->insert_inspeccion(
                $_POST["trabajo"],
                $_POST["ubicacion"],
                $_POST["numero_orden"],
                $_POST["col_id"],
                $_POST["zona_resbaladiza"],
                $_POST["zona_con_desnivel"],
                $_POST["hueco_piso_danado"],
                $_POST["instalacion_mal_estado"],
                $_POST["desconectados_expuestos"],
                $_POST["escalera_buen_estado"],
                $_POST["senaletica_instalada"]
            );

            // Si la inserción de la inspección fue exitosa
            if ($inspeccion_id) {
                // Manejo de la imagen
                $ruta_imagen = null;
                if (!empty($_FILES['imagen']['name'])) {
                    $directorio = "../public/uploads/inspecciones/";
                    if (!is_dir($directorio)) {
                        mkdir($directorio, 0777, true);
                    }

                    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                    $nombre_archivo = "inspeccion_" . $inspeccion_id . "." . $extension;
                    $ruta_imagen = $directorio . $nombre_archivo;

                    // Mover el archivo a la carpeta destino
                    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
                        $ruta_imagen = null;
                    }
                }

                // Guardar la ruta de la imagen en la base de datos
                $inspeccion->guardar_imagen_inspeccion($inspeccion_id, $ruta_imagen);

                // Preparamos los datos de equipos de seguridad
                $equipos_data = array(
                    'inspeccion_id' => $inspeccion_id,
                    'botas' => isset($_POST['botas']) ? 'SI' : 'N/A',
                    'chaleco' => isset($_POST['chaleco']) ? 'SI' : 'N/A',
                    'proteccion_auditiva' => isset($_POST['proteccion_auditiva']) ? 'SI' : 'N/A',
                    'proteccion_visual' => isset($_POST['proteccion_visual']) ? 'SI' : 'N/A',
                    'linea_vida' => isset($_POST['linea_vida']) ? 'SI' : 'N/A',
                    'arnes' => isset($_POST['arnes']) ? 'SI' : 'N/A',
                    'otros_equipos' => $_POST['otros_equipos'] ?? null
                );
            }
        }
        break;




    case "listar":
        $datos = $inspeccion->get_inspecciones();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            switch ($row["trabajo"]) {
                case 1:
                    $sub_array[] = '<span class="label label-success">Trabajo de Instalación</span>';
                    break;
                case 2:
                    $sub_array[] = '<span class="label label-info">Trabajo de Garantia</span>';
                    break;
                case 3:
                    $sub_array[] = '<span class="label label-warning">Trabajo de Mantenimiento</span>';
                    break;
                default:
            }
            $sub_array[] = $row["numero_orden"];
            $sub_array[] = $row["ubicacion"];

            $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha"]));

            $sub_array[] = $row["col_nombre"];
            $sub_array[] = $row["aprobacion"];
            $sub_array[] = '<button type="button" onClick="generar(' . $row["inspeccion_id"] . ');" 
            id="' . $row["inspeccion_id"] . '" 
            class="btn btn-inline btn-success btn-sm ladda-button">
            <i class="fa fa-print"></i>
        </button>';


            $sub_array[] = '<td class="text-center" colspan="2">
            <div style="display: flex; justify-content: center; gap: 5px;">
                <button type="button" onClick="ver(' . $row["inspeccion_id"] . ');" id="' . $row["inspeccion_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button">
                    <i class="fa fa-eye"></i>
                </button>
                <button type="button" onClick="eliminar(' . $row["inspeccion_id"] . ');" id="' . $row["inspeccion_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </td>';


            $data[] = $sub_array;
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case "eliminar":
        $inspeccion->delete_inspeccion($_POST["inspeccion_id"]);
        break;

    case 'mostrar':
        $inspeccion_id = $_POST['inspeccion_id'];
        $inspeccion = new Inspeccion();
        $data = $inspeccion->get_inspeccion_x_id($inspeccion_id);
        echo json_encode($data);
        break;


    case "aprobar":
        $inspeccion_id = isset($_POST["inspeccion_id"]) ? intval($_POST["inspeccion_id"]) : 0;
        $result = $inspeccion->update_inspeccion_status($inspeccion_id, 'aprobado');
        echo json_encode(['success' => $result]);
        break;

    case 'rechazar':
        $inspeccion_id = $_POST['inspeccion_id'];
        $motivo_rechazo = $_POST['motivo_rechazo'];
        $result = $inspeccion->reject_inspeccion($inspeccion_id, $motivo_rechazo);
        echo json_encode(['success' => $result]);
        break;


    case "generar_word":
        if (isset($_GET["inspeccion_id"])) {
            $inspeccion_id = $_GET["inspeccion_id"];
            $datos = $inspeccion->generarWordInspeccion($inspeccion_id);

            if ($datos) {
                $TBS = new clsTinyButStrong;
                $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

                $template = '../public/templates/plantilla_inspeccion.docx';
                $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

                // Asignar datos al template
                $tipo_trabajo = '';
                switch ($datos['trabajo']) {
                    case 1:
                        $tipo_trabajo = 'Instalación';
                        break;
                    case 2:
                        $tipo_trabajo = 'Garantía';
                        break;
                    case 3:
                        $tipo_trabajo = 'Mantenimiento';
                        break;
                    default:
                        $tipo_trabajo = 'Otro';
                        break;
                }

                $TBS->MergeField('trabajo', $tipo_trabajo);
                $TBS->MergeField('trabajo', $datos['trabajo']);
                $TBS->MergeField('ubicacion', $datos['ubicacion']);
                $TBS->MergeField('numero_orden', $datos['numero_orden']);
                $TBS->MergeField('fecha', $datos['fecha']);
                $TBS->MergeField('solicitante', $datos['solicitante']);
                $TBS->MergeField('zona_resbaladiza', $datos['zona_resbaladiza']);
                $TBS->MergeField('zona_con_desnivel', $datos['zona_con_desnivel']);
                $TBS->MergeField('hueco_piso_danado', $datos['hueco_piso_danado']);
                $TBS->MergeField('instalacion_mal_estado', $datos['instalacion_mal_estado']);
                $TBS->MergeField('cables_desconectados_expuestos', $datos['cables_desconectados_expuestos']);
                $TBS->MergeField('escalera_buen_estado', $datos['escalera_buen_estado']);
                $TBS->MergeField('senaletica_instalada', $datos['senaletica_instalada']);
                $TBS->MergeField('aprobacion', $datos['aprobacion']);
                $TBS->MergeField('botas', $datos['botas']);
                $TBS->MergeField('chaleco', $datos['chaleco']);
                $TBS->MergeField('proteccion_auditiva', $datos['proteccion_auditiva']);
                $TBS->MergeField('proteccion_visual', $datos['proteccion_visual']);
                $TBS->MergeField('linea_vida', $datos['linea_vida']);
                $TBS->MergeField('arnes', $datos['arnes']);
                $TBS->MergeField('otros_equipos', $datos['otros_equipos']);

                // Nombre del archivo de salida
                $output_file_name = 'Inspeccion_' . $inspeccion_id . '.docx';

                // Descargar el archivo
                $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name);
            } else {
                echo "No se encontraron datos para la inspección.";
            }
        }
        break;
}
