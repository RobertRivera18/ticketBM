<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla.php");
$cuadrilla = new Cuadrilla();

switch ($_GET["op"]) {
    case "guardar":
        $cuadrilla->insert_cuadrilla($_POST["cua_nom"]);
        break;


        case "listar":
            // Obtener todas las cuadrillas
            $datos = $cuadrilla->get_cuadrilla();
            $data = array();
        
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["cua_nombre"]; // Nombre de la cuadrilla
        
                // Obtener los colaboradores de esta cuadrilla
                $colaboradores = $cuadrilla->get_colaboradores_por_cuadrilla($row["cua_id"]);
                $colaboradores_array = array();
        
                if (is_array($colaboradores) && count($colaboradores) > 0) {
                    $cantidad_colaboradores = count($colaboradores); // Cantidad de colaboradores
        
                    if ($cantidad_colaboradores == 1) {
                        // Si hay solo un colaborador, mostrar "Falta por asignar"
                        $sub_array[] = '<a onClick="agregar(' . $row["cua_id"] . ');"><span class="label label-pill label-warning">Falta por asignar</span></a>';
                    } elseif ($cantidad_colaboradores > 1) {
                        // Si hay dos o más colaboradores, mostrar los nombres
                        foreach ($colaboradores as $colaborador) {
                            $colaboradores_array[] = $colaborador["col_nombre"];
                        }
                        $sub_array[] = implode(", ", $colaboradores_array);
                    }
        
                    // Mostrar la cantidad de colaboradores asignados
                    $sub_array[] = '<span class="label label-success">' . $cantidad_colaboradores . ' asignados</span>';
                } else {
                    // Si no hay ningún registro, mostrar "Sin Asignar"
                    $sub_array[] = '<a onClick="agregar(' . $row["cua_id"] . ');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
                    $sub_array[] = '<span class="label label-danger">0 asignados</span>';
                }
        
                // Botones para editar y eliminar
                $sub_array[] = '<button type="button" onClick="eliminar(' . $row["cua_id"] . ');" id="' . $row["cua_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
                $data[] = $sub_array;
            }
        
            // Respuesta en formato JSON
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

    case "mostrar";
        $datos = $cuadrilla->get_cuadrilla_x_id($_POST["cua_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["cua_id"] = $row["cua_id"];
                $output["cua_nombre"] = $row["cua_nombre"];
            }
            echo json_encode($output);
        }
        break;


    case "asignar":
        $cuadrilla->insert_cuadrilla_asignacion($_POST["cua_id"], $_POST["col_id"]);

        break;
}
