<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
	<!DOCTYPE html>
	<html>
	<?php require_once("../MainHead/head.php"); ?>
	<title>Admin Soporte Tecnico</>::Mantenimiento Usuario</title>
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
								<h3>Mantenimiento Usuario</h3>
								<ol class="breadcrumb breadcrumb-simple">
									<li><a href="#">Home</a></li>
									<li class="active">Mantenimiento Usuario</li>
								</ol>
							</div>
						</div>
					</div>
				</header>

				<div class="box-typical box-typical-padding">
					<button type="button" id="btnnuevo" class="btn btn-inline btn-primary">Nuevo Registro</button>
					<table id="usuario_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
						<thead>
							<tr>
								<th style="width: 10%;">Nombre Usuario</th>
								<th style="width: 10%;">Cedula</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Correo</th>
								<th class="d-none d-sm-table-cell" style="width: 5%;">Rol</th>
								<th class="d-none d-sm-table-cell" style="width: 5%;">Empresa</th>
								<th style="width: 10%;">IP/MAC Equipo</th>
								<th class="text-center"></th>
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

		<script type="text/javascript" src="mntusuario.js"></script>

	</body>

	</html>
<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>