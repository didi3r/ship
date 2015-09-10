<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Clientes</h1>

                <div ng-controller="CustomersListCtrl">
                    <!-- Search -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="search">Buscar:</label>
                                <input type="text" name="search" placeholder="Buscar" class="form-control" ng-model="search.text">


                                <button class="btn btn-primary" ng-click="getSalesCollection(search)">Buscar</button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && !isThereError && total_rows == 0">
                        <i class="fa fa-clock-o"></i>
                        No hay clientes registrados en la base de datos.
                    </div>

                    <div class="alert alert-danger" ng-cloak ng-show="isThereError">
                        <i class="fa fa-exclamation-triangle"></i>
                        Oops! Hubo un error al intentar obtener los datos.
                    </div>

                    <spinner ng-show="isLoading"></spinner>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>RX</th>
                                    <th># Compras</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customers in customer">
                                    <td>
                                        <strong>{{customer.name}}</strong> <br>
                                        {{customer.email}} <br>
                                        {{customer.phone}}
                                    </td>
                                    <td>{{customer.address}}</td>
                                    <td>{{customer.has_rx}}</td>
                                    <td>{{customer.total_purchases}}</td>
                                </tr>
                            </tbody>
                        </table>
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
