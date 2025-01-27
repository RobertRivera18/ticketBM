<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="inspeccion_form">
                <div class="modal-body">
                    <input type="hidden" id="inspeccion_id" name="inspeccion_id">
                    <input type="hidden" id="col_id" name="col_id">
                    
                    <div class="form-group">
                        <label class="form-label" for="trabajo">Trabajo a Realizar</label>
                        <select class="select2" id="trabajo" name="trabajo">
                            <option value="1">Instalacion</option>
                            <option value="2">Garantia</option>
                            <option value="3">Mantenimiento</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="form-label" for="ubicacion">Ubicacion</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ingrese Ubicacion del Trabajo a realizar" required>
                        
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="numero_orden">Numero de Orden</label>
                        <input type="text" class="form-control" id="numero_orden" name="numero_orden" placeholder="Ingrese Numero de Orden" required>
                        
                    </div>

                </div>

                <div class="modal-body table-responsive" style="overflow-x: auto;" id="tabla_cuadrillas">
                        <table id="tblcuadrillas" class="table table-striped table-bordered table-condensed table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Cedula</th>
                                    <th>Empresa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se agregarían los colaboradores dinámicamente -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre de Cuadrilla</th>
                                    <th>Ciudad</th>
                                    <th>Empresa</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="guardarBtn" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>