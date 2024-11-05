<?php
    require_once("../config/conexion.php");
    require_once("../models/Cuadrilla.php");
    $cuadrilla = new Cuadrilla();

    switch($_GET["op"]){
        case "guardaryeditar":
            if(empty($_POST["cua_id"])){       
                $cuadrilla->insert_cuadrilla($_POST["cua_nombre"]);     
            }
            else {
                $cuadrilla->update_cuadrilla($_POST["cua_id"],$_POST["cua_nombre"]);
            }
            break;

        case "listar":
            $datos=$cuadrilla->get_cuadrilla();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["cua_nombre"];

                $sub_array[] = '<button type="button" onClick="editar('.$row["cua_id"].');"  id="'.$row["cua_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["cua_id"].');"  id="'.$row["cua_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
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

        case "combo";
            $datos = $usuario->get_usuario_x_rol();
            if(is_array($datos)==true and count($datos)>0){
                $html.= "<option label='Seleccionar'></option>";
                foreach($datos as $row)
                {
                    $html.= "<option value='".$row['usu_id']."'>".$row['usu_nom']."</option>";
                }
                echo $html;
            }
            break;
        /* Controller para actualizar contraseña */
        /* case "password":
            $usuario->update_usuario_pass($_POST["usu_id"],$_POST["usu_pass"]);
            break; */

    }
?>