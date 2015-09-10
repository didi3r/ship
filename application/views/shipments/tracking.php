<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Rastreo de Envíos</h1>

                <div ng-controller="TrackablesCtrl">
                    <!-- Filters -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="search">Buscar:</label>
                                <input type="text" name="search" placeholder="Buscar" class="form-control" ng-model="filter.$">


                                <label for="courierFilter">Paquetería: </label>
                                <select name="courierFilter" class="form-control" ng-model="filter.courier">
                                    <option value="">Cualquiera</option>
                                    <option value="Estfeta">Estfeta</option>
                                    <option value="Correos de México">Correos de México</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && !isThereError && total_rows == 0">
                        <i class="fa fa-clock-o"></i>
                        No hay envíos en camino por el momento para rastrar, revisa más tarde.
                    </div>

                    <div class="alert alert-danger" ng-cloak ng-show="isThereError">
                        <i class="fa fa-exclamation-triangle"></i>
                        Oops! Hubo un error al intentar obtener los datos.
                    </div>

                    <div class="search-info" ng-cloak ng-hide="isLoading || total_rows == 0">
                        <p>
                            Mostrando envíos del
                            <strong>{{result_limit * (current_page - 1) > 0 ? result_limit * (current_page - 1) : 1 }}</strong>
                            al
                            <strong>{{result_limit * (current_page - 1) + result_limit < total_rows ? result_limit * (current_page - 1) + result_limit : total_rows}}</strong>,
                            de un total de
                            <strong>{{total_rows}}</strong>
                        </p>
                    </div>

                    <spinner ng-show="isLoading"></spinner>

                    <!-- Shipment list -->
                    <tackable class="shipment" ng-repeat="shipment in shipments | filter: filter" ng-class="{'loading' : isSaleLoading(shipment)}"></tackable>
                    <!-- /Shipment list -->

                    <div class="pagination" ng-cloak ng-show="total_pages > 1">
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