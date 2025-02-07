<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="colaborador_form">
                <div class="modal-body">
                    <input type="hidden" id="equipo_id" name="equipo_id">

                    <div class="form-group">
                        <label class="form-label" for="nombre_equipo">Nombres del Equipo</label>
                        <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" placeholder="Ingrese Nombre del Equipo" required>
                        
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="marca">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" placeholder="Ingrese Marca del equipo" required>
                        
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="serie">Modelo Equipo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ingrese modelo del equipo" required>
                        
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="serie">Serie/IMEI</label>
                        <input type="text" class="form-control" id="serie" name="serie" placeholder="Ingrese serie del equipo" required>
                        
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="rol_id">Es un chip?</label>
                        <select class="select2" id="datos" name="datos">
                            <option value="1">IMEI</option>
                            <option value="2">Equipo Informatico</option>
                        </select>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="#" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalmantenimiento1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mdltitulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mdltitulo">Código QR</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <input type="hidden" id="equipo_id" name="equipo_id">
                <div id="qrContainer">
                    <p>Aquí se mostrará el QR generado.</p>
                    <img src="" alt="Código QR" id="imagen_qr">

                </div>
            </div>
        </div>
    </div>
</div>
