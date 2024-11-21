<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="cuadrilla_form">
                <div class="modal-body">
                    <input type="hidden" id="cua_id" name="cua_id">

                    <div class="form-group">
                        <label class="form-label" for="cua_nombre">Nombre de la Cuadrilla</label>
                        <input type="text" class="form-control" id="cua_nombre" name="cua_nombre" placeholder="Ingrese Nombre identificativo de la Cuadrilla" required>
                        
                    </div>

                    
                    <div class="form-group">
                        <label class="form-label" for="cua_empresa">Empresa</label>
                        <select class="select2" id="cua_empresa" name="cua_empresa">
                            <option value="1">Claro</option>
                            <option value="2">CNEL</option>
                        
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cua_ciudad">Ciudad</label>
                        <select class="select2" id="cua_ciudad" name="cua_ciudad">
                            <option value="1">Guayaquil</option>
                            <option value="2">Quito</option>
                        
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