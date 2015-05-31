<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Listado de Ventas</h1>

                <div ng-controller="SalesListCtrl">
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
                                    <option value="Pagado">Pagado</option>
                                    <option value="Enviando">Enviando</option>
                                    <option value="Enviado">Enviados</option>
                                    <option value="Finalizado">Finalizados</option>
                                    <option value="Cancelado">Cancelados</option>
                                </select>

                                <label for="courierFilter">Paqueteria: </label>
                                <select name="courierFilter" class="form-control" ng-model="filter.delivery.courier">
                                    <option value="">Cualquiera</option>
                                    <option value="Estafeta">Estafeta</option>
                                    <option value="Correos de México">Correos de México</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger" ng-cloak ng-show="isThereError">
                        Oops! Hubo un error al intentar obtener los datos.
                    </div>
                    <div class="spinner" ng-show="isLoading">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                        <div class="rect4"></div>
                        <div class="rect5"></div>
                    </div>


                    <sale class="sale" ng-repeat="sale in sales | filter : filter | orderBy : orderBy" ng-class="{'loading' : isSaleLoading(sale)}"></sale>

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
