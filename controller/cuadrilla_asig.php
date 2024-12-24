<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla_Chip.php");
require_once("../models/Cuadrilla_creacion.php");


//Plugins creacion de Word Actas
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');




//Plugins creacion de XML,Excel
require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$cuadrilla = new Cuadrilla_Chip();
$cuadrilla_creacion = new Cuadrilla_creacion();

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
            } else {
                $sub_array[] = '
                    <div style="display: flex; align-items: center; cursor: pointer;" onclick="agregar(' . htmlspecialchars($row["cua_id"], ENT_QUOTES, 'UTF-8') . ');">
                        <i class="fa fa-exclamation-circle" style="color: #ffc107; margin-right: 5px;" title="Agregar equipo"></i>
                        <span>Sin Asignar</span>
                    </div>';
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

            // Validar si el campo 'recargas' es true o false
            $checked = $row["recargas"] ? 'checked' : ''; // Si recargas es true, marcar el checkbox

            $sub_array[] = '
                <div class="d-flex align-items-center">
                    <button type="button" onClick="generar(' . $row["cua_id"] . ');" 
                        id="' . $row["cua_id"] . '" 
                        class="btn btn-inline btn-success btn-sm ladda-button">
                        <i class="fa fa-print"></i>
                    </button>
                    <input type="checkbox" id="checkbox_' . $row["cua_id"] . '" 
                        class="mr-2" 
                        style="width: 20px; height: 20px;" ' . $checked . '>
                </div>';

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

    case 'marcarRecarga':
        if (isset($_POST['cua_id']) && isset($_POST['recargas'])) {
            $cua_id = $_POST['cua_id'];
            $recargas = $_POST['recargas'] == 'true' ? true : false; // Convertir el valor a booleano
            $result = $cuadrilla_creacion->marcarRecarga($cua_id, $recargas);

            if ($result > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Recarga actualizada']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la recarga']);
            }
        }
        break;

    case "desmarcarTodas":
        $result = $cuadrilla_creacion->desmarcarTodas();
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


        case 'exportarRecargas':
            $cuadrillas = $cuadrilla_creacion->obtenerRecargasTrue();
        
            if (!empty($cuadrillas)) {
                // Cargar la plantilla Excel
                $inputFileName = '../public/templates/formatoListadoRecargas.xlsx'; 
                $spreadsheet = IOFactory::load($inputFileName);
                $sheet = $spreadsheet->getActiveSheet();
        
                // Configurar las celdas con los datos obtenidos
                $fila = 9;  // Comienza en la fila 2 porque la 1 es para encabezados
                foreach ($cuadrillas as $cuadrilla) {
                    $sheet->setCellValue('C' . $fila, $cuadrilla['cua_id']);
                    $sheet->setCellValue('F' . $fila, $cuadrilla['cua_nombre']);
                    $sheet->setCellValue('D' . $fila, $cuadrilla['ciudad_nombre']);  // Nombre de la ciudad
                    $sheet->setCellValue('H' . $fila, $cuadrilla['recargas'] ? 'Sí' : 'No');
                    $sheet->setCellValue('E' . $fila, $cuadrilla['serie']);
                    $sheet->setCellValue('G' . $fila, 10.50);
                    $fila++;
                }
        
                // Guardar el archivo generado
                $writer = new Xlsx($spreadsheet);
                $fileName = 'cuadrillas_recargas_true_generado.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $fileName);
                $writer->save($temp_file);
        
                // Descargar archivo
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                readfile($temp_file);
                unlink($temp_file); // Eliminar archivo temporal
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No hay cuadrillas con recargas en true']);
            }
            break;
        
       
}

