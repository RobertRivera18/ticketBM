<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
	<!DOCTYPE html>
	<html>
	<?php require_once("../MainHead/head.php"); ?>
	<title>Admin Soporte Tecnico</>::Asignacion Equipos/Usuarios</title>
	<meta charset="utf-8">

	</head>

	<body class="with-side-menu">

		<?php require_once("../MainHeader/header.php"); ?>

		<div class="mobile-menu-left-overlay"></div>

		<?php require_once("../MainNav/nav.php"); ?>

		<!-- Contenido -->
		<div class="page-content">
			<div class="container-fluid">
				<header class="section-header">
					<div class="tbl">
						<div class="tbl-row">
							<div class="tbl-cell">
								<h3>Asignacion Equipos-Usuarios</h3>
								<ol class="breadcrumb breadcrumb-simple">
									<li><a href="#">Home</a></li>
									<li class="active">Asignacion de Equipos</li>
								</ol>
							</div>
						</div>
					</div>
				</header>

				<div class="box-typical box-typical-padding">

					<table id="cuadrilla_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th class="text-center text-nowrap px-2">Usuarios</th>
								<th class="text-center text-nowrap px-2">Equipos Entregados</th>
								<th class="text-center text-nowrap px-2">IP/MAC</th>
								<th class="text-center px-2" style="width: 5%;">Generar Actas Entrega/Descarga</th>
								<th class="text-center" style="width: 5%;"></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div>
		</div>
		<!-- Contenido -->
		<?php require_once("modalequipos.php"); ?>
		<?php require_once("modalmantenimiento.php"); ?>

		<?php require_once("../MainJs/js.php"); ?>

		<script type="text/javascript" src="usuarios_equipos.js"></script>

	</body>

	</html>
<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>