<div id="cargarArchivo" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="mdltitulo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <h4 class="modal-title" id="mdltitulo">Cargar Comprobante</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Formulario -->
            <form method="post" id="archivo_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Campo oculto para el ID del acta -->
                    <input type="hidden" id="usu_id" name="usu_id">
                    
                    <!-- Input para el archivo -->
                    <div class="form-group">
                        <label for="archivo" class="form-label">Comprobante a cargar</label>
                        <input 
                            type="file" 
                            id="archivo" 
                            name="archivo" 
                            class="form-control" 
                            accept=".pdf,.doc,.docx,.jpg,.png"
                            required
                        >
                        <small class="form-text text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG.</small>
                    </div>
                </div>

       
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-rounded" data-dismiss="modal">Cerrar</button>
                    <button 
                        type="submit" 
                        id="guardarArchivo" 
                        class="btn btn-primary btn-rounded"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
