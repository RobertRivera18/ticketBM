<?php
require_once("../config/conexion.php");
require_once("../models/Cuadrilla_Chip.php");
require_once("../models/Cuadrilla_creacion.php");


//Plugins creacion de Word Actas
require_once('../public/tbs_class.php');
require_once('../public/plugins/tbs_plugin_opentbs.php');
//Plugins creacion de XML,Excel
require_once __DIR__ . '/../vendor/autoload.php';

$cuadrilla = new Cuadrilla_Chip();
$cuadrilla_creacion = new Cuadrilla_creacion();


switch ($_GET["op"]) {
    case "guardar":
        $cuadrilla->insert_cuadrilla($_POST["cua_nom"]);
        break;

    case "combo":
        $datos = $cuadrilla->get_equiposTecnicos_disponibles();
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
        $datos = $cuadrilla->get_cuadrilla();
        $data = array();

        foreach ($datos as $row) {
            $sub_array = array();
            $sub_array[] = $row["cua_nombre"];
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

            // Manejo de equipos solo aquellos que tienen en el campo datos 3 ya que esos son equipos para tecnicos
            $equipos = $cuadrilla->get_equipos_por_cuadrilla1($row["cua_id"]);
            $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

            if ($cantidad_equipos > 0) {
                $equipos_array = array_map(function ($equipo) use ($row) {
                    return '<li class="fs-6 d-flex align-items-center">' .
                        $equipo["nombre_equipo"] . ' - ' . $equipo["marca"] . ' - ' . $equipo["serie"] .
                        '<i class="fa fa-times text-danger ms-2 mt-1" style="cursor: pointer;" onClick="eliminarItems(' . $row["cua_id"] . ', ' . $equipo["equipo_id"] . ')"></i>' .
                        '</li>';
                }, $equipos);
                $sub_array[] = '<ul class="mb-1">' . implode("", $equipos_array) . '</ul>' .
                    '<a class="btn btn-sm btn-primary mt-1" onClick="agregarEquipo(' . $row["cua_id"] . ')">Agregar más</a>';
            } else {
                $sub_array[] = '
  <div style="display: flex; align-items: center; cursor: pointer;" onclick="agregarEquipo(' . htmlspecialchars($row["cua_id"], ENT_QUOTES, 'UTF-8') . ');">
      <i class="fa fa-exclamation-circle" style="color: #ffc107; margin-right: 5px;" title="Agregar equipo"></i>
      <span>No tiene equipos asignados</span>
  </div>';
            }

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

        $datos = $cuadrilla->create_word_equipo_cuadrilla($cua_id);

        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontraron datos para la cuadrilla."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        $template = '../public/templates/acta_entregaequipos_cuadrillas.docx';
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

        $nombres = explode(",", $datos[0]['nombres_colaboradores'] ?? '');
        $cedulas = explode(",", $datos[0]['cedulas_colaboradores'] ?? '');

        $TBS->MergeField('cuadrilla.colaborador1', $nombres[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.colaborador2', $nombres[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula1', $cedulas[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula2', $cedulas[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.nombre', $datos[0]['nombre_cuadrilla']);
        $TBS->MergeField('fecha',date('d/m/Y'));

        $equipos = [];
        foreach ($datos as $equipo) {
            $equipos[] = [
                'descripcion' => $equipo['descripcion'] ?? 'N/A',
                'marca'       => $equipo['marca'] ?? 'N/A',
                'modelo'      => $equipo['modelo'] ?? 'N/A',
                'serie'       => $equipo['serie'] ?? 'N/A',
            ];
        }

        while (count($equipos) < 7) {
            $equipos[] = [
                'descripcion' => 'N/A',
                'marca'       => 'N/A',
                'modelo'      => 'N/A',
                'serie'       => 'N/A',
            ];
        }

        foreach ($equipos as $index => $equipo) {
            $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
            $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
            $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
            $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
        }

        $file_name = "acta_entregaEquipos_" . str_replace(" ", "_", $datos[0]['nombre_cuadrilla']) . "_" . date('Y-m-d') . ".docx";
        $save_path = "../public/actas/chipsCuadrillas/" . $file_name;

        $TBS->Show(OPENTBS_FILE, $save_path);

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

    case "asignarEquipo":
        $cuadrilla->insert_cuadrilla_equipos($_POST["cua_id"], $_POST["equipo_id"]);
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

        $destino = "../public/cuadrillas/comprobantes/";
        if (!file_exists($destino)) {
            if (!mkdir($destino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio de destino']);
                exit;
            }
        }

        // Generar el nombre del archivo
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "comprobante_cuadrilla_" . $cua_id . "." . $extension;
        $rutaRelativa = "public/cuadrillas/comprobantes/" . $nombreArchivo;
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




    case "generar_word_descargo":
        $cua_id = $_GET['cua_id'] ?? null;

        if (!$cua_id) {
            echo json_encode(["status" => "error", "message" => "ID de cuadrilla no proporcionado."]);
            exit();
        }

        $datos = $cuadrilla->create_word_equipo_cuadrilla($cua_id);

        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontraron datos para la cuadrilla."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        $template = '../public/templates/descargo_cuadrilla_equipo.docx';
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);

        $nombres = explode(",", $datos[0]['nombres_colaboradores'] ?? '');
        $cedulas = explode(",", $datos[0]['cedulas_colaboradores'] ?? '');

        $TBS->MergeField('cuadrilla.colaborador1', $nombres[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.colaborador2', $nombres[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula1', $cedulas[0] ?? 'N/A');
        $TBS->MergeField('cuadrilla.cedula2', $cedulas[1] ?? 'N/A');
        $TBS->MergeField('cuadrilla.nombre', $datos[0]['nombre_cuadrilla']);
        $TBS->MergeField('fecha',date('d/m/Y'));

        $equipos = [];
        foreach ($datos as $equipo) {
            $equipos[] = [
                'descripcion' => $equipo['descripcion'] ?? 'N/A',
                'marca'       => $equipo['marca'] ?? 'N/A',
                'modelo'      => $equipo['modelo'] ?? 'N/A',
                'serie'       => $equipo['serie'] ?? 'N/A',
            ];
        }

        while (count($equipos) < 7) {
            $equipos[] = [
                'descripcion' => 'N/A',
                'marca'       => 'N/A',
                'modelo'      => 'N/A',
                'serie'       => 'N/A',
            ];
        }

        foreach ($equipos as $index => $equipo) {
            $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
            $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
            $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
            $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
        }

        $file_name = "acta_entregaEquipos_" . str_replace(" ", "_", $datos[0]['nombre_cuadrilla']) . "_" . date('Y-m-d') . ".docx";
        $save_path = "../public/actas/chipsCuadrillas/" . $file_name;

        $TBS->Show(OPENTBS_FILE, $save_path);

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

    case "subirComprobanteCuadrillaEquipo":
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

        $destino = "../public/cuadrillas/comprobantes/equipos";
        if (!file_exists($destino)) {
            if (!mkdir($destino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio de destino']);
                exit;
            }
        }

        // Generar el nombre del archivo
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "comprobante_cuadrilla_" . $cua_id . "." . $extension;
        $rutaRelativa = "public/cuadrillas/comprobantes/equipos_" . $nombreArchivo;
        $rutaCompleta = "../" . $rutaRelativa;

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            $guardar = $cuadrilla->guardarRutaComprobanteCuadrillaEquipos($cua_id, $rutaRelativa);

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


    case "obtenerRutaArchivo":
        $cua_id = isset($_POST['cua_id']) ? intval($_POST['cua_id']) : 0;
        $ruta = $cuadrilla->obtenerRutaArchivoEquipo($cua_id);
        echo json_encode(['success' => true, 'ruta' => $ruta]);
        break;

    // Caso para descargar el archivo de entrega de equipo a cuadrilla
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
