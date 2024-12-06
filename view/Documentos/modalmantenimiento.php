<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="documento_form">
                <div class="modal-body">
                    <input type="hidden" id="id_acta" name="id_acta">


                    <div class="form-group">
                        <label class="form-label" for="tipo_acta">Tipo de Acta</label>
                        <select class="control-form select2" id="tipo_acta" name="tipo_acta">
                            <option>Seleccione el tipo de Acta a Generar</option>
                            <option value="1">Acta de Entrega Credencial</option>
                        
                        </select>
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
                                    <th>Compañia</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                    <!-- <div class="modal-body table-responsive" style="overflow-x: auto;" id="tabla_equipos">
                        <table id="tblequipos" class="table table-striped table-bordered table-condensed table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre Equipo</th>
                                    <th>Marca</th>
                                    <th>Serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                 Aquí se agregarían los colaboradores dinámicamente
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre Equipo</th>
                                    <th>Marca</th>
                                    <th>Serie</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" name="action" id="#" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
</div>