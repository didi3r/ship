<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Transferencias</h1>

                <?php if($this->authentication->is_admin()) : ?>
                <h3>Registrar Transferencia</h3>

                <div class="row add-expense-form"
                     ng-controller="AddTransferCtrl"
                     ng-class="{'loading' : isLoading}">

                    <div class="alert alert-success" ng-cloak ng-show="!isLoading && isSaved">
                        <i class="fa fa-check-circle"></i>
                        Registro guardado correctamente
                    </div>

                    <spinner></spinner>

                    <form name="AddTransferForm" id="AddTransferForm">
                        <div class="col-xs-12 col-sm-3 form-group">
                            <label>Fecha</label>
                            <input type="text" class="form-control" datepicker="<?php echo $today ?>" ng-model="transfer.date" required>
                        </div>
                        <div class="col-xs-12 col-sm-3 form-group">
                            <label>Cuenta</label>
                            <select class="form-control" ng-model="transfer.account" required>
                            	<option value="Victor">Victor</option>
                            	<option value="Aztrid">Aztrid</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-3 form-group">
                            <label>Total</label>
                            <input type="text" class="form-control" ng-model="transfer.total" ng-currency required>
                        </div>
                        <div class="col-xs-12 col-sm-3 form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary" style="display:block"
                                ng-disabled="AddTransferForm.$invalid"
                                ng-click="saveTransfer(transfer)">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <h3>Listado de Transferencias</h3>
                <div ng-controller="TransfersCtrl">
                    <div class="row" ng-cloak>
                        <div class="col-xs-6">
                            <div class="well well-sm">
                                <!-- Inversiones: <span class="">{{payedRawMaterial | currency}}</span> <br> -->
                                <?php if($this->authentication->is_admin()) : ?><br><?php endif; ?>
                                Materia Prima: <span class="green">{{totalRawMaterial | currency}}</span> <br>
                                Transferido: <span class="red">-{{transferedRawMaterial | currency}}</span> <br>
                                <strong>Por Transferir: <span class="green">{{pendingRawMaterial | currency}}</span> <br></strong>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="well well-sm">
                                <?php if($this->authentication->is_admin()) : ?>
                                Dividendo: <span class="green">{{totalSplittings | currency}}</span> <br>
                                Gastos: <span class="red">-{{expensesSplittings | currency}}</span> <br>
                                <?php endif; ?>
                                <?php if(!$this->authentication->is_admin()) : ?><br><?php endif; ?>
                                Transferido: <span class="red">-{{transferedSplittings | currency}}</span> <br>
                                <strong>Por Transferir: <span class="green">{{pendingSplittings | currency}}</span> <br></strong>
                            </div>
                        </div>
                    </div>

                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && totalRows == 0">
                    	<i class="fa fa-exclamation-triangle"></i>
                        No hay tranferencias registradas actualmente.
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>


                    <div class="row">
                        <div class="col-xs-12 col-md-6" ng-cloack ng-hide="victorTransfers.length == 0">
                            <table class="table table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Cuenta</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="transfer in victorTransfers = (transfers | filter: {account : 'Victor'})">
                                        <td>#{{transfer.id}}</td>
                                        <td>{{transfer.date | date : 'dd/MMM/yyyy'}}</td>
                                        <td>{{transfer.account}}</td>
                                        <td class="green">{{transfer.total | currency}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr ng-cloak>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">Total:</td>
                                        <td class="green">{{victorTransfers | sum:'total' | currency}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-xs-12 col-md-6" ng-cloack ng-hide="aztridTransfers.length == 0">
                            <table class="table table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Cuenta</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="transfer in aztridTransfers = (transfers | filter: {account : 'Aztrid'})">
                                        <td>#{{transfer.id}}</td>
                                        <td>{{transfer.date | date : 'dd/MMM/yyyy'}}</td>
                                        <td>{{transfer.account}}</td>
                                        <td class="green">{{transfer.total | currency}}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr ng-cloak>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">Total:</td>
                                        <td class="green">{{aztridTransfers | sum:'total' | currency}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.row -->

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