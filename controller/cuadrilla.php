<?php
    require_once("../config/conexion.php");
    require_once("../models/Cuadrilla.php");
    $cuadrilla = new Cuadrilla();

    switch($_GET["op"]){
        case "guardaryeditar":
            // Verificar que los datos estén siendo enviados correctamente
            error_log("cua_nombre: " . $_POST["cua_nombre"]);
            error_log("cua_id: " . $_POST["cua_id"]);
        
            if(empty($_POST["cua_id"])){
                // Insertar nueva cuadrilla
                $cua_id = $cuadrilla->insert_cuadrilla($_POST["cua_nombre"]);
                
                if ($cua_id) {
                    error_log("ID de cuadrilla insertada: " . $cua_id); // Verificar ID insertado
                    echo json_encode(['status' => 'success', 'cua_id' => $cua_id]); // Respuesta exitosa
                } else {
                    error_log("Error al insertar cuadrilla.");
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo insertar la cuadrilla']);
                }
            } else {
                // Actualizar cuadrilla
                $resultado = $cuadrilla->update_cuadrilla($_POST["cua_id"], $_POST["cua_nombre"]);
                if ($resultado) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la cuadrilla']);
                }
            }
            break;
        

         case "listar":
        // Obtener todas las cuadrillas
        $datos = $cuadrilla->get_cuadrilla();
        $data = Array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["cua_nombre"];
            
            // Obtener los colaboradores de esta cuadrilla
            $colaboradores = $cuadrilla->get_colaboradores_por_cuadrilla($row["cua_id"]);
            $colaboradores_array = array();
            if (is_array($colaboradores) && count($colaboradores) > 0) {
                foreach ($colaboradores as $colaborador) {
                    $colaboradores_array[] = $colaborador["col_nombre"] . ' ' . $colaborador["col_apellido"];
                }
                $sub_array[] = implode(", ", $colaboradores_array); // Mostrar colaboradores como lista separada por comas
            } else {
                    $sub_array[] = '<a onClick="asignar('.$row["cua_id"].');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
            }

            // Botones para editar y eliminar
            $sub_array[] = '<button type="button" onClick="editar(' . $row["cua_id"] . ');"  id="' . $row["cua_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["cua_id"] . ');"  id="' . $row["cua_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
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
            $datos=$cuadrilla->get_cuadrilla_x_id($_POST["cua_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["cua_id"] = $row["cua_id"];
                    $output["cua_nombre"] = $row["cua_nombre"];
                }
                echo json_encode($output);
            }   
            break;


            case "asignar":
                if (isset($_POST["cua_id"]) && isset($_POST["col_id"])) {
                    // Verificar los valores recibidos
                    error_log("cua_id: " . $_POST["cua_id"]);
                    error_log("col_id: " . $_POST["col_id"]);
                    
                    // Llamar al método de asignación
                    $cuadrilla->update_cuadrilla_asignacion($_POST["cua_id"], $_POST["col_id"]);
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros']);
                }
                break;
    }
?>