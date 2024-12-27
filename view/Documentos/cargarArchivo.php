<div id="cargarArchivo" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="documento_form" enctype="multipart/form-data">

                <div class="modal-body">
                    <input type="hidden" id="id_acta" name="id_acta">
                    <div class="form-group">
                        <label class="form-label" for="archivo">Comprobante a Cargar</label>
                        <input type="file" id="archivo" name="archivo" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="guardarArchivo" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
