<?php $this->load->view('header') ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Historial de Ventas</h1>
                <div ng-controller="HistoryCtrl">
                	<!-- Filters -->
                    <div class="navbar navbar-default">
                        <div class="navbar-form ">
                            <div class="form-group">
                                <label for="since">Desde: </label>
                                <input type="text" id="since" class="form-control" datepicker="<?php echo $start_date ?>" ng-model="sinceDate" value="{{sinceDate}}">

                                <label for="to">Hasta: </label>
                                <input type="text" id="to" class="form-control" datepicker="<?php echo $end_date ?>" ng-model="toDate" value="{{toDate}}">

                            	<button class="btn btn-primary"
                                    ng-click="getHistory(sinceDate, toDate)">
                                    Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                    <spinner ng-show="isLoading"></spinner>
                    <div class="alert alert-info" ng-show="!isLoading && totalRows == 0">
                        <i class="fa fa-exclamation-triangle"></i>
                        No hubo ventas durante ese periodo de tiempo.
                    </div>

                    <p class="text-right" ng-cloak ng-show="!isLoading && totalRows != 0">
                        <i class="fa fa-list-ul"></i> Total: <strong>{{totalRows}}</strong>
                    </p>
	                <table class="table table-striped table-condensed" ng-cloak ng-show="!isLoading && totalRows != 0">
	                	<thead>
	                		<tr>
	                			<th>ID</th>
	                			<th>Fecha</th>
	                			<th>Nombre</th>
	                			<th>Envío</th>
	                			<th>Total</th>
	                			<th>Comisión</th>
	                			<th>Mat. Prima</th>
                                <?php if($this->authentication->is_admin()) : ?>
                                <th>Ingreso</th>
	                			<th>Dividendo</th>
	                			<th>Ganancia</th>
                                <?php endif; ?>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr ng-cloak ng-repeat="sale in filteredSales = (sales) ">
	                			<td>#{{sale.id}}</td>
	                			<td>{{sale.date | date : 'dd/MMM/yyyy'}}</td>
	                			<td>{{sale.name}}</td>
	                			<td ng-init="controller.totalDeliveryCost = controller.totalDeliveryCost + sale.delivery.cost">{{sale.delivery.cost | currency}}</td>
	                			<td class="green">{{sale.payment.total | currency}}</td>
	                			<td class="red">-{{sale.payment.commission | currency}}</td>
	                			<td class="red">-{{sale.payment.rawMaterial | currency}}</td>
                                <?php if($this->authentication->is_admin()) : ?>
                                <td class="green">
                                    {{sale.payment.total - sale.payment.commission - sale.payment.rawMaterial | currency}}
                                </td>
	                			<td class="red">
	                				-{{(sale.payment.total - sale.payment.commission - sale.payment.rawMaterial) * 0.30 | currency}}
	                			</td>
	                			<td class="green">
	                				{{(sale.payment.total - sale.payment.commission - sale.payment.rawMaterial) * 0.70 | currency}}
	                			</td>
                                <?php endif; ?>
	                		</tr>
	                	</tbody>
                		<tfoot>
                			<tr ng-cloak>
                				<td></td>
                				<td></td>
                				<td class="text-right">Total:</td>
                				<td>{{filteredSales | sum:'delivery.cost' | currency}}</td>
                				<td class="green">{{filteredSales | sum:'payment.total' | currency}}</td>
                				<td class="red">{{filteredSales | sum:'payment.commission' | currency}}</td>
                				<td class="red">{{filteredSales | sum:'payment.rawMaterial' | currency}}</td>
                				<?php if($this->authentication->is_admin()) : ?>
                                <td class="green">{{filteredSales | calc:'total' | currency}}</td>
                                <td class="red">{{filteredSales | calc:'splittings' | currency}}</td>
                				<td class="green">{{filteredSales | calc:'earnings' | currency}}</td>
                			    <?php endif; ?>
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