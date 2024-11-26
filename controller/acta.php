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


        case "asignarEquipo":
            $acta->insert_actaEquipos($_POST["tipo_acta"], $_POST["equipo_id"]);
            break;
    case "listar":
        $datos = $acta->get_acta();
        $data = array();
        foreach ($datos as $row) {
            $sub_array = array();
            if ($row["tipo_acta"] == 1) {
                $sub_array[] = '<span class="label label-pill label-info">Acta de Entrega Credencial</span>';
            } elseif ($row["tipo_acta"] == 2) {
                $sub_array[] = '<span class="label label-pill label-danger">Acta de Entrega de Equipos</span>';
            } else {
                $sub_array[] = '<span>No asignado</span>';
            }

            $sub_array[] = $row["cua_nombre"] ? $row["cua_nombre"] : '-';


            $sub_array[] = '<button type="button" onClick="generar(' . $row["id_acta"] . ');" 
                id="' . $row["id_acta"] . '" 
                                   class="btn btn-inline btn-success btn-sm ladda-button">
                               <i class="fa fa-print"></i>
                           </button>';
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
        
            $TBS = new clsTinyButStrong;
            $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        
            // Validar el tipo de acta y realizar acciones
            if ($datos['tipo_acta'] == 1) {
                // Caso: Acta de entrega
                $template = '../public/templates/acta.docx';
                $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
        
                $TBS->MergeField('pro.id', $datos['id_acta']);
                $TBS->MergeField('pro.tipo', "ACTA DE ENTREGA");
                $TBS->MergeField('pro.cuadrilla', $datos['cua_nombre'] ?? 'Sin asignar');
        
                $file_name = "acta_entrega_" . $datos['id_acta'] . "_" . date('Y-m-d') . ".docx";
            } elseif ($datos['tipo_acta'] == 2) {
                // Caso: Acta de descarga
                $template = '../public/templates/acta_entregaequipo.docx';
                $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
        
                $TBS->MergeField('pro.id', $datos['id_acta']);
                $TBS->MergeField('pro.tipo', "ACTA DE ENTREGA");
                $TBS->MergeField('pro.cuadrilla', $datos['cua_nombre'] ?? 'Sin asignar');
        
                $file_name = "acta_descarga_" . $datos['id_acta'] . "_" . date('Y-m-d') . ".docx";
            } else {
                echo json_encode(["status" => "error", "message" => "El tipo de acta no es válido."]);
                exit();
            }
        
            // Ruta donde guardar el archivo
            $save_path = "../public/actas/" . $file_name;
        
            // Guardar el archivo en el servidor
            $TBS->Show(OPENTBS_FILE, $save_path);
        
    
            header("Location:".Conectar::ruta()."/view/Documentos");
            exit();
            break;
        
}
