<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Gastos</h1>
                <h3>Registrar Gasto</h3>

                <div class="row add-expense-form"
                     ng-controller="AddExpenseCtrl"
                     ng-class="{'loading' : isLoading}">

                    <div class="alert alert-success" ng-cloak ng-show="!isLoading && isSaved">
                        <i class="fa fa-check-circle"></i>
                        Gasto guardado correctamente
                    </div>

                    <spinner></spinner>

                    <form name="AddExpenseForm" id="AddExpenseForm">
                        <div class="col-xs-12 col-sm-3 col-lg-2 form-group">
                            <label>Fecha</label>
                            <input type="text" class="form-control" datepicker="<?php echo date('Y-m-d') ?>" ng-model="expense.date" required>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-lg-6 form-group">
                            <label>Descripción</label>
                            <input type="text" class="form-control" placeholder="Descripción del gasto" ng-model="expense.description" required>
                        </div>
                        <div class="col-xs-12 col-sm-3 col-lg-2 form-group">
                            <label>Total</label>
                            <input type="text" class="form-control" ng-model="expense.total" ng-currency required>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-2 form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary" style="display:block"
                                ng-disabled="AddExpenseForm.$invalid"
                                ng-click="saveExpense(expense)">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>

                <h3>Lista de Gastos</h3>
                <div ng-controller="ExpensesCtrl">
                	<!-- Filters -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="since">Desde: </label>
                                <input type="text" id="since" class="form-control" datepicker="<?php echo $start_date ?>" ng-model="sinceDate" value="{{sinceDate}}">

                                <label for="to">Hasta: </label>
                                <input type="text" id="to" class="form-control" datepicker="<?php echo $end_date ?>" ng-model="toDate" value="{{toDate}}">

                            	<button class="btn btn-primary"
                                    ng-click="getExpenses(sinceDate, toDate)">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && totalRows == 0">
                        No hubo gastos durante ese periodo de tiempo.
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>
                    <table class="table table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
	                	<thead>
	                		<tr>
	                			<th>ID</th>
	                			<th>Fecha</th>
	                			<th>Nombre</th>
	                			<th>Total</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr ng-repeat="expense in filteredExpenses = expenses">
                                <td>#{{expense.id}}</td>
                                <td>{{expense.date | date : 'dd/MMM/yyyy'}}</td>
                                <td>{{expense.description}}</td>
                                <td class="red">-{{expense.total | currency}}</td>
                            </tr>
	                	</tbody>
                		<tfoot>
                			<tr ng-cloak>
                				<td></td>
                				<td></td>
                                <td class="text-right">Total:</td>
                				<td class="red">-{{filteredExpenses | sum:'total' | currency}}</td>
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