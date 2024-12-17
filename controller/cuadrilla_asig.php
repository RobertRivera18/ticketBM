<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla_Chip.php");
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
$cuadrilla = new Cuadrilla_Chip();

switch ($_GET["op"]) {
    case "guardar":
        $cuadrilla->insert_cuadrilla($_POST["cua_nom"]);
        break;

    case "combo":
        $datos = $cuadrilla->get_chips_disponibles();
        $data = array();
        if (is_array($datos) && count($datos) > 0) {
            foreach ($datos as $row) {
                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="asignarEquipo(' . $row['equipo_id'] . ')"><span class="fa fa-plus"></span></button>',
                    "1" => $row['nombre_equipo'],
                    "2" => $row['marca'],
                    "3" => $row['serie'],
                );
            }
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
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
                $sub_array[] = '<a onClick="agregarEquipo(' . $row["cua_id"] . ');"><span class="label label-pill label-danger">Sin Chip</span></a>';
            }

            $sub_array[] = '<button type="button" onClick="generar(' . $row["cua_id"] . ');" 
            id="' . $row["cua_id"] . '" 
            class="btn btn-inline btn-success btn-sm ladda-button">
            <i class="fa fa-print"></i>
        </button>';


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

    case "generar_word":
        $cua_id = $_GET['cua_id'] ?? null;

        if (!$cua_id) {
            echo json_encode(["status" => "error", "message" => "ID de cuadrilla no proporcionado."]);
            exit();
        }

        $datos = $cuadrilla->create_word($cua_id);

        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontraron datos para la cuadrilla."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        // Ruta de la plantilla
        $template = '../public/templates/acta_entregachip.docx';
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

        $nombres = explode(",", $datos['nombres_colaboradores']);
        // Fusionar datos
        $TBS->MergeField('cuadrilla.colaborador1', $nombres[0]);  // Primer nombre
        $TBS->MergeField('cuadrilla.colaborador2', $nombres[1]);  // Segundo nombre

        $cedulas = explode(",", $datos['cedulas_colaboradores']);
        $TBS->MergeField('cuadrilla.cedula1', $cedulas[0]);  // Cedula del primer colaborador
        $TBS->MergeField('cuadrilla.cedula2', $cedulas[1]);  // Cedula del segundo colaborador

        $TBS->MergeField('cuadrilla.nombre', $datos['nombre_cuadrilla']);
        $TBS->MergeField('cuadrilla.equipos', $datos['equipos_asignados']);

        $file_name = "acta_chip_" . $cua_id . "_" . date('Y-m-d') . ".docx";
        $save_path = "../public/actas/chipsCuadrillas/" . $file_name;

        // Guardar el archivo
        $TBS->Show(OPENTBS_FILE, $save_path);

        // Descargar el archivo
        if (file_exists($save_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($save_path));
            readfile($save_path);
            exit();
        } else {
            echo "El archivo no se pudo generar correctamente.";
        }

        exit();



    case "asignar":
        $cuadrilla->insert_cuadrilla_asignacion($_POST["cua_id"], $_POST["col_id"]);

        break;


    case "asignarEquipo":
        $cuadrilla->insert_cuadrilla_equipos($_POST["cua_id"], $_POST["equipo_id"]);
        break;
    case "combo":
        $datos = $cuadrilla->get_cuadrilla();
        $data = array();
        if (is_array($datos) && count($datos) > 0) {
            foreach ($datos as $row) {
                $empresa = $row['cua_empresa'] == 1 ? '<span class="label label-pill label-info">Claro</span>' : ($row['cua_empresa'] == 2 ? '<span class="label label-pill label-danger">CNEL</span>' : "Otro");
                $ciudad = $row['cua_ciudad'] == 1 ? '<span class="label label-pill label-info">Guayaquil</span>' : ($row['cua_ciudad'] == 2 ? '<span class="label label-pill label-danger">Quito</span>' : "Otro");

                $data[] = array(
                    "0" => '<button class="btn btn-warning" onclick="asignar(' . $row['cua_id'] . ')"><span class="fa fa-plus"></span></button>',
                    "1" => $row['cua_nombre'],
                    "2" => $ciudad,
                    "3" => $empresa,
                );
            }
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
