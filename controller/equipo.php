<?php
require_once("../config/conexion.php");
require_once("../models/Equipo.php");
$equipo = new Equipo();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["equipo_id"])) {
            $equipo->insert_equipo($_POST["nombre_equipo"], $_POST["marca"],$_POST["modelo"], $_POST["serie"]);
        } else {
            $equipo->update_equipo($_POST["equipo_id"], $_POST["nombre_equipo"], $_POST["marca"],$_POST["modelo"], $_POST["serie"]);
        }
        break;

    case "listar":
        $datos = $equipo->get_equipo();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre_equipo"];
            $sub_array[] = $row["marca"];
            $sub_array[] = $row["modelo"];
            $sub_array[] = $row["serie"];
            $sub_array[] = '<td class="text-center" colspan="2">
            <div style="display: flex; justify-content: center; gap: 5px;">
                <button type="button" onClick="editar(' . $row["equipo_id"] . ');" id="' . $row["equipo_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" onClick="eliminar(' . $row["equipo_id"] . ');" id="' . $row["equipo_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button">
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
