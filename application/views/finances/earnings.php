<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Ganancias</h1>

                <div ng-controller="EarningsCtrl">
                	<!-- Filters -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="since">Desde: </label>
                                <input type="text" id="since" class="form-control" datepicker="<?php echo $start_date ?>" ng-model="sinceDate" value="{{sinceDate}}">

                                <label for="to">Hasta: </label>
                                <input type="text" id="to" class="form-control" datepicker="<?php echo $end_date ?>" ng-model="toDate" value="{{toDate}}">

                            	<button class="btn btn-primary"
                                    ng-click="getEarnings(sinceDate, toDate)">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-cloak ng-show="!isLoading && totalRows == 0">
                        <i class="fa fa-exclamation-triangle"></i>
                        No hubo ganancias durante ese periodo de tiempo.
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>
                    <table class="table table-striped table-condensed" ng-cloak ng-hide="isLoading || totalRows == 0">
	                	<thead>
	                		<tr>
	                			<th>ID</th>
	                			<th>Fecha</th>
	                			<th>Descripción</th>
	                			<th>Total</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr ng-repeat="earning in filteredEarnings = earnings">
                                <td>#{{earning.id}}</td>
                                <td>{{earning.date | date : 'dd/MMM/yyyy'}}</td>
                                <td>{{earning.description}}</td>
                                <td ng-if="earning.type == 'Venta'" class="green">{{earning.total | currency}}</td>
                                <td ng-if="earning.type == 'Gasto'" class="red">{{earning.total | currency}}</td>
                            </tr>
	                	</tbody>
                		<tfoot>
                			<tr ng-cloak>
                				<td></td>
                				<td></td>
                                <td class="text-right">Total:</td>
                				<td class="{{earningsTotal > 0 ? 'green' : 'red'}}">{{filteredEarnings | sum:'total' | currency}}</td>
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