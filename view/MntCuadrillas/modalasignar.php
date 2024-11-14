<!-- <div id="modalasignar" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                        <label class="form-label" for="col_id">Colaboradores</label>
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
</div> -->

<div class="modal fade" id="modalasignar" tabindex="-1" role="dialog" aria-labelledby="modalasignarLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="mdltitulo">Asignar Colaborador</h4>
            </div>
            <form method="post" id="cuadrilla_form">
                <div class="modal-body table-responsive" style="overflow-x: auto;">
                    <table id="tblcolaboradores" class="table table-striped table-bordered table-condensed table-hover" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se agregarían los colaboradores dinámicamente -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="asignar_btn" value="add" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>
