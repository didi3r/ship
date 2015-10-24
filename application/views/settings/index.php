<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12" ng-controller="SettingsCtrl">
                <h1 class="page-header">Configuración</h1>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#users" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-users"></i> Usuarios
                            </a>
                        </li>
                    </ul>

                    <br>
                    <!-- Tab panes -->
                    <div class="tab-content">

                        <!-- START: User Settings -->
                        <div class="tab-pane fade active in" id="users">
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills">
                                <li class="active"><a href="#users-list" data-toggle="tab">Lista de Usuarios</a>
                                </li>
                                <li><a href="#users-add" data-toggle="tab">Agregar Usuario</a>
                                </li>
                            </ul>

                            <br>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="userus-list" ng-init="getUsers()">
                                    <h4>Lista de Usuarios</h4>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-condensed" ng-cloak ng-show="!isLoading">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px">Rol</th>
                                                    <th>Email</th>
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                    <th style="width: 150px" class="text-right">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="user in users | orderBy: -role">
                                                    <td style="width: 50px">
                                                        <i class="fa fa-user" ng-class="{'fa-user-plus' : user.role == '99' }"></i>
                                                    </td>
                                                    <td>{{user.email}}</td>
                                                    <td>{{user.name}}</td>
                                                    <td>{{user.lastName}}</td>
                                                    <td class="text-right" style="width: 150px">
                                                        <button class="btn btn-xs btn-default">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-default">
                                                            <i class="fa fa-key"></i>
                                                        </button>
                                                        <button class="btn btn-xs btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="users-add">
                                    <h4>Agregar Usuario</h4>

                                    <form>
                                        <div class="form-group">
                                            <label for="name">Nombre: </label>
                                            <input type="text" name="name" value="" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="lastname">Apellido: </label>
                                            <input type="text" name="lastname" value="" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email: </label>
                                            <input type="text" name="email" value="" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Conraseña</label>
                                            <input type="text" name="password" value="" class="form-control">
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </div>
                        <!-- END: User Settings -->

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
