<div id="modalasignar" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <label class="form-label" for="col_id">Colabordores</label>
                        <select class="select2" id="col_id" name="col_id" data-placeholder="Seleccionar" required>

                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="#" value="add" class="btn btn-rounded btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>