<?php
require_once("../config/conexion.php");
require_once("../models/Acta.php");
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
$acta = new Acta();

switch ($_GET["op"]) {
    case "guardaryeditar":
        $acta->insert_acta($_POST["tipo_acta"], $_POST["cua_id"]);
        break;
    case "asignar":
        $acta->insert_acta($_POST["tipo_acta"], $_POST["cua_id"]);
        break;

    case "listar":
        $datos = $acta->get_acta();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            if ($row["tipo_acta"] == 1) {
                $sub_array[] = '<span class="label label-pill label-info">Acta de Entrega</span>';
            } elseif ($row["tipo_acta"] == 2) {
                $sub_array[] = '<span class="label label-pill label-danger">Acta de Descarga</span>';
            } else {
                $sub_array[] = '<span>No asignado</span>';
            }

            $sub_array[] = $row["cua_nombre"] ? $row["cua_nombre"] : 'Sin cuadrilla';


            $sub_array[] = '<button type="button" onClick="generar(' . $row["id_acta"] . ');"  
                                  id="' . $row["id_acta"] . '" 
                                  class="btn btn-inline btn-danger btn-sm ladda-button">
                                  <i class="fa fa-save"></i></button>';
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

        case "generar_word":
            $id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;
    
            $datos = $acta->get_acta_by_id($id_acta);
    
            if (!$datos) {
                echo json_encode(["status" => "error", "message" => "No se encontró el acta."]);
                exit();
            }
    
            // Inicializar TinyButStrong y OpenTBS
            $TBS = new clsTinyButStrong;
            $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
    
            // Ruta de la plantilla
            $template = '../public/templates/acta.docx';
    
            // Cargar la plantilla
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
    
            // Asignar los valores al documento
            $TBS->MergeField('pro.id', $datos['id_acta']);
            $TBS->MergeField('pro.tipo', $datos['tipo_acta'] == 1 ? "ACTA DE ENTREGA" : "ACTA DE DESCARGA");
            $TBS->MergeField('pro.cuadrilla', $datos['cua_nombre'] ?? 'Sin asignar');
    
            // Generar nombre dinámico
            $output_file_name = "acta_" . $datos['id_acta'] . "_" . date('Y-m-d') . ".docx";
    
            // Descargar archivo
            $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name);
            exit();
            break;
}
