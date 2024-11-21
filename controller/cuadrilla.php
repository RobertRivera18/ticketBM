<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla.php");
$cuadrilla = new Cuadrilla();

switch ($_GET["op"]) {
    case "guardar":
        $cuadrilla->insert_cuadrilla($_POST["cua_nom"]);
        break;

    case "listar":
        $datos = $cuadrilla->get_cuadrilla(); // Obtener todas las cuadrillas
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();

            // Nombre de la cuadrilla
            $sub_array[] = $row["cua_nombre"];

            // Obtener información adicional de la cuadrilla, incluida la empresa y la ciudad
            $empresa_info = $cuadrilla->get_empresa_cuadrilla($row["cua_id"]);
            if (is_array($empresa_info) && count($empresa_info) > 0) {
                $empresa_id = $empresa_info[0]["cua_empresa"];
                $ciudad_id = $empresa_info[0]["cua_ciudad"];

                // Empresa (Claro/CNEL/No definido)
                $empresa_nombre = ($empresa_id == 1) ? "CLARO" : (($empresa_id == 2) ? "CNEL" : "No definido");

                // Ciudad (Guayaquil/Quito)

                $ciudad_nombre = ($ciudad_id == 1) ? "Guayaquil" : (($ciudad_id == 2) ? "Quito" : "No definido");
                // Concatenar empresa y ciudad
                $sub_array[] = '<span class="label label-danger">' . $empresa_nombre . '</span><br>' .
                    '<span class="label label-info">' . $ciudad_nombre . '</span>';
            } else {
                $sub_array[] = '<span>No definido</span><br><span>No definida</span>';
            }

            // Manejo de colaboradores
            $colaboradores = $cuadrilla->get_colaboradores_por_cuadrilla($row["cua_id"]);
            $cantidad_colaboradores = is_array($colaboradores) ? count($colaboradores) : 0;

            if ($cantidad_colaboradores > 0) {
                $colaboradores_array = array_map(function ($colaborador) {
                    return $colaborador["col_nombre"];
                }, $colaboradores);

                $sub_array[] = implode("<br>", $colaboradores_array) .
                    '<br><a onClick="agregar(' . $row["cua_id"] . ')"><span class="label label-primary">Agregar más</span></a>';
                $sub_array[] = '<span class="label label-success">' . $cantidad_colaboradores . ' asignados</span>';
            } else {
                $sub_array[] = '<a onClick="agregar(' . $row["cua_id"] . ');"><span class="label label-pill label-warning">Sin Asignar</span></a>';
                $sub_array[] = '<span class="label label-danger">0 asignados</span>';
            }

            // Manejo de equipos
            $equipos = $cuadrilla->get_equipos_por_cuadrilla($row["cua_id"]);
            $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

            if ($cantidad_equipos > 0) {
                $equipos_array = array_map(function ($equipo) {
                    return '<li>' . $equipo["nombre_equipo"] . ' - ' . $equipo["serie"] . '</li>';
                }, $equipos);

                $sub_array[] = implode("<br>", $equipos_array) .
                    '<br><a onClick="agregarEquipo(' . $row["cua_id"] . ')"><span class="label label-primary">Agregar más</span></a>';
            } else {
                $sub_array[] = '<a onClick="agregarEquipo(' . $row["cua_id"] . ');"><span class="label label-pill label-warning">No se le han otorgado equipos</span></a>';
            }

            // Agregar fila al resultado final
            $data[] = $sub_array;
        }

        // Preparar los resultados en formato JSON
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

    case "asignarEquipo":
        $cuadrilla->insert_cuadrilla_equipos($_POST["cua_id"], $_POST["equipo_id"]);
        break;
}
