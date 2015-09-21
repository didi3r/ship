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
                                <input type="text" name="search" placeholder="Buscar" class="form-control" ng-model="search.$">
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

                    <div class="table-responsive" ng-cloak ng-show="!isLoading">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dirección</th>
                                    <th>RX</th>
                                    <th>Compras</th>
                                    <th>Invertido</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="customer in customers | filter: search">
                                    <td>
                                        <strong>{{customer.name}}</strong> <br>
                                        <a href="mailto:{{customer.email}}">{{customer.email}}</a> <br>
                                        {{customer.phone}}
                                        <div ng-if="customer.addressee">
                                            <br>
                                            <small>
                                                Recibe: <br>
                                                <strong>{{customer.addressee}}</strong><br>
                                                {{customer.addressee_phone}}
                                            </small>
                                        </div>
                                    </td>
                                    <td style="white-space: pre-line">{{customer.address}}</td>
                                    <td>{{customer.has_rx}}</td>
                                    <td>{{customer.purchases}}</td>
                                    <td>{{customer.total | currency}}</td>
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
