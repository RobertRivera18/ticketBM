<div id="modalmantenimiento" class="modal fade bd-example-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="font-icon-close-2"></i>
                </button>
                <h4 class="modal-title" id="mdltitulo"></h4>
            </div>
            <form method="post" id="usuario_form">
                <div class="modal-body">
                    <input type="hidden" id="usu_id" name="usu_id">

                    <div class="form-group">
                        <label class="form-label" for="usu_nom">Nombre</label>
                        <input type="text" class="form-control" id="usu_nom" name="usu_nom" placeholder="Ingrese Nombre" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="usu_ape">Apellido</label>
                        <input type="text" class="form-control" id="usu_ape" name="usu_ape" placeholder="Ingrese Apellido" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="usu_correo">Correo Electrónico</label>
                        <input type="email" class="form-control" id="usu_correo" name="usu_correo" placeholder="test@test.com" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="usu_cedula">Cédula de Identidad</label>
                        <input type="text" class="form-control" id="usu_cedula" name="usu_cedula" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="usu_pass">Contraseña</label>
                        <input type="text" class="form-control" id="usu_pass" name="usu_pass" placeholder="************" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="rol_id">Rol</label>
                        <select class="select2 form-control" id="rol_id" name="rol_id">
                            <option value="1">Usuario</option>
                            <option value="2">Soporte</option>
                            <option value="3">Operador</option>
                            <option value="4">Monitor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="empresa_id">Empresa</label>
                        <select class="select2 form-control" id="empresa_id" name="empresa_id">
                            <option value="1">Claro</option>
                            <option value="2">CNEL</option>
                            <option value="3">Administrativo</option>
                        </select>
                    </div>

                    <!-- Campos agregados para IP y MAC -->
                    <div class="form-group">
                        <label class="form-label" for="ip">Dirección IP (Opcional)</label>
                        <input type="text" class="form-control" id="ip" name="ip" placeholder="Ejemplo: 192.168.1.1">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mac">Dirección MAC (Opcional)</label>
                        <input type="text" class="form-control" id="mac" name="mac" placeholder="Ejemplo: AA:BB:CC:DD:EE:FF">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-rounded btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" name="action" value="add" class="btn btn-rounded btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
