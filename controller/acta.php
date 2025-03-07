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
        $acta->insert_actaEquipos($_POST["tipo_acta"], $_POST["col_id"], $_POST["equipo_id"]);
        break;
    case "listar":
        // Obtener los datos del acta
        $datos = $acta->get_acta();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();

            // Asignar el tipo de acta
            switch ($row["tipo_acta"]) {
                case 2:
                    $sub_array[] = '<span class="label label-pill label-info">Acta de Entrega Credencial</span>';
                    break;
                case 3:
                    $sub_array[] = '<span class="label label-pill label-danger">Acta de Entrega de Equipos</span>';
                    break;
                default:
                    $sub_array[] = '<span>No asignado</span>';
            }

            // Validamos la prima columna del registro a listar
            if ($row["tipo_acta"] == 2) {
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



            $sub_array[] = $row["col_nombre"];
            $botones = '<button type="button" onClick="generar(' . $row["id_acta"] . ');" 
            id="' . $row["id_acta"] . '" 
            class="btn btn-inline btn-success btn-sm ladda-button">
            <i class="fa fa-print"></i>
        </button>
        <button type="button" onClick="procesarArchivo(' . $row["id_acta"] . ');" 
            id="' . $row["id_acta"] . '" 
            class="btn btn-inline btn-warning btn-sm ladda-button">
            <i class="fa fa-image"></i>
        </button>
        
        <button type="button" onClick="descargarComprobante(' . $row["id_acta"] . ');" 
            id="' . $row["id_acta"] . '" 
            class="btn btn-inline btn-danger btn-sm ladda-button">
            <i class="fa fa-download"></i>
        </button>
        <button type="button" onClick="eliminarActa(' . $row["id_acta"] . ');" 
            id="' . $row["id_acta"] . '" 
            class="btn btn-inline btn-danger btn-sm ladda-button">
            <i class="fa fa-trash"></i>
        </button>';

            if ($row["tipo_acta"] == 3) {
                $botones .= '<button type="button" onClick="descargarNota(' . $row["id_acta"] . ');" 
                id="' . $row["id_acta"] . '" 
                class="btn btn-inline btn-success btn-sm ladda-button">
                <i class="fa fa-file"></i>
            </button>';
            }

            $sub_array[] = $botones;






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

    case "eliminarActa":

        $acta->delete_acta($_POST["id_acta"]);

        break;


    case "generar_word":
        $id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;
        $tipo_acta = isset($_GET['tipo_acta']) ? intval($_GET['tipo_acta']) : 0;
        $datos = $acta->get_acta_by_id($id_acta, $tipo_acta);


        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontró el acta."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        if ($datos['tipo_acta'] == 2) {
            // Caso: Acta de entrega
            $template = '../public/templates/acta.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            $TBS->MergeField('pro.colaborador', $datos['col_nombre'] ?? 'Sin asignar');
            $TBS->MergeField('pro.tipo', "Acta de Entrega");
            $TBS->MergeField('pro.cedula', $datos['col_cedula']);
            $TBS->MergeField('pro.fecha', date('d/m/Y'));

            $file_name = 'ACTA_ENTREGA_CREDENCIAL' . $datos['col_cedula'] . "_" . $datos['col_nombre'] . "_" . date('Y-m-d') . ".docx";
        } elseif ($datos['tipo_acta'] == 3) {

            // Caso: Acta de descarga (equipo)
            $template = '../public/templates/acta_entregaequipos_colaboradores.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            // Campos del colaborador
            $TBS->MergeField('pro.id', $datos['id_acta']);
            $TBS->MergeField('fecha', date('d/m/Y'));
            $TBS->MergeField('pro.cedula', $datos['col_cedula']);
            $TBS->MergeField('pro.colaborador', $datos['col_nombre'] ?? 'Sin asignar');



            $TBS->MergeField('pro.nombre_equipo', $datos['nombre_equipo'] ?? 'Sin asignar');
            $TBS->MergeField('pro.marca', $datos['marca'] ?? 'Sin asignar');
            $TBS->MergeField('pro.modelo', $datos['modelo'] ?? 'Sin asignar');
            $TBS->MergeField('pro.serie', $datos['serie'] ?? 'Sin asignar');

            $file_name = "acta_descarga_" . $datos['col_nombre'] . "-" . $datos['col_cedula'] . "_" . date('Y-m-d') . ".docx";
        } else {
            echo json_encode(["status" => "error", "message" => "El tipo de acta no es válido."]);
            exit();
        }

        $save_path = "../public/actas/credencialesCuadrillas/" . $file_name;
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
            echo "El archivo no se pudo generar correctamente.";
        }

        header("Location:" . Conectar::ruta() . "/view/Documentos");
        exit();
        break;



    case "subirArchivo":
        header('Content-Type: application/json');

        if (!isset($_FILES['archivo']) || !isset($_POST['id_acta'])) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el ID del acta']);
            exit;
        }

        $id_acta = intval($_POST['id_acta']);
        $archivo = $_FILES['archivo'];

        if (empty($id_acta)) {
            echo json_encode(['success' => false, 'message' => 'El ID del acta es inválido o está vacío']);
            exit;
        }

        // Obtener el nombre del colaborador asociado al acta
        $datos_acta = $acta->get_acta_by_id($id_acta); // Asegúrate de que este método exista y devuelva los datos correctos
        if (!$datos_acta || empty($datos_acta['col_nombre'])) {
            echo json_encode(['success' => false, 'message' => 'No se encontró el colaborador asociado al acta']);
            exit;
        }
        $nombre_colaborador = preg_replace('/[^A-Za-z0-9_\-]/', '_', $datos_acta['col_nombre']); // Limpiar caracteres especiales

        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
            exit;
        }

        // Validar tamaño (5MB máximo)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'El archivo excede el tamaño máximo permitido (5MB)']);
            exit;
        }

        $destino = "../public/actas/comprobantes/";
        if (!file_exists($destino)) {
            if (!mkdir($destino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio de destino']);
                exit;
            }
        }

        // Generar el nombre del archivo con el nombre del colaborador
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "comprobante_firma" . "-" . $nombre_colaborador . "." . $extension;
        $rutaRelativa = "public/actas/comprobantes/" . $nombreArchivo;
        $rutaCompleta = "../" . $rutaRelativa;

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            $guardar = $acta->guardarRutaArchivo($id_acta, $rutaRelativa);

            if ($guardar) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Archivo cargado con éxito',
                    'nombre_guardado' => $nombreArchivo,
                    'ruta' => $rutaRelativa
                ]);
            } else {
                // Si falla el guardado en BD, eliminar el archivo
                unlink($rutaCompleta);
                echo json_encode(['success' => false, 'message' => 'Error al guardar la ruta en la base de datos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo al destino']);
        }
        break;

    case "obtenerRutaArchivo":
        $id_acta = isset($_POST['id_acta']) ? intval($_POST['id_acta']) : 0;
        $ruta = $acta->obtenerRutaArchivo($id_acta);
        echo json_encode(['success' => true, 'ruta' => $ruta]);
        break;

        // Caso para descargar el archivo
    case "descargarArchivo":
        $ruta = isset($_GET['ruta']) ? $_GET['ruta'] : '';
        $rutaCompleta = "../" . $ruta;

        if (file_exists($rutaCompleta)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($rutaCompleta) . '"');
            header('Content-Length: ' . filesize($rutaCompleta));
            readfile($rutaCompleta);
            exit;
        }
        http_response_code(404);
        exit;
        break;


    case "generar_acta_descargo":
        $id_acta = isset($_GET['id_acta']) ? intval($_GET['id_acta']) : 0;
        $tipo_acta = isset($_GET['tipo_acta']) ? intval($_GET['tipo_acta']) : 0;
        $datos = $acta->get_acta_by_id($id_acta, $tipo_acta);


        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontró el acta."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        if ($datos['tipo_acta'] == 2) {
            // Caso: Acta de entrega
            $template = '../public/templates/acta_descargo.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            $TBS->MergeField('pro.colaborador', $datos['col_nombre'] ?? 'Sin asignar');
            $TBS->MergeField('pro.tipo', "Acta de Entrega");
            $TBS->MergeField('pro.cedula', $datos['col_cedula']);
            $TBS->MergeField('pro.fecha', date('d/m/Y'));

            $file_name = 'ACTA_ENTREGA_CREDENCIAL' . $datos['col_cedula'] . "_" . $datos['col_nombre'] . "_" . date('Y-m-d') . ".docx";
        } elseif ($datos['tipo_acta'] == 3) {

            // Caso: Acta de descarga (equipo)
            $template = '../public/templates/acta_descargo.docx';
            $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

            // Campos del colaborador
            $TBS->MergeField('pro.id', $datos['id_acta']);
            $TBS->MergeField('fecha', date('d/m/Y'));
            $TBS->MergeField('pro.cedula', $datos['col_cedula']);
            $TBS->MergeField('pro.colaborador', isset($datos['col_nombre']) ? ucwords(strtolower($datos['col_nombre'])) : 'Sin asignar');
            $TBS->MergeField('pro.fecha', date('d/m/Y'));



            $TBS->MergeField('pro.nombre_equipo', $datos['nombre_equipo'] ?? 'Sin asignar');
            $TBS->MergeField('pro.marca', $datos['marca'] ?? 'Sin asignar');
            $TBS->MergeField('pro.modelo', $datos['modelo'] ?? 'Sin asignar');
            $TBS->MergeField('pro.serie', $datos['serie'] ?? 'Sin asignar');

            $file_name = "descargo" . $datos['col_nombre'] . "-" . $datos['col_cedula'] . "_" . date('Y-m-d') . ".docx";
        } else {
            echo json_encode(["status" => "error", "message" => "El tipo de acta no es válido."]);
            exit();
        }

        $save_path = "../public/actas/credencialesCuadrillas/" . $file_name;
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
            echo "El archivo no se pudo generar correctamente.";
        }

        header("Location:" . Conectar::ruta() . "/view/Documentos");
        exit();
        break;
}
