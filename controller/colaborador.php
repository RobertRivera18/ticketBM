<?php
    require_once("../config/conexion.php");
    require_once("../models/Colaborador.php");
    $colaborador = new Colaborador();

    switch($_GET["op"]){
        case "guardaryeditar":
            if(empty($_POST["cua_id"])){       
                $colaborador->insert_colaborador($_POST["col_nombre"],$_POST["col_apellido"]);     
            }
            else {
                $colaborador->update_colaborador($_POST["col_id"],$_POST["col_nombre"],$_POST["col_apellido"]);
            }
            break;

        case "listar":
            $datos=$colaborador->get_colaborador();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["col_nombre"] . " " . $row["col_apellido"];

                $sub_array[] = '<button type="button" onClick="editar('.$row["col_id"].');"  id="'.$row["col_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["col_id"].');"  id="'.$row["col_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        case "eliminar":
            $colaborador->delete_colaborador($_POST["col_id"]);
            break;

        case "mostrar";
            $datos=$colaborador->get_colaborador_x_id($_POST["col_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["col_id"] = $row["col_id"];
                    $output["col_nombre"] = $row["col_nombre"];
                    $output["col_apellido"] = $row["col_apellido"];
                }
                echo json_encode($output);
            }   
            break;
/* 
        case "total";
            $datos=$usuario->get_usuario_total_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        case "totalabierto";
            $datos=$usuario->get_usuario_totalabierto_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        case "totalcerrado";
            $datos=$usuario->get_usuario_totalcerrado_x_id($_POST["usu_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row)
                {
                    $output["TOTAL"] = $row["TOTAL"];
                }
                echo json_encode($output);
            }
            break;

        case "grafico";
            $datos=$usuario->get_usuario_grafico($_POST["usu_id"]);  
            echo json_encode($datos);
            break;
*/
        // case "combo";
        //     $datos = $cuadrilla->get_colaboradores();
        //     if(is_array($datos)==true and count($datos)>0){
        //         $html.= "<option label='Seleccionar'></option>";
        //         foreach($datos as $row)
        //         {
        //             $html.= "<option value='".$row['col_id']."'>".$row['col_nombre']."</option>";
        //         }
        //         echo $html;
        //     }
        //     break;
        /* Controller para actualizar contraseña */
        /* case "password":
            $usuario->update_usuario_pass($_POST["usu_id"],$_POST["usu_pass"]);
            break; */

    }
?>