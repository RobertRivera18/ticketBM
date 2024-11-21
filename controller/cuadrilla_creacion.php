<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla_creacion.php");
$cuadrilla = new Cuadrilla_creacion();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["cua_id"])) {
            $cuadrilla->insert_cuadrilla($_POST["cua_nombre"], $_POST["cua_empresa"], $_POST["cua_ciudad"]);
        } else {
            $rows_affected = $cuadrilla->update_cuadrilla($_POST["cua_nombre"], $_POST["cua_empresa"], $_POST["cua_ciudad"], $_POST["cua_id"]);
            echo json_encode([
                "status" => $rows_affected > 0 ? "success" : "error",
                "message" => $rows_affected > 0 ? "Registro actualizado correctamente." : "No se pudo actualizar el registro."
            ]);
        }
        break;


    case "listar":
        $datos = $cuadrilla->get_cuadrillas();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();

            // Nombre de la cuadrilla junto con la ciudad
            $ciudad_nombre = ($row["cua_ciudad"] == 1) ? "Guayaquil" : (($row["cua_ciudad"] == 2) ? "Quito" : "No definido");
            $sub_array[] = $row["cua_nombre"] . ' - <span class="label label-primary">' . $ciudad_nombre . '</span>';

            // Empresa (formatear con span según valor)
            if ($row["cua_empresa"] == 1) {
                $sub_array[] = '<span class="label label-pill label-danger">Claro</span>';
            } elseif ($row["cua_empresa"] == 2) {
                $sub_array[] = '<span class="label label-pill label-info">CNEL</span>';
            } else {
                $sub_array[] = '<span>No definido</span>';
            }

            // Botones de acción
            $sub_array[] = '<button type="button" onClick="editar(' . $row["cua_id"] . ');" id="' . $row["cua_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["cua_id"] . ');" id="' . $row["cua_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';

            // Agregar fila al arreglo de datos
            $data[] = $sub_array;
        }

        // Preparar resultado final
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        // Devolver datos en formato JSON
        echo json_encode($results);
        break;

    case "eliminar":
        $cuadrilla->delete_cuadrilla($_POST["cua_id"]);
        break;

    case "mostrar":
        $datos = $cuadrilla->get_cuadrilla_x_id($_POST["cua_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["cua_id"] = $row["cua_id"];
                $output["cua_nombre"] = $row["cua_nombre"];
                $output["cua_empresa"] = $row["cua_empresa"];
                $output["cua_ciudad"] = $row["cua_ciudad"];
            }

            echo json_encode($output);
        }
        break;
}
