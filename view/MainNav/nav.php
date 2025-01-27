<?php
if ($_SESSION["rol_id"] == 1 || $_SESSION["rol_id"] == 3) {
?>
    <nav class="side-menu">
        <ul class="side-menu-list">
            <li class="blue-dirty">
                <a href="..\Home\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Inicio</span>
                </a>
            </li>
            
            <li class="blue-dirty">
                <a href="..\NuevoTicket\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Nuevo Ticket</span>
                </a>
            </li>

            <li class="blue-dirty">
                <a href="..\ConsultarTicket\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Consultar Ticket</span>
                </a>
            </li>


        </ul>
    </nav>
<?php
} else {
?>
    <nav class="side-menu">
        <ul class="side-menu-list">
            <li class="blue-dirty">
                <a href="..\Home\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Inicio</span>
                </a>
            </li>

            <li class="blue-dirty">
                <a href="..\NuevoTicket\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Nuevo Ticket</span>
                </a>
            </li>



            <li class="blue-dirty dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-tasks"></span>
                    <span class="lbl">Mantenimiento Usuarios</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="blue-dirty">
                        <a href="..\MntUsuario\">
                            <span class="lbl">Usuarios del Sistema</span>
                        </a>
                    </li>
                    <li class="blue-dirty">
                        <a href="..\UsuariosEquipos\">
                            <span class="lbl">Usuarios-Equipos</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="blue-dirty">
                <a href="..\ConsultarTicket\">
                    <span class="glyphicon glyphicon-th"></span>
                    <span class="lbl">Consultar Ticket</span>
                </a>
            </li>
            <li class="blue-dirty">
                <a href="..\MntEquipos\">
                    <span class="glyphicon glyphicon-hdd"></span>
                    <span class="lbl">Equipos Informaticos</span>
                </a>
            </li>

            <li class="blue-dirty dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="glyphicon glyphicon-wrench"></span>
                    <span class="lbl">Mantenimiento Cuadrillas Colaboradores</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="blue-dirty">
                        <a href="..\MntCuadrillas\">

                            <span class="lbl">Creacion de Cuadrillas</span>
                        </a>
                    </li>
                   

                    <li class="blue-dirty">
                        <a href="..\MntColaboradores\">
                            <span class="lbl">Mantenimiento Colaboradores</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="blue-dirty">
                <a href="..\Documentos\">
                    <span class="glyphicon glyphicon-credit-card"></span>
                    <span class="lbl">Generar Actas Entregas Credenciales</span>
                </a>
            </li>

            <li class="blue-dirty">
                <a href="..\AsigChip\">
                    <span class="glyphicon glyphicon-credit-card"></span>
                    <span class="lbl">Generar Actas Cuadrillas-Chip</span>
                </a>
            </li>



            <li class="blue-dirty">
                <a href="..\Inspeccion\">
                    <span class="glyphicon glyphicon-alert"></span>
                    <span class="lbl">Generar Permiso Trabajos Especiales</span>
                </a>
            </li>

        </ul>
    </nav>
<?php
}
?>