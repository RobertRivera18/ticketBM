<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
	<!DOCTYPE html>
	<html>
	<?php require_once("../MainHead/head.php"); ?>
	<link rel="stylesheet" href="spinner.css">
	<title>Admin Soporte Tecnico</>::Mantenimiento Cuadrillas</title>
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
								<h3>Asignación Cuadrillas-Chip</h3>
								<ol class="breadcrumb breadcrumb-simple">
									<li><a href="#">Home</a></li>
									<li class="active">Asignación Cuadrillas-Chip </li>
								</ol>
							</div>
						</div>
					</div>
				</header>

				<div class="box-typical box-typical-padding">
					<table id="cuadrilla_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">

					<span id="spinner" class="loader" style="display: none;"></span>

						<div class="row mb-3">
							<div class="col-md-12 text-right">
								<button onclick="refresh()" class="btn btn-primary ml-2">
									<i class="fa fa-refresh"></i> Resetear Recargas
								</button>


								<button id="exportarRecargas" class="btn btn-success ml-2">
									<i class="fa fa-file"></i> Generar Excel
								</button>
							</div>
						</div>
						<thead>
							<tr>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Nombre Cuadrilla</th>
								<th style="width: 10%;">Empresa</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Colaboradores Integrantes</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Chip Entregado</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Generar Acta</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Descargar</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>


			</div>
		</div>
		<!-- Contenido -->

		<?php require_once("modalasignar.php"); ?>
		<?php require_once("modalequipos.php"); ?>
		<?php require_once("modalmantenimiento.php"); ?>

		<?php require_once("../MainJs/js.php"); ?>

		<script type="text/javascript" src="mntcuadrilla.js"></script>

	</body>

	</html>
<?php
} else {
	header("Location:" . Conectar::ruta() . "index.php");
}
?>