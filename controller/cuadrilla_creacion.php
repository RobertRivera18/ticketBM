<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla_creacion.php");
$cuadrilla = new Cuadrilla_creacion();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["cua_id"])) {
            $cuadrilla->insert_cuadrilla($_POST["cua_nombre"]);
        } else {
            $rows_affected = $cuadrilla->update_cuadrilla($_POST["cua_nombre"], $_POST["cua_id"]);
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
            $sub_array[] = $row["cua_nombre"];
            

            $sub_array[] = '<button type="button" onClick="editar(' . $row["cua_id"] . ');"  id="' . $row["cua_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["cua_id"] . ');"  id="' . $row["cua_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
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
        $cuadrilla->delete_cuadrilla($_POST["cua_id"]);
        break;

    case "mostrar":
        $datos = $cuadrilla->get_cuadrilla_x_id($_POST["cua_id"]);

        if (is_array($datos) && count($datos) > 0) {
            $output = array();
            foreach ($datos as $row) {
                $output["cua_id"] = $row["cua_id"];
                $output["cua_nombre"] = $row["cua_nombre"];
            }
            echo json_encode($output);
        } 
        break;
 
    
    
}
