<?php
require_once("../config/conexion.php");
require_once("../models/Acta.php");
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
$acta = new Acta();

switch ($_GET["op"]) {
    case "asignar":
        $acta->insert_acta($_POST["tipo_acta"], $_POST["col_id"]);
        break;


    case "asignarEquipo":
        $acta->insert_actaEquipos($_POST["tipo_acta"], $_POST["equipo_id"]);
        break;
    case "listar":
        // Obtener los datos del acta
        $datos = $acta->get_acta();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();

            // Asignar el tipo de acta
            switch ($row["tipo_acta"]) {
                case 1:
                    $sub_array[] = '<span class="label label-pill label-info">Acta de Entrega Credencial</span>';
                    break;
                case 2:
                    $sub_array[] = '<span class="label label-pill label-danger">Acta de Entrega de Equipos</span>';
                    break;
                default:
                    $sub_array[] = '<span>No asignado</span>';
            }

            // Validamos la prima columna del registro a listar
            if ($row["tipo_acta"] == 1) {
                $sub_array[] = 'Se entrego credencial';
            } else {
                // Manejo de equipos asignados
                $equipos = $acta->get_equipos_asignados($row["id_acta"]);
                $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

                if ($cantidad_equipos > 0) {
                    $equipos_array = array_map(function ($equipo) {
                        return '<li>' . $equipo["nombre_equipo"] . ' - ' . $equipo["serie"] . '</li>';
                    }, $equipos);

                    $sub_array[] = implode("<br>", $equipos_array) .
                        '<br><a onClick="asignarEquipo(' . $row["id_acta"] . ')"><span class="label label-primary">Agregar más</span></a>';
                } else {
                    $sub_array[] = '<a onClick="asignarEquipo(' . $row["id_acta"] . ');"><span class="label label-pill label-warning">-</span></a>';
                }
            }

            //Segunda columna del registro si se entrega un equipo
            if ($row["tipo_acta"] == 1) {
                $sub_array[] = $row["col_nombre"];
            } else {
                $sub_array[] = '';
            }



            // Botón para generar el acta
            $sub_array[] = '<button type="button" onClick="generar(' . $row["id_acta"] . ');" 
                    id="' . $row["id_acta"] . '" 
                    class="btn btn-inline btn-success btn-sm ladda-button">
                    <i class="fa fa-print"></i>
                </button>';

            // Agregar fila al resultado final

            $data[] = $sub_array;
        }

        // Preparar los resultados para enviar como JSON
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );

        echo json_encode($results);
        break;


    case "generar_word":
        $id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;
        $datos = $acta->get_acta_by_id($id_acta);

        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontró el acta."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        // Validar el tipo de acta y realizar acciones
        if ($datos['tipo_acta'] == 1) {
            // Caso: Acta de entrega
            $template = '../public/templates/acta.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            $TBS->MergeField('pro.colaborador', $datos['col_nombre'] ?? 'Sin asignar');
            $TBS->MergeField('pro.tipo', "Acta de Entrega");
            $TBS->MergeField('pro.cedula', $datos['col_cedula']);
            

            $file_name = "acta_entrega_" . $datos['id_acta'] . "_" . date('Y-m-d') . ".docx";
        } elseif ($datos['tipo_acta'] == 2) {
            // Caso: Acta de descarga
            $template = '../public/templates/acta_entregaequipo.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            $TBS->MergeField('pro.id', $datos['id_acta']);
            $TBS->MergeField('pro.tipo', "ACTA DE ENTREGA");
            $TBS->MergeField('pro.fecha', $datos['cua_nombre'] ?? 'Sin asignar');

            $file_name = "acta_descarga_" . $datos['id_acta'] . "_" . date('Y-m-d') . ".docx";
        } else {
            echo json_encode(["status" => "error", "message" => "El tipo de acta no es válido."]);
            exit();
        }

        $save_path = "../public/actas/" . $file_name;

        // Guardar el archivo en el servidor
        $TBS->Show(OPENTBS_FILE, $save_path);
        
        // Descargar el archivo
        if (file_exists($save_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file_name).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($save_path));
            readfile($save_path);
            exit;
        } else {
            // Manejar error si el archivo no existe
            echo "El archivo no se pudo generar correctamente.";
        }


        header("Location:" . Conectar::ruta() . "/view/Documentos");
        exit();
        break;
}
