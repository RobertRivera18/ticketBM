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

            // Si la inserción de la inspección fue exitosa, insertamos los equipos
            if ($inspeccion_id) {
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
                    $sub_array[] = '<span class="label label-info">Trabajo de Mantenimiento</span>';
                    break;
                case 3:
                    $sub_array[] = '<span class="label label-warning">Trabajo de Reparación</span>';
                    break;
                default:
                    $sub_array[] = '<span class="label label-default">Trabajo No Especificado</span>';
                    break;
            }
            $sub_array[] = $row["numero_orden"];
            $sub_array[] = $row["ubicacion"];

            $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha"]));

            $sub_array[] = $row["col_nombre"];


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
        $equipo->delete_equipo($_POST["equipo_id"]);
        break;

    case "mostrar":
        $datos = $inspeccion->get_inspeccion_x_id($_POST["inspeccion_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["inspeccion_id"] = $row["inspeccion_id"];
                $output["trabajo"] = $row["trabajo"];
                $output["ubicacion"] = $row["ubicacion"];
                $output["numero_orden"] = $row["numero_orden"];
                $output["fecha"] = $row["fecha"];
                $output["col_nombre"] = $row["col_nombre"];
                $output["botas"] = $row["botas"];
                $output["chaleco"] = $row["chaleco"];
                $output["proteccion_auditiva"] = $row["proteccion_auditiva"];
                $output["proteccion_visual"] = $row["proteccion_visual"];
                $output["linea_vida"] = $row["linea_vida"];
                $output["arnes"] = $row["arnes"];
                $output["otros_equipos"] = $row["otros_equipos"];
           

            }
            echo json_encode($output);
        } 
        break;


    case "combo":
        $datos = $equipo->get_equipos_disponibles();
        $data = array();
        if (is_array($datos) && count($datos) > 0) {
            foreach ($datos as $row) {
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="asignarEquipo(' . $row['equipo_id'] . ')"><span class="fa fa-plus"></span></button>',
                    "1" => $row['nombre_equipo'],
                    "2" => $row['marca'],
                    "3" => $row['modelo'],
                    "4" => $row['serie'],

                );
            }
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

        //Combo para equipos disponibles para usuarios(Administrativos)

    case "comboEquipos":
        $datos = $equipo->get_equipos_disponibles_usuarios();
        $data = array();
        if (is_array($datos) && count($datos) > 0) {
            foreach ($datos as $row) {
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="asignarEquipo(' . $row['equipo_id'] . ')"><span class="fa fa-plus"></span></button>',
                    "1" => $row['nombre_equipo'],
                    "2" => $row['marca'],
                    "3" => $row['modelo'],
                    "4" => $row['serie'],

                );
            }
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
