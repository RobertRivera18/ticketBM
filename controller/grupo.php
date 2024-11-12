<?php
    require_once("../config/conexion.php");
    require_once("../models/Grupo.php");
    $grupo = new Grupo();
    require_once("../models/Colaborador.php");
    $colaborador = new Colaborador();

    switch($_GET["op"]){
        case "guardaryeditar":
            if(empty($_POST["grupo_id"])){       
                $grupo->insert_grupo($_POST["grupo_nombre"]);     
            }
            else {
                $grupo->update_grupo($_POST["grupo_id"],$_POST["grupo_nombre"]);
            }
            break;

        // case "listar":
        //     $datos=$grupo->get_grupo();
        //     $data= Array();
        //     foreach($datos as $row){
        //         $sub_array = array();
        //         $sub_array[] = $row["grupo_nombre"];
                 
        //         if($row["col_id"]==null){       
        //             $sub_array[] = '<span class="label label-pill label-default">Sin Asignar</span>';     
        //         }else {
        //             $datos1=$colaborador->get_colaboradores();
        //             foreach($datos1 as $row1){
        //                 $sub_array[] = '<span class="label label-pill label-success">'. $row1["col_nombre"].'</span>';
        //             }
        //         }
        //         $sub_array[] = '<button type="button" onClick="editar('.$row["grupo_id"].');"  id="'.$row["grupo_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
        //         $sub_array[] = '<button type="button" onClick="eliminar('.$row["grupo_id"].');"  id="'.$row["grupo_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
        //         $data[] = $sub_array;

        //     }

        case "listar":
            // Obtener todas las cuadrillas
            $datos = $grupo->get_grupo();
            $data = Array();
            foreach ($datos as $row) {
                $sub_array = array();
                $sub_array[] = $row["grupo_nombre"];
                
                // Obtener los colaboradores de esta cuadrilla
                $colaboradores = $grupo->get_colaboradores_por_grupo($row["cua_id"]);
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

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        case "eliminar":
            $grupo->delete_grupo($_POST["grupo_id"]);
            break;

        case "mostrar";
            $datos=$grupo->get_grupo_x_id($_POST["grupo_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["grupo_id"] = $row["grupo_id"];
                    $output["grupo_nombre"] = $row["grupo_nombre"];
                }
                echo json_encode($output);
            }   
            break;
            
            case "combo";
            $datos = $colaborador->get_colaboradores();
            if(is_array($datos)==true and count($datos)>0){
                $html.= "<option label='Seleccionar'></option>";
                foreach($datos as $row)
                {
                    $html .= "<option value='" . $row['col_id'] . "'>" . $row['col_nombre'] . " " . $row['col_apellido'] . " - " . $row['col_cedula'] . "</option>";

                }
                echo $html;
            }
            break;

            //Version anterior con problemas al cargar colaboradores
            // case "asignar":
            //     $grupo->update_grupo_asignacion($_POST["grupo_id"],$_POST["col_id"]);
            // break;

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