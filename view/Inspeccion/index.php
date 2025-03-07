<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>


	<!DOCTYPE html>
	<html>
	<?php require_once("../MainHead/head.php"); ?>
	<title>Admin Soporte Tecnico</>
	</title>
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
								<h3>Generar Permisos Trabajos Especiales</h3>
								<ol class="breadcrumb breadcrumb-simple">
									<li><a href="#">Home</a></li>
									<li class="active">Generar Permisos</li>
								</ol>
							</div>
						</div>
					</div>
				</header>

				<div class="box-typical box-typical-padding">
					<button type="button" id="btnnuevo" class="btn btn-inline btn-primary">Nuevo Registro</button>
					<table id="inspeccion_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>

								<th style="width: 10%;">Trabajo a Realizar</th>
								<th style="width: 5%;">Numero de Orden</th>
								<th class="d-none d-sm-table-cell">Ubicacion</th>
								<th class="d-none d-sm-table-cell">Fecha</th>
								<th style="width: 10%;">Tecnico Solicitante</th>
								<th style="width: 10%;">Estado Inspeccion</th>
								<th class="text-center" style="width: 5%;"></th>
								<?php
								// Verificar si el usuario tiene rol 1 o 3 antes de mostrar la columna
								if ($_SESSION["rol_id"] == 2 || $_SESSION["rol_id"] == 4) {
								?>
									<th class="text-center" style="width: 5%;"></th>
								<?php } ?>


								
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

			</div>
		</div>
		<!-- Contenido -->

		<?php require_once("modalmantenimiento.php"); ?>

		<?php require_once("../MainJs/js.php"); ?>

		<script type="text/javascript" src="mntinspeccion.js"></script>

	</body>

	</html>
<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>