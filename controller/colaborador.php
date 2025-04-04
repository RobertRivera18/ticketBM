<?php
require_once("../config/conexion.php");
require_once("../models/Colaborador.php");
$colaborador = new Colaborador();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["col_id"])) {
            $colaborador->insert_colaborador($_POST["col_nombre"], $_POST["col_cedula"], $_POST["empresa_id"]);
        } else {
            $colaborador->update_colaborador($_POST["col_id"], $_POST["col_nombre"], $_POST["col_cedula"], $_POST["empresa_id"]);
        }
        break;

    case "listar":
        $datos = $colaborador->get_colaborador();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["col_nombre"];
            $sub_array[] = $row["col_cedula"];
            // Verificamos el valor de 'empresa_id' y asignamos el texto correspondiente
            if ($row["empresa_id"] == 1) {
                $sub_array[] = '<span class="label label-pill label-info">CNEL</span>';
            } elseif ($row["empresa_id"] == 2) {
                $sub_array[] = '<span class="label label-pill label-danger">CLARO</span>';
            } else {
                $sub_array[] = '<span>No asignado</span>'; // En caso de que el 'empresa_id' no sea ni 1 ni 2
            }

            $sub_array[] = '<td class="text-center" colspan="2">
            <div style="display: flex; justify-content: center; gap: 5px;">
                <button type="button" onClick="editar(' . $row["col_id"] . ');" id="' . $row["col_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button">
                    <i class="fa fa-edit"></i>
                </button>
                <button type="button" onClick="eliminar(' . $row["col_id"] . ');" id="' . $row["col_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button">
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
        $colaborador->delete_colaborador($_POST["col_id"]);
        break;

    case "mostrar":
        $datos = $colaborador->get_colaborador_x_id($_POST["col_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["col_id"] = $row["col_id"];
                $output["col_nombre"] = $row["col_nombre"];
                $output["col_cedula"] = $row["col_cedula"];
                $output["empresa_id"] = $row["empresa_id"];
            }
            echo json_encode($output);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Colaborador no encontrado']);
        }
        break;

    case "combo":
        $datos = $colaborador->get_all_colaboradores();
        $data = array();
        if (is_array($datos) && count($datos) > 0) {
            foreach ($datos as $row) {
                $empresa = $row['empresa_id'] == 1 ? '<span class="label label-pill label-info">CNEL</span>' : ($row['empresa_id'] == 2 ? '<span class="label label-pill label-danger">CLARO</span>' : "Otro");

                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="asignar(' . $row['col_id'] . ', event)"><span class="fa fa-plus"></span></button>',
                    "1" => $row['col_nombre'],
                    "2" => $row['col_cedula'],
                    "3" => $empresa,
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


        case "comboOperadores":
            $datos = $colaborador->get_colaboradoresClaro();
            $data = array();
            if (is_array($datos) && count($datos) > 0) {
                foreach ($datos as $row) {
                    $empresa = $row['empresa_id'] == 1 ? '<span class="label label-pill label-info">CNEL</span>' : ($row['empresa_id'] == 2 ? '<span class="label label-pill label-danger">CLARO</span>' : "Otro");
    
                    $data[] = array(
                        "0" => '<button class="btn btn-warning" onclick="asignar(' . $row['col_id'] . ')"><span class="fa fa-plus"></span></button>',
                        "1" => $row['col_nombre'],
                        "2" => $row['col_cedula'],
                        "3" => $empresa,
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
