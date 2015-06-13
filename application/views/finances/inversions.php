<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Inversiones</h1>

                <?php if($this->authentication->is_admin()) : ?>
                <h3>Registrar Inversiones</h3>

                <div class="row add-expense-form"
                     ng-controller="AddInversionCtrl"
                     ng-class="{'loading' : isLoading}">

                    <div class="alert alert-success" ng-cloak ng-show="!isLoading && isSaved">
                        <i class="fa fa-check-circle"></i>
                        Registro guardado correctamente
                    </div>

                    <spinner></spinner>

                    <form name="AddInversionForm" id="AddInversionForm">
                        <div class="col-xs-12 col-sm-3 col-lg-2 form-group">
                            <label>Fecha</label>
                            <input type="text" class="form-control" datepicker="<?php echo $today ?>" ng-model="inversion.date" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-lg-6 form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" placeholder="Descripción de la inversión" ng-model="inversion.description" required>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-lg-2 form-group">
                            <label>Total</label>
                            <input type="text" class="form-control" ng-model="inversion.total" ng-currency required>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-2 form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary" style="display:block"
                                ng-disabled="AddInversionForm.$invalid"
                                ng-click="saveInversion(inversion)">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <h3>Listado de Inversiones</h3>
                <div ng-controller="InversionsCtrl">

                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && totalRows == 0">
                        <i class="fa fa-exclamation-triangle"></i>
                        Actualmente no hay inversiones
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>
                    <table class="table table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
	                	<thead>
	                		<tr>
	                			<th>ID</th>
	                			<th>Fecha</th>
	                			<th>Descipción</th>
	                			<th>Total</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr ng-repeat="inversion in filteredInversions = (inversions | orderBy: 'date')">
                                <td>#{{inversion.id}}</td>
                                <td>{{inversion.date | date : 'dd/MMM/yyyy'}}</td>
                                <td>{{inversion.description}}</td>
                                <td class="{{inversion.total > 0 ? 'green' : 'red'}}">{{inversion.total | currency}}</td>
                            </tr>
	                	</tbody>
                		<tfoot>
                			<tr ng-cloak>
                				<td></td>
                				<td></td>
                                <td class="text-right">Total:</td>
                				<td class="{{totalInversions > 0 ? 'green' : 'red'}}">{{filteredInversions | sum:'total' | currency}}</td>
                            </tr>
                		</tfoot>
	                </table>
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