<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Envíos</h1>

                <div ng-controller="ShipmentsListCtrl">
                    <!-- Filters -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="search">Buscar:</label>
                                <input type="text" name="search" placeholder="Buscar" class="form-control" ng-model="filter.$">

                                <label for="orderBy">Mostrar: </label>
                                <select name="orderBy" class="form-control" ng-model="orderBy">
                                    <option value="-date">Nuevos</option>
                                    <option value="date">Antiguos</option>
                                </select>

                                <label for="statusFilter">Estado: </label>
                                <select name="statusFilter" class="form-control" ng-model="filter.status">
                                    <option value="">Todos</option>
                                    <option value="Pendiente">Pendientes</option>
                                    <option value="Enviado">Enviados</option>
                                    <option value="Cancelado">Cancelados</option>
                                </select>

                                <label for="courierFilter">Paquetería: </label>
                                <select name="courierFilter" class="form-control" ng-model="filter.courier">
                                    <option value="">Cualquiera</option>
                                    <option value="Estfeta">Estfeta</option>
                                    <option value="Correos de México">Correos de México</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger" ng-cloack ng-show="isThereError">
                        Oops! Hubo un error al intentar obtener los datos.
                    </div>
                    <div class="spinner" ng-show="isLoading">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>

                    <!-- Shipment list -->
                    <shipment class="shipment" ng-repeat="shipment in shipments | filter: filter | orderBy: order" ng-class="{'loading' : isSaleLoading(shipment)}"></shipment>
                    <!-- /Shipment list -->

                    <div class="pagination" ng-show="total_pages > 1">
                        <button class="btn btn-primary"
                            ng-click="prevPage()"
                            ng-hide="current_page <= 1">
                            Anterior
                        </button>
                        <button class="btn btn-default"
                            ng-repeat="i in getPagesNumber() track by $index"
                            ng-class="{'btn-primary' : $index + 1 == current_page}"
                            ng-click="goToPage($index + 1)">
                            {{$index+1}}
                        </button>
                        <button class="btn btn-primary"
                            ng-click="nextPage()"
                            ng-hide="current_page >= total_pages">
                            Siguiente
                        </button>
                    </div>
                </div>

            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<?php $this->load->view('footer') ?>