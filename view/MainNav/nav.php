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
            6
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

            <li class="blue-dirty">
                <a href="..\MntUsuario\">
                    <span class="glyphicon glyphicon-user"></span>
                    <span class="lbl">Mantenimiento Usuario</span>
                </a>
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
                        <a href="..\AsigCuadrillas\">
                            <span class="lbl">Asignacion Cuadrillas-Colaboradores</span>
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
                    <span class="glyphicon glyphicon-check"></span>
                    <span class="lbl">Generar Actas</span>
                </a>
            </li>

        </ul>
    </nav>
<?php
}
?>