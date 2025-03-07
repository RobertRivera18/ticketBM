<?php
  require_once("../../config/conexion.php"); 
  if(isset($_SESSION["usu_id"])){ 
?>
<!DOCTYPE html>
<html>
    <?php require_once("../MainHead/head.php");?>
	<title>Admin Soporte Tecnico</>::Mantenimiento Colaboradores</title>
</head>
<body class="with-side-menu">

    <?php require_once("../MainHeader/header.php");?>

    <div class="mobile-menu-left-overlay"></div>
    
    <?php require_once("../MainNav/nav.php");?>

	<!-- Contenido -->
	<div class="page-content">
		<div class="container-fluid">
			<header class="section-header">
				<div class="tbl">
					<div class="tbl-row">
						<div class="tbl-cell">
							<h3>Mantenimiento Equipos Informaticos</h3>
							<ol class="breadcrumb breadcrumb-simple">
								<li><a href="#">Home</a></li>
								<li class="active">Mantenimiento Equipos Informaticos</li>
							</ol>
						</div>
					</div>
				</div>
			</header>

			<div class="box-typical box-typical-padding">
				<button type="button" id="btnnuevo" class="btn btn-inline btn-primary">Nuevo Registro</button>
				<table id="colaborador_data" class="table table-bordered table-striped table-vcenter js-dataTable-full">
					<thead>
						<tr>
						
							<th style="width: 10%;">Nombre del Equipo</th>
							<th style="width: 10%;">Marca</th>
							<th class="d-none d-sm-table-cell" style="width: 5%;">Modelo Equipo</th>
							<th class="d-none d-sm-table-cell" style="width: 5%;">Serie/IMEI</th>
							<th class="d-none d-sm-table-cell" style="width: 5%;">Asignado</th>
							<th class="d-none d-sm-table-cell" style="width: 5%;">Datos</th>
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

	<?php require_once("modalmantenimiento.php");?>

	<?php require_once("../MainJs/js.php");?>
	
	<script type="text/javascript" src="mntequipos.js"></script>

</body>
</html>
<?php
  } else {
    header("Location:".Conectar::ruta()."index.php");
  }
?>