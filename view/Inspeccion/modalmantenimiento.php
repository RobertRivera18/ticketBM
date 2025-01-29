<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="inspeccion_form" enctype="multipart/form-data">
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



                <div class="form-group">
                    <h4 class="form-label text-center">INSPECCIÓN DE ÁREA</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Aspecto</th>
                                <th>SI</th>
                                <th>N/A</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Zona Resbaladiza</td>
                                <td><input type="radio" name="zona_resbaladiza" value="SI" required></td>
                                <td><input type="radio" name="zona_resbaladiza" value="N/A" checked></td>
                            </tr>
                            <tr>
                                <td>Zona con Desnivel</td>
                                <td><input type="radio" name="zona_con_desnivel" value="SI" required></td>
                                <td><input type="radio" name="zona_con_desnivel" value="N/A" checked></td>
                            </tr>
                            <tr>
                                <td>Hueco o Piso Dañado</td>
                                <td><input type="radio" name="hueco_piso_danado" value="SI" required></td>
                                <td><input type="radio" name="hueco_piso_danado" value="N/A" checked></td>
                            </tr>
                            <tr>
                                <td>Instalación en Mal Estado</td>
                                <td><input type="radio" name="instalacion_mal_estado" value="SI" required></td>
                                <td><input type="radio" name="instalacion_mal_estado" value="N/A" checked></td>
                            </tr>

                            <tr>
                                <td>Cables:Desconectados o Expuestos</td>
                                <td><input type="radio" name="desconectados_expuestos" value="SI" required></td>
                                <td><input type="radio" name="desconectados_expuestos" value="N/A" checked></td>
                            </tr>
                            <tr>
                                <td>Escalera o Equipos en Buen Estado</td>
                                <td><input type="radio" name="escalera_buen_estado" value="SI" required></td>
                                <td><input type="radio" name="escalera_buen_estado" value="N/A" checked></td>
                            </tr>
                            <tr>
                                <td>Señalética Instalada</td>
                                <td><input type="radio" name="senaletica_instalada" value="SI" required></td>
                                <td><input type="radio" name="senaletica_instalada" value="N/A" checked></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Fin de la Inspección de Área -->






                <!---Equipos de seguridad----->



                <div class="form-group">
                    <h4 class="form-label text-center">EQUIPOS DE SEGURIDAD Y CONTRAINCENDIOS A EMPLEAR</h4>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>BOTAS</td>
                                <td><input type="checkbox" name="botas" value="1"></td>
                                <td>LINEA DE VIDA</td>
                                <td><input type="checkbox" name="linea_vida" value="1"></td>
                            </tr>
                            <tr>
                                <td>CHALECO</td>
                                <td><input type="checkbox" name="chaleco" value="1"></td>
                                <td>ARNES</td>
                                <td><input type="checkbox" name="arnes" value="1"></td>
                            </tr>
                            <tr>
                                <td>PROTECCION AUDITIVA</td>
                                <td><input type="checkbox" name="proteccion_auditiva" value="1"></td>
                                <td rowspan="2">Otros (especificar)</td>
                                <td rowspan="2">
                                    <textarea class="form-control" name="otros_equipos" id="otros_equipos" rows="2"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>PROTECCION VISUAL</td>
                                <td><input type="checkbox" name="proteccion_visual" value="1"></td>
                            </tr>
                        </tbody>
                    </table>
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

                <!-- Input file para imagen -->
                <div class="form-group flex">
                    <label for="imagen">Cargar Imagen</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" class="form-control">
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" id="guardarBtn" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>