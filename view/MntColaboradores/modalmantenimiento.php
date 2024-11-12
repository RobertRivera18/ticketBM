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
                    <input type="hidden" id="col_id" name="col_id">

                    <div class="form-group">
                        <label class="form-label" for="col_nombre">Nombres del Colaborador</label>
                        <input type="text" class="form-control" id="col_nombre" name="col_nombre" placeholder="Ingrese Nombres del Colaborador" required>
                        
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="col_cedula">Cedula del Colaborador</label>
                        <input type="text" class="form-control" id="col_cedula" name="col_cedula" placeholder="Ingrese Cedula del Colaborador" required>
                        
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="empresa_id">Empresa</label>
                        <select class="select2" id="empresa_id" name="empresa_id">
                            <option value="1">CNEL</option>
                            <option value="2">Claro</option>
                            
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