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

            if (!empty($row["nombre_usuario"])) {

                $sub_array[] = '<span class="label label-danger">' . 'Equipo Asignado' . '</span>' . '<span class="label label-warning">' . $row["nombre_usuario"] . ' ' . $row["usu_ape"] . '</span>';
            } else {
                $sub_array[] =  '<span class="label label-success">Equipo Libre</span>';
            }
            if (isset($row["datos"]) && $row["datos"] == 1) {
                $sub_array[] = '<span class="badge badge-danger">Si</span>';
            } else {
                $sub_array[] = '<span class="badge badge-danger">No</span>';
            }

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



            if ($row['qr_codigo'] == '' || $row['qr_codigo'] == null) {

                $sub_array[] = '<button type="button" onClick="generarqr(' . $row["equipo_id"] . ');" id="' . $row["equipo_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button">
            <i class="fa fa-qrcode"></i>
        </button>';
            }else{
                $sub_array[] = '<button type="button" onClick="verqr(' . $row["equipo_id"] . ');" id="' . $row["equipo_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button">
                <i class="fa fa-eye"></i>
            </button>';
            }



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



        case "get_qr":
            if (isset($_POST["equipo_id"])) {
                $equipo_id = intval($_POST["equipo_id"]);
                $qr = $equipo->get_qr_equipo($equipo_id);
        
                // Verificar si el array está bien estructurado
                if ($qr && isset($qr["qr_codigo"]) && !empty($qr["qr_codigo"])) {
                    // Devolver solo el JSON sin advertencias
                    echo json_encode(["status" => "success", "qr_codigo" => trim($qr["qr_codigo"])]);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontró un código QR para este equipo."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "ID de equipo no proporcionado."]);
            }
            exit; // Asegúrate de que no haya más salida después del JSON
        

            

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


    case "generar_qr":
        if (!empty($_POST["equipo_id"])) {
            $equipo_id = $_POST["equipo_id"];

            $datos = $equipo->get_equipo_x_id($equipo_id);
            if (is_array($datos) && count($datos) > 0) {
                $equipo_info = $datos[0];

                $url_qr = "http://localhost/soporte/view/MntEquipos/equipo_info.php?equipo_id=" . $equipo_id;
                $generator = new barcode_generator();
                $svg = $generator->render_svg("qr", $url_qr, "");

                $qr_filename = "qr_" . $equipo_id . ".svg";
                $qr_filepath = "../public/qrcodes/" . $qr_filename;

                if (!file_exists("../public/qrcodes/")) {
                    mkdir("../public/qrcodes/", 0777, true);
                }

                file_put_contents($qr_filepath, $svg);

                if ($equipo->update_qr_equipo($equipo_id, $qr_filepath)) {
                    echo json_encode(["status" => "success", "message" => "QR generado correctamente", "qr_path" => $qr_filepath]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Error al actualizar en la base de datos"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Equipo no encontrado"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Falta el ID del equipo"]);
        }
        break;
}
