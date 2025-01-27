<?php
require_once("../config/conexion.php");
require_once("../models/Inspeccion.php");
$inspeccion = new Inspeccion();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["inspeccion_id"])) {
            $inspeccion->insert_inspeccion($_POST["trabajo"], $_POST["ubicacion"], $_POST["numero_orden"], $_POST["col_id"]);
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
            
            $sub_array[] = $row["ubicacion"];
            $sub_array[] = $row["numero_orden"];
            $sub_array[] = $row["fecha"];
            $sub_array[] = $row["col_nombre"];


            $sub_array[] = '<td class="text-center" colspan="2">
            <div style="display: flex; justify-content: center; gap: 5px;">
                <button type="button" onClick="editar(' . $row["inspeccion_id"] . ');" id="' . $row["inspeccion_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button">
                    <i class="fa fa-edit"></i>
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
        $datos = $equipo->get_equipo_x_id($_POST["equipo_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["equipo_id"] = $row["equipo_id"];
                $output["nombre_equipo"] = $row["nombre_equipo"];
                $output["marca"] = $row["marca"];
                $output["modelo"] = $row["modelo"];
                $output["serie"] = $row["serie"];
                $output["datos"] = $row["datos"];
            }
            echo json_encode($output);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Colaborador no encontrado']);
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
