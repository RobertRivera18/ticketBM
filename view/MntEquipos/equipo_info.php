<?php
require_once("../../config/conexion.php");
require_once("../../models/Usuario_Equipo.php");

if (!isset($_GET["usu_id"]) || empty($_GET["usu_id"])) {
    die("ID del usuario no proporcionado.");
}

$usu_id = intval($_GET["usu_id"]);
$usuario_equipo = new Usuario_Equipo();
$usuario = new Usuario();
$equipos = $usuario_equipo->get_equipos_por_usuario($usu_id);

if (!is_array($equipos) || count($equipos) == 0) {
    die("No se encontró información de equipos asignados.Este usuario no tiene equipos asignados");
}
$usuario_nombre = htmlspecialchars($equipos[0]["usu_nom"] . " " . $equipos[0]["usu_ape"]);
$ip_usuario = !empty($equipos[0]["ip"]);
?>

<!DOCTYPE html>
<html lang="es">
<?php require_once("../MainHead/head.php");?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<title>Sistema Ticket Soporte Tecnico - Equipos Asignados</title>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Equipos Asignados al Usuario: <strong><?php echo $usuario_nombre; ?></strong></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Equipo</th>
                    <th>Marca</th>
                    <th>Serie</th>
                    <th>IP Asignada</th>
                    <th>MAC Asignada</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    // Asumimos que la IP y la MAC están en el primer equipo del array
                    
                    $mac_usuario = !empty($equipos[0]["mac"]) ? htmlspecialchars($equipos[0]["mac"]) : 'Sin asignación';
                
                    // Mostrar solo una vez IP y MAC del usuario
                    foreach ($equipos as $equipo) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($equipo["nombre_equipo"]); ?></td>
                            <td><?php echo htmlspecialchars($equipo["marca"]); ?></td>
                            <td><?php echo htmlspecialchars($equipo["serie"]); ?></td>
                            <!-- IP y MAC asignados al usuario (una sola vez) -->
                            <?php if ($equipo === reset($equipos)) { ?>
                                <td rowspan="<?php echo count($equipos); ?>"><?php echo $ip_usuario; ?></td>
                                <td rowspan="<?php echo count($equipos); ?>"><?php echo $mac_usuario; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
