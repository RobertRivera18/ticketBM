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
                        <label class="form-label" for="serie">Serie/IMEI</label>
                        <input type="text" class="form-control" id="serie" name="serie" placeholder="Ingrese serie del equipo" required>
                        
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