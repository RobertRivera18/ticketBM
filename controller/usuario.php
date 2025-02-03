<?php
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
$usuario = new Usuario();

switch ($_GET["op"]) {
    case "guardaryeditar":
        if (empty($_POST["usu_id"])) {
            $usuario->insert_usuario($_POST["usu_nom"], $_POST["usu_ape"], $_POST["usu_cedula"], $_POST["usu_correo"], $_POST["usu_pass"], $_POST["rol_id"], $_POST["empresa_id"]);
        } else {
            $usuario->update_usuario($_POST["usu_id"], $_POST["usu_nom"], $_POST["usu_ape"], $_POST["usu_cedula"], $_POST["usu_correo"], $_POST["usu_pass"], $_POST["rol_id"], $_POST["empresa_id"]);
        }
        break;

    case "listar":
        $datos = $usuario->get_usuario();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["usu_nom"];
            $sub_array[] = $row["usu_ape"];
            $sub_array[] = $row["usu_cedula"];
            $sub_array[] = $row["usu_correo"];
            $sub_array[] = $row["usu_pass"];

            $role_label = '';
            $empresa_label = '';

            // Verificamos el rol y asignamos la etiqueta correspondiente
            switch ($row["rol_id"]) {
                case "1":
                    $role_label = '<span class="label label-pill label-success">Usuario</span>';
                    break;
                case "2":
                    $role_label = '<span class="label label-pill label-info">Soporte</span>';
                    break;
                case "3":
                    $role_label = '<span class="label label-pill label-warning">Operador</span>';
                    break;
                default:
                    // Si el rol no está en los valores esperados, no asignamos ninguna etiqueta
                    $role_label = '';
                    break;
            }

            // Agregamos la etiqueta de "Administrativo" para roles 1 o 2
            if (in_array($row["rol_id"], ["1", "2"])) {
                $empresa_label = '<span class="label label-pill label-success">Administrativo</span>';
            }

            // Para el rol "Operador" (rol_id = 3), verificamos el valor de empresa_id
            if ($row["rol_id"] == "3") {
                switch ($row["empresa_id"]) {
                    case "1":
                        $empresa_label = '<span class="label label-pill label-success">Claro</span>';
                        break;
                    case "2":
                        $empresa_label = '<span class="label label-pill label-success">CNEL</span>';
                        break;
                    default:
                        $empresa_label = '<span class="label label-pill label-warning">Administrativo</span>';
                        break;
                }
            }
            if ($row["rol_id"] == "4") {
                $empresa_label = '<span class="label label-pill label-success">Monitor</span>';
            }

            // Agregamos las etiquetas al array final
            $sub_array[] = $role_label;
            $sub_array[] = $empresa_label;

            $sub_array[] = '<button type="button" onClick="editar(' . $row["usu_id"] . ');"  id="' . $row["usu_id"] . '" class="btn btn-inline btn-warning btn-sm ladda-button"><i class="fa fa-edit"></i></button>';
            $sub_array[] = '<button type="button" onClick="eliminar(' . $row["usu_id"] . ');"  id="' . $row["usu_id"] . '" class="btn btn-inline btn-danger btn-sm ladda-button"><i class="fa fa-trash"></i></button>';
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
        $usuario->delete_usuario($_POST["usu_id"]);
        break;

    case "mostrar";
        $datos = $usuario->get_usuario_x_id($_POST["usu_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["usu_id"] = $row["usu_id"];
                $output["usu_nom"] = $row["usu_nom"];
                $output["usu_ape"] = $row["usu_ape"];
                $output["usu_cedula"] = $row["usu_cedula"];
                $output["usu_correo"] = $row["usu_correo"];
                $output["usu_pass"] = $row["usu_pass"];
                $output["rol_id"] = $row["rol_id"];
                $output["empresa_id"] = $row["empresa_id"];
            }
            echo json_encode($output);
        }
        break;

    case "total";
        $datos = $usuario->get_usuario_total_x_id($_POST["usu_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
        }
        break;

    case "totalabierto";
        $datos = $usuario->get_usuario_totalabierto_x_id($_POST["usu_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
        }
        break;

    case "totalcerrado";
        $datos = $usuario->get_usuario_totalcerrado_x_id($_POST["usu_id"]);
        if (is_array($datos) == true and count($datos) > 0) {
            foreach ($datos as $row) {
                $output["TOTAL"] = $row["TOTAL"];
            }
            echo json_encode($output);
        }
        break;

    case "grafico";
        $datos = $usuario->get_usuario_grafico($_POST["usu_id"]);
        echo json_encode($datos);
        break;

    case "combo";
        $datos = $usuario->get_usuario_x_rol();
        if (is_array($datos) == true and count($datos) > 0) {
            $html .= "<option label='Seleccionar'></option>";
            foreach ($datos as $row) {
                $html .= "<option value='" . $row['usu_id'] . "'>" . $row['usu_nom'] . "</option>";
            }
            echo $html;
        }
        break;
        /* Controller para actualizar contraseña */
    case "password":
        $usuario->update_usuario_pass($_POST["usu_id"], $_POST["usu_pass"]);
        break;
}
