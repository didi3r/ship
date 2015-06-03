<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <br>
        <ul class="nav" id="side-menu">
            <li>
                <a href="<?php echo site_url(); ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-shopping-cart fa-fw"></i> Ventas<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo site_url('sales/add'); ?>">Registrar Venta</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('sales'); ?>">Listado de Ventas</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="<?php echo site_url('shipments'); ?>"><i class="fa fa-truck fa-fw"></i> Envíos</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-line-chart fa-fw"></i> Finanzas<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="<?php echo site_url('finances'); ?>">Historial de Ventas</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('finances/expenses'); ?>">Gastos</a>
                    </li>
                    <li>
                        <a href="<?php echo site_url('finances/transfers'); ?>">Transferencias</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->