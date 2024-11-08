<?php
    require_once("../config/conexion.php");
    require_once("../models/Grupo.php");
    $grupo = new Grupo();

    switch($_GET["op"]){
        case "guardaryeditar":
            if(empty($_POST["grupo_id"])){       
                $grupo->insert_grupo($_POST["grupo_nombre"]);     
            }
            else {
                $grupo->update_grupo($_POST["grupo_id"],$_POST["grupo_nombre"]);
            }
            break;

        case "listar":
            $datos=$grupo->get_grupo();
            $data= Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["grupo_nombre"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["grupo_id"].');"  id="'.$row["grupo_id"].'" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["grupo_id"].');"  id="'.$row["grupo_id"].'" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
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


    }
?>