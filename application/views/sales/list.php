<?php $this->load->view('header') ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#search').keypress(function (e) {
        if (e.which == 13) {
            $('#submit').click();
            return false;    //<---- Add this line
        }
    });
});
</script>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Listado de Ventas</h1>

                <div ng-controller="SalesListCtrl"
                    <?php if($this->authentication->is_admin()) : ?>
                    ng-init="isAdmin=true"
                    <?php else : ?>
                    ng-init="isAdmin=false"
                    <?php endif; ?>
                >
                    <!-- Search -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="search">Buscar:</label>
                                <input type="text" name="search" placeholder="Buscar" id="search" class="form-control" ng-model="search.text">

                                <label for="orderBy">Mostrar: </label>
                                <select name="orderBy" class="form-control" ng-model="search.order">
                                    <option value="-date">Nuevos</option>
                                    <option value="date">Antiguos</option>
                                </select>

                                <label for="statusFilter">Estado: </label>
                                <select name="statusFilter" class="form-control" ng-model="search.status">
                                    <option value="">Todos</option>
                                    <option value="Pendiente">Pendientes</option>
                                    <option value="Pagado">Pagado</option>
                                    <option value="Enviando">Enviando</option>
                                    <option value="En Camino">En Camino</option>
                                    <option value="Finalizado">Finalizados</option>
                                    <option value="Cancelado">Cancelados</option>
                                </select>

                                <label for="courierFilter">Paqueteria: </label>
                                <select name="courierFilter" class="form-control" ng-model="search.courier">
                                    <option value="">Cualquiera</option>
                                    <option value="Estafeta">Estafeta</option>
                                    <option value="Correos de México">Correos de México</option>
                                </select>

                                <button class="btn btn-primary" id="submit" ng-click="getSalesCollection(search)">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && !isThereError && total_rows == 0">
                        <i class="fa fa-clock-o"></i>
                        No hay ventas pendientes por el momento, revisa más tarde.
                    </div>

                    <div class="alert alert-danger" ng-cloak ng-show="isThereError">
                        <i class="fa fa-exclamation-triangle"></i>
                        Oops! Hubo un error al intentar obtener los datos.
                    </div>

                    <spinner ng-show="isLoading"></spinner>

                    <div class="search-info" ng-cloak ng-hide="isLoading || total_rows == 0">
                        <p>
                            Mostrando ventas de la
                            <strong>{{result_limit * (current_page - 1) > 0 ? result_limit * (current_page - 1) : 1 }}</strong>
                            a la
                            <strong>{{result_limit * (current_page - 1) + result_limit < total_rows ? result_limit * (current_page - 1) + result_limit : total_rows}}</strong>,
                            de un total de
                            <strong>{{total_rows}}</strong>
                        </p>
                    </div>

                    <div ng-cloak ng-hide="isLoading || total_rows == 0">
                        <sale class="sale" ng-repeat="sale in sales" ng-class="{'loading' : isSaleLoading(sale)}"></sale>
                    </div>

                    <div upload-file-modal="selectedSale" show="showModal"></div>

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
