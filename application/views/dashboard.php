<?php $this->load->view('header') ?>
<div id="page-wrapper" ng-controller="DashboardCtrl">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Escritorio</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-plus fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" ng-cloak>{{totalSalesThisWeek}}</div>
                                <div>Esta Semana</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->authentication->is_admin() ? site_url('sales/add') : site_url('finances') ?>">
                        <div class="panel-footer">
                            <span class="pull-left">Añadir Venta</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-truck fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" ng-cloak>{{totalPendingShipments}}</div>
                                <div>Envios Pendientes</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo site_url('shipments') ?>">
                        <div class="panel-footer">
                            <span class="pull-left">Ver Envíos</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" ng-cloak>{{totalEnded}}</div>
                                <div>Concretadas</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->authentication->is_admin() ? site_url('sales') : site_url('finances') ?>">
                        <div class="panel-footer">
                            <span class="pull-left">Ver Ventas</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-times fa-4x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" ng-cloak>{{totalCancelled}}</div>
                                <div>Canceladas</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->authentication->is_admin() ? site_url('sales') : site_url('finances') ?>">
                        <div class="panel-footer">
                            <span class="pull-left">Ver Ventas</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- /.row -->
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i> Ventas por semana
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <canvas id="line" class="chart chart-bar"
                            data="historyChart.data"
                            labels="historyChart.labels"
                            series="historyChart.series"
                            colours="historyChart.colours"
                            legend="true"
                            click="onClick">
                        </canvas>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

            </div>
            <!-- /.col-lg-8 -->
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-pie-chart fa-fw"></i> Total de ventas
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <canvas id="doughnut" class="chart chart-doughnut"
                            data="salesChart.data"
                            labels="salesChart.labels"
                            colours="salesChart.colours"
                            legend="true">
                        </canvas>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-list-ul fa-fw"></i> Compradores más activos
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="list-group">
                            <div class="list-group-item" ng-repeat="buyer in mostActiveBuyers">
                                {{buyer.name}}
                                <span class="pull-right text-muted small">
                                    <em>{{buyer.purchases}}</em>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->

            </div>
            <!-- /.col-lg-4 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php $this->load->view('footer') ?>