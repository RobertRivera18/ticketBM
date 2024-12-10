<?php
require_once("../config/conexion.php");
require_once("../models/Usuario.php");
require_once("../models/Usuario_Equipo.php");
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
$usuario = new Usuario();
$usuario_equipo = new Usuario_Equipo();

switch ($_GET["op"]) {
    case "listar":
        $datos = $usuario->get_usuario(); // Obtener todos los usuarios
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            // Nombre de la cuadrilla
            $sub_array[] = $row["usu_nom"];
            // Manejo de equipos
            $equipos = $usuario_equipo->get_equipos_por_usuario($row["usu_id"]);
            $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

            if ($cantidad_equipos > 0) {
                $equipos_array = array_map(function ($equipo) use ($row) {
                    return '<li>' . $equipo["nombre_equipo"] . ' - ' . $equipo["serie"] .
                           '<button class="btn btn-sm btn-danger ml-2" onClick="eliminarItem(' . $row["usu_id"] . ', ' . $equipo["equipo_id"] . ')">
                               <i class="fa fa-times"></i>
                           </button>' .
                           '</li>';
                }, $equipos);
                $sub_array[] = '<ul>' . implode("", $equipos_array) . '</ul>' .
                               '<br><a onClick="agregarEquipo(' . $row["usu_id"] . ')"><span class="label label-primary">Agregar más</span></a>';
            } else {
                $sub_array[] = '<a onClick="agregarEquipo(' . $row["usu_id"] . ');"><span class="label label-pill label-warning">No se le han otorgado equipos</span></a>';
            }

            $sub_array[] = '<button type="button" onClick="generar(' . $row["usu_id"] . ');" 
            id="' . $row["usu_id"] . '" 
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

    case "asignarEquipo":
        if (isset($_POST["usu_id"], $_POST["equipo_id"])) {
            $usu_id = intval($_POST["usu_id"]);
            $equipo_id = intval($_POST["equipo_id"]);
            // Intentar insertar el equipo para el usuario
            $resultado = $usuario_equipo->insert_usuario_equipos($usu_id, $equipo_id);
            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Equipo asignado correctamente."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "No se pudo asignar el equipo. Verifica los datos."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Faltan datos obligatorios para asignar el equipo."
            ]);
        }
        break;

    case "eliminarEquipo":
        $usuario_equipo->delete_usuario_equipo($_POST["usu_id"], $_POST["equipo_id"]);
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

        $file_name = "acta_cuadrilla_" . $cua_id . "_" . date('Y-m-d') . ".docx";
        $save_path = "../public/actas/" . $file_name;

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
}
