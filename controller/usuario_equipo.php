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
            $sub_array[] = $row["usu_nom"] . ' ' . $row["usu_ape"] . ' ' . '<span class="label label-info">' . $row["usu_correo"] . '</span>';

            // Manejo de equipos
            $equipos = $usuario_equipo->get_equipos_por_usuario($row["usu_id"]);
            $cantidad_equipos = is_array($equipos) ? count($equipos) : 0;

            if ($cantidad_equipos > 0) {
                $equipos_array = array_map(function ($equipo) use ($row) {
                    return '<li class="fs-6 d-flex align-items-center">' .
                        $equipo["nombre_equipo"] . ' - ' . $equipo["marca"] . ' - ' . $equipo["serie"] .
                        '<i class="fa fa-times text-danger ms-2 mt-1" style="cursor: pointer;" onClick="eliminarItem(' . $row["usu_id"] . ', ' . $equipo["equipo_id"] . ')"></i>' .
                        '</li>';
                }, $equipos);
                $sub_array[] = '<ul class="mb-1">' . implode("", $equipos_array) . '</ul>' .
                    '<a class="btn btn-sm btn-primary mt-1" onClick="agregarEquipo(' . $row["usu_id"] . ')">Agregar más</a>';
            } else {
                $sub_array[] = '
<div style="display: flex; align-items: center; cursor: pointer;" onclick="agregarEquipo(' . htmlspecialchars($row["usu_id"], ENT_QUOTES, 'UTF-8') . ');">
    <i class="fa fa-exclamation-circle" style="color: #ffc107; margin-right: 5px;" title="Agregar equipo"></i>
    <span>No tiene equipos asignados</span>
</div>';
            }

            $sub_array[] = '<button type="button" onClick="generar(' . $row["usu_id"] . ');" 
            id="' . $row["usu_id"] . '"class="btn btn-inline btn-success btn-sm ladda-button">
            <i class="fa fa-print"></i>
                       
                       <button type="button" onClick="procesarArchivo(' . $row["usu_id"] . ');" 
                    id="' . $row["usu_id"] . '" 
                    class="btn btn-inline btn-warning btn-sm ladda-button">
                    <i class="fa fa-image"></i>
                </button>

                </button> <button type="button" onClick="descargarComprobante(' . $row["usu_id"] . ')" 
                           id="' . $row["usu_id"] . '" 
                                class="btn btn-inline btn-danger btn-sm ladda-button">
                               <i class="fa fa-download"></i>
                       </button>
                    
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
        $usu_id = $_GET['usu_id'] ?? null;

        if (!$usu_id) {
            echo json_encode(["status" => "error", "message" => "ID de usuario no proporcionado."]);
            exit();
        }
        $datos = $usuario_equipo->create_word($usu_id);
        if (!$datos) {
            echo json_encode(["status" => "error", "message" => "No se encontraron datos para el usuario."]);
            exit();
        }

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);

        $equipos = [];

        // Agrego los equipos existentes al array
        foreach ($datos as $equipo) {
            $equipos[] = [
                'descripcion' => $equipo['descripcion'] ?? 'N/A',
                'marca'       => $equipo['marca'] ?? 'N/A',
                'modelo'      => $equipo['modelo'] ?? 'N/A',
                'serie'       => $equipo['serie'] ?? 'N/A',
            ];
        }

        //Verifica la cantidad de equipos por usuarios
        while (count($equipos) < 7) {
            $equipos[] = [
                'descripcion' => 'N/A',
                'marca'       => 'N/A',
                'modelo'      => 'N/A',
                'serie'       => 'N/A',
            ];
        }


        $template = '../public/templates/acta_entregaequipo.docx';
        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8);
        $nombre_completo = ($datos[0]['nombre_usuario'] ?? 'Sin asignar') . ' ' . ($datos[0]['apellido_usuario'] ?? '');
        $cedula = $datos[0]['cedula'] ?? 'No disponible';
        $TBS->MergeField('usu.nombre', trim($nombre_completo));
        $TBS->MergeField('fecha', date('d/m/Y'));
        $TBS->MergeField('cedula', $cedula);


        foreach ($equipos as $index => $equipo) {
            $TBS->MergeField("equipos.descripcion_$index", $equipo['descripcion']);
            $TBS->MergeField("equipos.marca_$index", $equipo['marca']);
            $TBS->MergeField("equipos.modelo_$index", $equipo['modelo']);
            $TBS->MergeField("equipos.serie_$index", $equipo['serie']);
        }

        $file_name = "acta_entrega_equipo_" . $nombre_completo . "_" . date('Y-m-d') . ".docx";
        $save_path = "../public/actas/entrega_equiposUsuarios/" . $file_name;
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

    case "subirArchivo":
        header('Content-Type: application/json');

        if (!isset($_FILES['archivo']) || !isset($_POST['usu_id'])) {
            echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el ID del acta']);
            exit;
        }

        $usu_id = intval($_POST['usu_id']);
        $archivo = $_FILES['archivo'];

        if (empty($usu_id)) {
            echo json_encode(['success' => false, 'message' => 'El ID del acta es inválido o está vacío']);
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

        $destino = "../public/actas/comprobantes/";
        if (!file_exists($destino)) {
            if (!mkdir($destino, 0777, true)) {
                echo json_encode(['success' => false, 'message' => 'Error al crear el directorio de destino']);
                exit;
            }
        }

        // Generar el nombre del archivo con el nombre del colaborador
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = "comprobante_firma"  . "." . $extension;
        $rutaRelativa = "public/actas/comprobantes/entrega_equiposUsuarios/comprobantesRecepcion" . $nombreArchivo;
        $rutaCompleta = "../" . $rutaRelativa;

        if (move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            $guardar = $usuario->guardarRutaArchivo($usu_id, $rutaRelativa);

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
        $usu_id = isset($_POST['usu_id']) ? intval($_POST['usu_id']) : 0;
        $ruta = $usuario->obtenerRutaArchivo($usu_id);
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
