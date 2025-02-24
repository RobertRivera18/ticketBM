<?php
require_once("../../config/conexion.php");
require_once("../../models/Usuario_Equipo.php");
require_once("../../models/Usuario.php");

if (!isset($_GET["usu_id"]) || empty($_GET["usu_id"])) {
    die("ID del usuario no proporcionado.");
}

$usu_id = intval($_GET["usu_id"]);
$usuario_equipo = new Usuario_Equipo();
$equipos = $usuario_equipo->get_equipos_por_usuario($usu_id);
$address = $usuario_equipo->get_user_address($usu_id);



if (!is_array($equipos) || count($equipos) == 0) {
    die("No se encontró información de equipos asignados. Este usuario no tiene equipos asignados.");
}

$usuario_nombre = htmlspecialchars($equipos[0]["usu_nom"] . " " . $equipos[0]["usu_ape"]);
$ip_usuario = !empty($address["ip"]) ? $address["ip"] : "No disponible";
$mac_address = !empty($address["mac"]) ? $address["mac"] : "No disponible";
?>

<!DOCTYPE html>
<html lang="es">
<?php require_once("../MainHead/head.php"); ?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<title>Sistema Ticket Soporte Tecnico - Equipos Asignados</title>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center">Equipos Asignados al Usuario: <strong><?php echo $usuario_nombre ?></strong></h2>
        <p class="text-center">IP Asignada: <strong><?php echo $ip_usuario ?></strong></p>
        <p class="text-center">MAC del Equipo: <strong><?php echo $mac_address ?></strong></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Equipo</th>
                    <th>Marca</th>
                    <th>Serie</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar solo una vez IP y MAC del usuario
                foreach ($equipos as $equipo) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($equipo["nombre_equipo"]); ?></td>
                        <td><?php echo htmlspecialchars($equipo["marca"]); ?></td>
                        <td><?php echo htmlspecialchars($equipo["serie"]); ?></td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>