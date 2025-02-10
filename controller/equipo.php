<?php
require_once("../config/conexion.php");
require_once("../models/Equipo.php");
require_once("../public/barcode/barcode.php");
$equipo = new Equipo();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["equipo_id"])) {
            $equipo->insert_equipo($_POST["nombre_equipo"], $_POST["marca"], $_POST["modelo"], $_POST["serie"], $_POST["datos"]);
        } else {
            $equipo->update_equipo($_POST["equipo_id"], $_POST["nombre_equipo"], $_POST["marca"], $_POST["modelo"], $_POST["serie"], $_POST["datos"]);
        }
        break;

    case "listar":
        $datos = $equipo->get_equipo_con_asignacion();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["nombre_equipo"];
            $sub_array[] = $row["marca"];
            $sub_array[] = $row["modelo"];
            $sub_array[] = $row["serie"];

            // Verifica si el equipo está asignado a un usuario
            if (!empty($row["nombre_usuario"])) {
                $sub_array[] = '<span class="label label-danger">Equipo Asignado</span>' .
                    '<span class="label label-warning">' . $row["nombre_usuario"] . '</span>';
            }
            // Verifica si el equipo está asignado a una cuadrilla
            elseif (!empty($row["cuadrilla_asignada"])) {
                $sub_array[] = '<span class="label label-danger">Equipo Asignado</span>' .
                    '<span class="label label-info">' . $row["cuadrilla_asignada"] . '</span>';
            }
            // Si no está asignado a nadie
            else {
                $sub_array[] = '<span class="label label-success">Equipo Libre</span>';
            }

            // Verifica si es un equipo informático (datos = 2)
            if (isset($row["datos"]) && $row["datos"] == 2) {
                $sub_array[] = '<span class="badge badge-success">Informático</span>';
            }
            // Si es un equipo no informático (datos = 1)
            elseif (isset($row["datos"]) && $row["datos"] == 1) {
                $sub_array[] = '<span class="badge badge-danger">No Informático</span>';
            }

            // Agrega botones para editar y eliminar
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

            // Agrega el arreglo de resultados
            $data[] = $sub_array;
        }

        // Respuesta en formato JSON
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        // Enviar respuesta al cliente
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
