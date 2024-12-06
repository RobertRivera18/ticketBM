<div class="modal fade" id="modalequipos" tabindex="-1" role="dialog" aria-labelledby="modalequiposLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="mdltitulo">Asignar Equipos Informaticos</h4>
            </div>
            <form method="post" id="cuadrilla_form">
                <div class="modal-body table-responsive" style="overflow-x: auto;">
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
                            <!-- Aquí se agregarían los colaboradores dinámicamente -->
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="asignar_btn" value="add" class="btn btn-primary">Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>