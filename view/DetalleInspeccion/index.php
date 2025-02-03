<?php
require_once("../../config/conexion.php");
if (isset($_SESSION["usu_id"])) {
?>
  <!DOCTYPE html>
  <html>
  <?php require_once("../MainHead/head.php"); ?>
  <title>Sistema Tickets</>::Detalle Inspeccion</title>



  <style>
    .checkbox-toggle input[type="checkbox"]:checked {
      color: green;
      /* Cambia el color del checkbox */
    }

    .checkbox-toggle input[type="checkbox"]:checked+label {
      color: black;
      /* Cambia el color del texto */
      font-weight: bold;
    }
  </style>
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
                <h3 id="lblidinspeccion">Detalle Inspeccion - 1</h3>
                <div id="lblestado"></div>
                <span class="label label-pill label-primary" id="lblnomusuario"></span>
                <span class="label label-pill label-default" id="lblfechcrea"></span>
                <ol class="breadcrumb breadcrumb-simple">
                  <li><a href="">Inspecciones</a></li>
                  <li class="active">Detalle Inspeccion</li>
                </ol>
              </div>
            </div>
          </div>
        </header>

        <div class="box-typical box-typical-padding">
          <div class="row">
            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label semibold" for="col_nombre">Nombre del Colaborador</label>
                <input type="text" class="form-control" id="col_nombre" name="col_nombre" readonly>
              </fieldset>
            </div>

            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label" for="trabajo">Trabajo</label>
                <select class="select2" id="trabajo" name="trabajo">
                  <option value="1">Instalacion</option>
                  <option value="2">Garantia</option>
                  <option value="3">Mantenimiento</option>
                </select>
              </fieldset>
            </div>
          </div>


          <div class="row">
            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label semibold" for="ubicacion">Ubicación</label>
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" readonly>
              </fieldset>
            </div>
            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label semibold" for="numero_orden">Número de Orden</label>
                <input type="text" class="form-control" id="numero_orden" name="numero_orden" readonly>
              </fieldset>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label semibold" for="fecha">Fecha</label>
                <input type="text" class="form-control" id="fecha" name="fecha" readonly>
              </fieldset>
            </div>
          </div>


          <div class="row">
            <!-- Inspección de Área -->
            <div class="col-lg-6">
              <h5 class="m-t-lg with-border font-weight-bold">Inspección de Área</h5>
              <div class="checkbox-toggle">
                <input type="checkbox" id="zona_resbaladiza">
                <label for="zona_resbaladiza">Zona Resbaladiza</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="zona_con_desnivel">
                <label for="zona_con_desnivel">Zona con Desnivel</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="hueco_piso_danado">
                <label for="hueco_piso_danado">Hueco o piso dañado</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="instalacion_mal_estado">
                <label for="instalacion_mal_estado">Instalación en mal estado</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="cables_desconectados_expuestos">
                <label for="cables_desconectados_expuestos">Cables Desconectados o Expuestos</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="escalera_buen_estado">
                <label for="escalera_buen_estado">Escalera o Equipo en Buen Estado</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="senaletica_instalada">
                <label for="senaletica_instalada">Señalética Instalada</label>
              </div>
            </div>

            <!-- Equipos de Seguridad Utilizados -->
            <div class="col-lg-6">
              <h5 class="m-t-lg with-border font-weight-bold">Equipos de Seguridad Utilizados</h5>
              <div class="checkbox-toggle">
                <input type="checkbox" id="botas">
                <label for="botas">Botas</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="chaleco">
                <label for="chaleco">Chaleco</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="proteccion_auditiva">
                <label for="proteccion_auditiva">Protección Auditiva</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="proteccion_visual">
                <label for="proteccion_visual">Protección Visual</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="linea_vida">
                <label for="linea_vida">Línea de Vida</label>
              </div>
              <div class="checkbox-toggle">
                <input type="checkbox" id="arnes">
                <label for="arnes">Arnés</label>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-lg-12">
              <fieldset class="form-group">
                <label class="form-label semibold" for="otros_equipos">Otros Equipos</label>
                <textarea class="form-control" id="otros_equipos" name="otros_equipos" rows="3" readonly></textarea>
              </fieldset>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6">
              <fieldset class="form-group">
                <label class="form-label semibold">Imagen de la Inspección</label>
                <br>
                <div class="image-container" style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                  <img id="imagen_inspeccion" src="" alt="Imagen de Inspección"
                    style="max-width: 100%; height: auto; display: none;">
                  <div id="no-image-message" style="display: none;">
                    No hay imagen disponible
                  </div>
                </div>
              </fieldset>
            </div>
          </div>




        </div>

        <div class="col-lg-6">
          <fieldset class="form-group">
            <label class="form-label semibold" for="estado_inspeccion">Estado de Inspeccion</label>
            <input type="text" class="form-control" id="estado_inspeccion" name="estado_inspeccion" readonly>
          </fieldset>
        </div>



        <div class="col-lg-6">
          <button class="btn btn-primary" id="btn-aprobar">Aprobar</button>
          <button class="btn btn-danger" id="btn-rechazar">Rechazar</button>
          <div id="motivo-rechazo-container" style="display: none; margin-top: 10px;">
            <input class="form-control" type="text" id="motivo-rechazo" placeholder="Ingrese el motivo del rechazo">
            <button class="btn btn-secondary" id="btn-confirmar-rechazo">Confirmar Rechazo</button>
          </div>
        </div>


        <div class="row" id="container-rechazo">
            <div class="col-lg-12">
              <fieldset class="form-group">
                <label class="form-label semibold" for="motivo_rechazo">Motivo de Rechazo</label>
                <textarea class="form-control" id="motivo_rechazo" name="motivo_rechazo" rows="3" readonly></textarea>
              </fieldset>
            </div>
          </div>



      </div>
    </div>
    <!-- Contenido -->

    <?php require_once("../MainJs/js.php"); ?>

    <script type="text/javascript" src="detalleinspeccion.js"></script>

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

  </body>

  </html>
<?php
} else {
  header("Location:" . Conectar::ruta() . "index.php");
}
?>