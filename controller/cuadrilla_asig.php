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
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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


            $colaboradores = $cuadrilla->get_colaboradores_por_cuadrilla((int)$row["cua_id"]);
            $cantidad_colaboradores = is_array($colaboradores) ? count($colaboradores) : 0;

            if ($cantidad_colaboradores > 0) {
                $colaboradores_array = array_map(function ($colaborador) use ($row) {
                    return '<li class="fs-6 d-flex align-items-center">' .
                        htmlspecialchars($colaborador["col_nombre"], ENT_QUOTES, 'UTF-8') .
                        '<i class="fa fa-times text-danger ms-2 mt-1" style="cursor: pointer;" onClick="eliminarItem(' .
                        $row["cua_id"] . ', ' . $colaborador["col_id"] . ')"></i>' .
                        '</li>';
                }, $colaboradores);

                $sub_array[] = '<ul class="mb-1">' . implode("", $colaboradores_array) . '</ul>' .
                    '<a class="btn btn-sm btn-primary mt-1" onClick="agregar(' . (int)$row["cua_id"] . ')">Agregar más</a>';
            } else {
                $sub_array[] = '
                    <div style="display: flex; align-items: center; cursor: pointer;" onclick="agregar(' .
                    htmlspecialchars($row["cua_id"], ENT_QUOTES, 'UTF-8') . ');">
                        <i class="fa fa-exclamation-circle" style="color: #ffc107; margin-right: 5px;" title="Agregar colaborador"></i>
                        <span>No tiene colaboradores asignados</span>
                    </div>';
            }


            // Manejo de equipos
            $equipos = $cuadrilla->get_equipos_por_cuadrilla($row["cua_id"]);
            $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

            if ($cantidad_equipos > 0) {
                $equipos_array = array_map(function ($equipo) use ($row) {
                    return '<li class="fs-6 d-flex align-items-center">' .
                        $equipo["nombre_equipo"] . ' - ' . $equipo["marca"] . ' - ' . $equipo["serie"] .
                        '<i class="fa fa-times text-danger ms-2 mt-1" style="cursor: pointer;" onClick="eliminarItems(' . $row["cua_id"] . ', ' . $equipo["equipo_id"] . ')"></i>' .
                        '</li>';
                }, $equipos);
                $sub_array[] = '<ul class="mb-1">' . implode("", $equipos_array) . '</ul>' .
                    '<a class="btn btn-sm btn-primary mt-1" onClick="agregarEquipo(' . $row["cua_id"] . ')"> Agregar más</a>';
            } else {
                $sub_array[] = '
  <div style="display: flex; align-items: center; cursor: pointer;" onclick="agregarEquipo(' . htmlspecialchars($row["cua_id"], ENT_QUOTES, 'UTF-8') . ');">
      <i class="fa fa-exclamation-circle" style="color: #ffc107; margin-right: 5px;" title="Agregar equipo"></i>
      <span>No tiene equipos asignados</span>
  </div>';
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
                    <button type="button" onClick="descargarNota(' . $row["cua_id"] . ');" 
                        id="' . $row["cua_id"] . '" 
                        class="btn btn-inline btn-primary btn-sm ladda-button">
                         <i class="fa fa-file"></i>
                    </button>
                   
                    <input type="checkbox" id="checkbox_' . $row["cua_id"] . '" 
                        class="mr-2" 
                        style="width: 20px; height: 20px;" ' . $checked . '>
                </div>

                
                ';
                $sub_array[] = '
                <div class="d-flex justify-content-end align-items-end">
                    <button type="button" onClick="procesarArchivo(' . $row["cua_id"] . ');" 
                        id="' . $row["cua_id"] . '" 
                        class="btn btn-inline btn-danger btn-sm ladda-button">
                         <i class="fa fa-image"></i>
                    </button>
                    <button type="button" onClick="descargarComprobante(' . $row["cua_id"] . ');" 
                        id="' . $row["cua_id"] . '" 
                        class="btn btn-inline btn-success btn-sm ladda-button">
                         <i class="fa fa-download"></i>
                    </button>
                </div>
            ';
            
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



    case "eliminarColaborador":
        $cuadrilla->delete_cuadrilla_colaborador($_POST["cua_id"], $_POST["col_id"]);
        break;

    case "eliminarEquipo":
        if (isset($_POST["cua_id"], $_POST["equipo_id"], $_POST["motivo"])) {
            $cua_id = $_POST["cua_id"];
            $equipo_id = $_POST["equipo_id"];
            $motivo = $_POST["motivo"];

            // Llamada a la función de eliminación con motivo
            $resultado = $cuadrilla->delete_cuadrilla_equipo($cua_id, $equipo_id, $motivo);

            echo json_encode(["success" => $resultado]);
        }
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
        break;



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
        echo json_encode([
            "status" => $result > 0 ? "success" : "error",
            "message" => $result > 0 ? "Recargas desmarcadas correctamente" : "No se realizaron cambios"
        ]);
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
            $fechaInicio = new DateTime('now');
            $fechaInicio->setDate($fechaInicio->format('Y'), $fechaInicio->format('m'), 23); // Día 23 del mes actual
            $fechaFin = clone $fechaInicio;
            $fechaFin->modify('+1 month'); // Sumar un mes

            // Encabezado dinámico
            $encabezado = "LISTADO DE LINEAS DE RECARGAS MENSUALES PERIODO " . $fechaInicio->format('d/m/Y') . " AL " . $fechaFin->format('d/m/Y');

            // Aplicar el encabezado a las filas 4, 5, 6 y 7 fusionando columnas C a I
            $sheet->mergeCells('C4:I7');
            $sheet->setCellValue('C4', $encabezado);
            $sheet->getStyle('C4')->getFont()->setBold(true);
            $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            $fila = 9;  // Comienza en la fila 9 porque las anteriores son encabezados
            $contador = 1; // Inicializa el contador en 1
            $cantidadCuadrillas = 0; // Para contar las cuadrillas

            foreach ($cuadrillas as $cuadrilla) {
                $colaboradores = !empty($cuadrilla['colaboradores']) ? explode(',', $cuadrilla['colaboradores']) : ['Sin colaboradores'];
                $primeraFila = true;

                foreach ($colaboradores as $colaborador) {
                    $sheet->setCellValue('C' . $fila, $contador); // Numeración secuencial
                    $sheet->setCellValue('D' . $fila, $cuadrilla['ciudad_nombre']);
                    $sheet->setCellValue('E' . $fila, $cuadrilla['serie']);
                    $sheet->setCellValue('F' . $fila, $cuadrilla['cua_nombre']);
                    $sheet->setCellValue('G' . $fila, $colaborador);
                    $sheet->setCellValue('H' . $fila, 10.50); // El valor constante
                    $sheet->setCellValue('I' . $fila, $cuadrilla['recargas']);

                    if ($primeraFila) {
                        $inicioFila = $fila;
                        $finFila = $fila + count($colaboradores) - 1;

                        if (count($colaboradores) > 1) {
                            $sheet->mergeCells("C$inicioFila:C$finFila");
                            $sheet->mergeCells("D$inicioFila:D$finFila");
                            $sheet->mergeCells("E$inicioFila:E$finFila");
                            $sheet->mergeCells("F$inicioFila:F$finFila");
                            $sheet->mergeCells("H$inicioFila:H$finFila");
                            $sheet->mergeCells("I$inicioFila:I$finFila");
                        }
                        $primeraFila = false;
                    }

                    $fila++;
                }

                $contador++; // Incrementar el contador después de procesar una cuadrilla
                $cantidadCuadrillas++; // Incrementar el contador de cuadrillas
            }

            // Calcular la suma total de recargas (cantidad de cuadrillas * 10.50)
            $totalRecargas = $cantidadCuadrillas * 10.50;
            $sheet->setCellValue('H59', $totalRecargas); // Asignar el total a la celda H59

            // Guardar el archivo generado
            $fActual = new DateTime();
            $writer = new Xlsx($spreadsheet);
            $fileName = 'reporteRecargas' . $fActual->format('Y-m-d') . '.xlsx';
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



    case "subirComprobanteCuadrilla":
        header('Content-Type: application/json');

        if (!isset($_FILES['archivo']) || !isset($_POST['cua_id'])) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el ID de la cuadrilla']);
            exit;
        }

        $cua_id = intval($_POST['cua_id']);
        $archivo = $_FILES['archivo'];

        if (empty($cua_id)) {
            echo json_encode(['success' => false, 'message' => 'El ID de la cuadrilla es inválido o está vacío']);
            exit;
        }

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

        $destino = "../public/cuadrillas/comprobantes/chips";
        if (!file_exists($destino)) {
            if (!mkdir($destino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio de destino']);
                exit;
            }
        }

        // Generar el nombre del archivo
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "comprobante_cuadrilla_" . $cua_id . "." . $extension;
        $rutaRelativa = "public/cuadrillas/comprobantes/chips" . $nombreArchivo;
        $rutaCompleta = "../" . $rutaRelativa;

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            $guardar = $cuadrilla->guardarRutaComprobanteCuadrilla($cua_id, $rutaRelativa);

            if ($guardar) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Archivo cargado con éxito',
                    'nombre_guardado' => $nombreArchivo,
                    'ruta' => $rutaRelativa
                ]);
            } else {
                unlink($rutaCompleta);
                echo json_encode(['success' => false, 'message' => 'Error al guardar la ruta en la base de datos']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo al destino']);
        }
        break;


    //Acta de descargo
    case "generar_word_descargo":
        $cua_id = $_GET['cua_id'] ?? null;

        if (!$cua_id) {
            echo json_encode(["status" => "error", "message" => "ID de cuadrilla no proporcionado."]);
            exit();
        }

        $datos = $cuadrilla->create_word_descargo($cua_id);

        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontraron datos para la cuadrilla."]);
            exit();
        }

        // Cargar la librería TinyButStrong y OpenTBS
        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        // Verificar que la plantilla Word existe
        $template = '../public/templates/descargo_cuadrilla.docx';

        if (!file_exists($template)) {
            echo "Error: La plantilla no existe en la ruta especificada.";
            exit();
        }

        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

        // Separar los nombres y cédulas asegurando que no haya espacios extra
        $nombres = array_map('trim', explode(",", $datos['nombres_colaboradores']));
        $cedulas = array_map('trim', explode(",", $datos['cedulas_colaboradores']));

        // Separar los datos de los equipos
        $series_equipos = array_map('trim', explode(",", $datos['equipos_asignados']));
        $nombres_equipos = array_map('trim', explode(",", $datos['nombres_equipos']));
        $marcas_equipos = array_map('trim', explode(",", $datos['marcas_equipos']));
        $modelos_equipos = array_map('trim', explode(",", $datos['modelos_equipos']));

        // Evitar errores si hay menos de dos colaboradores
        $colaborador1 = $nombres[0] ?? 'N/A';
        $colaborador2 = $nombres[1] ?? 'N/A';
        $cedula1 = $cedulas[0] ?? 'N/A';
        $cedula2 = $cedulas[1] ?? 'N/A';
        $nombre_cuadrilla = $datos['nombre_cuadrilla'] ?? 'N/A';

        // Evitar errores si no hay equipos asignados
        $serie1 = $series_equipos[0] ?? 'N/A';
        $equipo1 = $nombres_equipos[0] ?? 'N/A';
        $marca1 = $marcas_equipos[0] ?? 'N/A';
        $modelo1 = $modelos_equipos[0] ?? 'N/A';

        // Fusionar datos en la plantilla Word
        $TBS->MergeField('cuadrilla.colaborador1', $colaborador1);
        $TBS->MergeField('cuadrilla.colaborador2', $colaborador2);
        $TBS->MergeField('cuadrilla.cedula1', $cedula1);
        $TBS->MergeField('cuadrilla.cedula2', $cedula2);
        $TBS->MergeField('cuadrilla.nombre', $nombre_cuadrilla);
        $TBS->MergeField('fecha', date('d/m/Y'));

        // Datos de equipos
        $TBS->MergeField('equipo.serie', $serie1);
        $TBS->MergeField('equipo.nombre', $equipo1);
        $TBS->MergeField('equipo.marca', $marca1);
        $TBS->MergeField('equipo.modelo', $modelo1);

        // Definir el nombre del archivo y la ruta donde se guardará
        $file_name = "acta_descargo_cuadrilla_" . $cua_id . "_" . $nombre_cuadrilla . ".docx";
        $save_path = "../public/actas/descargo_cuadrillas/" . $file_name;

        // Guardar el archivo Word
        $TBS->Show(OPENTBS_FILE, $save_path);

        // Verificar si el archivo se generó correctamente
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
            echo "Error: No se pudo generar el archivo Word.";
        }
        exit();


        case "obtenerRutaArchivo":
            $cua_id = isset($_POST['cua_id']) ? intval($_POST['cua_id']) : 0;
            $ruta = $cuadrilla->obtenerRutaArchivo($cua_id);
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
}
