<?php
require_once("../config/conexion.php");
require_once("../models/Inspeccion.php");
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
                    $directorio = "public/uploads/inspecciones/";
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
}
