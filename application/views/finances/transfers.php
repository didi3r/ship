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
                            <input type="text" readonly="true" class="form-control" datepicker="<?php echo $today ?>" ng-model="transfer.date" required>
                        </div>
                        <div class="col-xs-12 col-sm-3 form-group">
                            <label>Cuenta</label>
                            <select class="form-control" ng-model="transfer.account" required>
                                <option value=""></option>
                            	<option value="MPrima">MPrima</option>
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
                <div ng-controller="TransfersCtrl"
                    <?php if($this->authentication->is_admin()) : ?>
                    ng-init="isAdmin=true"
                    <?php else : ?>
                    ng-init="isAdmin=false"
                    <?php endif; ?>
                >

                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && totalRows == 0">
                        <i class="fa fa-exclamation-triangle"></i>
                        No hay tranferencias registradas actualmente.
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>


                    <div class="row">
                        <div class="col-xs-12 col-md-6" ng-cloak ng-hide="rawTransfers.length == 0">
                            <h4>Materia Prima</h4>

                            <div class="well well-sm">
                                <?php if($this->authentication->is_admin()) : ?>
                                Inversiones: <span class="">{{payedRawMaterial | currency}}</span> <br>
                                <?php endif; ?>
                                Materia Prima: <span class="green">{{totalRawMaterial | currency}}</span> <br>
                                Transferido: <span class="red">-{{transferedRawMaterial | currency}}</span> <br>
                                <strong>Por Transferir: <span class="green">{{pendingRawMaterial | currency}}</span> <br></strong>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Cuenta</th>
                                            <th class="text-right">Total</th>
                                            <th ng-if="isAdmin"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="transfer in rawTransfers = (transfers | filter: {account : 'MPrima'})">
                                            <td>#{{transfer.id}}</td>
                                            <td>{{transfer.date | date : 'dd/MMM/yyyy'}}</td>
                                            <td>{{transfer.account}}</td>
                                            <td class="green text-right">{{transfer.total | currency}}</td>
                                            <th ng-if="isAdmin" class="text-center">
                                                <button class="btn btn-xs btn-danger"
                                                    ng-click="deleteTransfer(transfer)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr ng-cloak>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">Total:</td>
                                            <td class="green text-right">{{rawTransfers | sum:'total' | currency}}</td>
                                            <td ng-if="isAdmin"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>

                        <?php if($this->authentication->is_admin()) : ?>
                        <div class="col-xs-12 col-md-6" ng-cloak ng-hide="splitTransfers.length == 0">
                            <h4>Dividendo</h4>

                            <div class="well well-sm">
                                Dividendo: <span class="green">{{totalSplittings | currency}}</span> <br>
                                Gastos: <span class="red">-{{expensesSplittings | currency}}</span> <br>
                                Transferido: <span class="red">-{{transferedSplittings | currency}}</span> <br>
                                <strong>Por Transferir: <span class="green">{{pendingSplittings | currency}}</span> <br></strong>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Cuenta</th>
                                            <th class="text-right">Total</th>
                                            <th ng-if="isAdmin"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="transfer in splitTransfers = (transfers | filter: {account : 'Aztrid'})">
                                            <td>#{{transfer.id}}</td>
                                            <td>{{transfer.date | date : 'dd/MMM/yyyy'}}</td>
                                            <td>{{transfer.account}}</td>
                                            <td class="green text-right">{{transfer.total | currency}}</td>
                                            <th ng-if="isAdmin" class="text-center">
                                                <button class="btn btn-xs btn-danger"
                                                    ng-click="deleteTransfer(transfer)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr ng-cloak>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">Total:</td>
                                            <td class="green text-right">{{splitTransfers | sum:'total' | currency}}</td>
                                            <th ng-if="isAdmin"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                        <?php endif; ?>

                    <?php if($this->authentication->is_admin()) : ?>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                    <?php endif; ?>

                        <div class="col-xs-12 col-md-6" ng-cloak ng-hide="expensesTransfers.length == 0">
                            <h4>Gastos</h4>

                            <div class="well well-sm">
                                Gastos: <span class="green">{{totalExpenses | currency}}</span> <br>
                                Transferido: <span class="red">-{{transferedExpenses | currency}}</span> <br>
                                <strong>Por Transferir: <span class="green">{{pendingExpenses | currency}}</span> <br></strong>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Cuenta</th>
                                            <th class="text-right">Total</th>
                                            <th ng-if="isAdmin"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="transfer in expensesTransfers = (transfers | filter: {account : 'Victor'})">
                                            <td>#{{transfer.id}}</td>
                                            <td>{{transfer.date | date : 'dd/MMM/yyyy'}}</td>
                                            <td>{{transfer.account}}</td>
                                            <td class="green text-right">{{transfer.total | currency}}</td>
                                            <th ng-if="isAdmin" class="text-center">
                                                <button class="btn btn-xs btn-danger"
                                                    ng-click="deleteTransfer(transfer)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr ng-cloak>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">Total:</td>
                                            <td class="green text-right">{{expensesTransfers | sum:'total' | currency}}</td>
                                            <th ng-if="isAdmin"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


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