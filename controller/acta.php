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
        </button>
        <button type="button" onClick="procesarArchivo(' . $row["id_acta"] . ');" 
            id="' . $row["id_acta"] . '" 
            class="btn btn-inline btn-info btn-sm ladda-button">
            <i class="fa fa-upload"></i>
        </button>';

            // Asegúrate de que 'fecha_entrega' esté presente y sea válida
            if (!empty($row["fecha_entrega"])) {
                $sub_array[] = date("d/m/Y H:i:s", strtotime($row["fecha_entrega"]));
            } else {
                $sub_array[] = '<span class="label label-pill label-warning">Sin fecha</span>';
            }
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
            $TBS->MergeField('pro.fecha', date('d/m/Y'));

            //Guardo el nombre de la acta a generar
            $file_name = 'ACTA_ENTREGA_CREDENCIAL' . $datos['col_cedula'] . "_" . $datos['col_nombre'] . "_" . date('Y-m-d') . ".docx";
        } elseif ($datos['tipo_acta'] == 2) {
            // Caso: Acta de descarga
            $template = '../public/templates/acta_entregaequipo.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            $TBS->MergeField('pro.id', $datos['id_acta']);
            $TBS->MergeField('pro.tipo', "ACTA DE ENTREGA");
            $TBS->MergeField('pro.fecha', $datos['cua_nombre'] ?? 'Sin asignar');

            $file_name = "acta_descarga_" . $datos['col_cedula'] . "_" . date('Y-m-d') . ".docx";
        } else {
            echo json_encode(["status" => "error", "message" => "El tipo de acta no es válido."]);
            exit();
        }

        $save_path = "../public/actas/credencialesCuadrillas/" . $file_name;

        // Guardar el archivo en el servidor
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
            exit;
        } else {
            // Manejar error si el archivo no existe
            echo "El archivo no se pudo generar correctamente.";
        }


        header("Location:" . Conectar::ruta() . "/view/Documentos");
        exit();
        break;


        case "subirArchivo":
            header('Content-Type: application/json'); // Respuesta en formato JSON
        
            if (isset($_FILES['archivo']) && isset($_POST['id_acta'])) {
                $id_acta = intval($_POST['id_acta']); // Asegura que el ID sea un entero
                $archivo = $_FILES['archivo'];
        
                $destino = "../public/actas/comprobantes/";
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true); 
                }
        
                $nombreArchivo = uniqid() . "-" . basename($archivo['name']);
                $rutaCompleta = $destino . $nombreArchivo;
        
                if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
                    $guardar = $acta->guardarRutaArchivo($id_acta, $rutaCompleta);
        
                    if ($guardar) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Archivo cargado con éxito',
                            'nombre_guardado' => $nombreArchivo,
                            'ruta' => $rutaCompleta
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error al guardar la ruta en la base de datos']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al mover el archivo al destino']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el ID del acta']);
            }
            break;
        
}
