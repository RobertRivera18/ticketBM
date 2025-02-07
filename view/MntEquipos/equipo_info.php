<?php
require_once("../../config/conexion.php"); // Ajusta la ruta según tu estructura de proyecto
require_once("../../models/Equipo.php"); // Modelo para obtener la información del equipo

// Verificar si se proporciona un ID de equipo válido
if (!isset($_GET["equipo_id"]) || empty($_GET["equipo_id"])) {
    die("ID de equipo no proporcionado.");
}

$equipo_id = intval($_GET["equipo_id"]); // Convertir a entero para seguridad

$equipo = new Equipo(); // Instancia del modelo
$datos = $equipo->get_equipo_x_id($equipo_id);

if (!is_array($datos) || count($datos) == 0) {
    die("No se encontró información para este equipo.");
}

$equipo_info = $datos[0]; // Obtener los datos del equipo

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Equipo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Información del Equipo</h2>
        <table class="table table-bordered">
            <tr>
                <th>ID del Equipo</th>
                <td><?php echo htmlspecialchars($equipo_info["equipo_id"]); ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($equipo_info["nombre_equipo"]); ?></td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td><?php echo htmlspecialchars($equipo_info["marca"]); ?></td>
            </tr>
            <tr>
                <th>Fecha de Registro</th>
                <td><?php echo htmlspecialchars($equipo_info["modelo"]); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>
