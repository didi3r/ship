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
                                <input type="text" id="since" class="form-control" datepicker="<?php echo date('Y-m-d', strtotime('last friday')) ?>" ng-model="sinceDate">

                                <label for="to">Hasta: </label>
                                <input type="text" id="to" class="form-control" datepicker="<?php echo date('Y-m-d', strtotime('next thursday')) ?>" ng-model="toDate">

                            	<button class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>

	                <table class="table table-striped table-condensed">
	                	<thead>
	                		<tr>
	                			<th>ID</th>
	                			<th>Fecha</th>
	                			<th>Nombre</th>
	                			<th>Envío</th>
	                			<th>Total</th>
	                			<th>Comisión</th>
	                			<th>Mat. Prima</th>
	                			<th>Ingreso</th>
	                			<th>Dividendo</th>
	                			<th>Ganancia</th>
	                		</tr>
	                	</thead>
	                	<tbody>
	                		<tr ng-cloak ng-repeat="sale in filteredSales = (sales | limitTo: 10) ">
	                			<td>#{{sale.id}}</td>
	                			<td>{{sale.date | date : 'dd/MM/yyyy'}}</td>
	                			<td>{{sale.name}}</td>
	                			<td ng-init="controller.totalDeliveryCost = controller.totalDeliveryCost + sale.delivery.cost">{{sale.delivery.cost | currency}}</td>
	                			<td>{{sale.payment.total | currency}}</td>
	                			<td class="red">-{{sale.payment.commission | currency}}</td>
	                			<td class="red">-{{sale.payment.rawMaterial | currency}}</td>
	                			<td class="green">
	                				{{sale.payment.total - sale.payment.commission - sale.payment.rawMaterial | currency}}
	                			</td>
	                			<td class="red">
	                				-{{(sale.payment.total - sale.payment.commission - sale.payment.rawMaterial) * 0.30 | currency}}
	                			</td>
	                			<td class="green">
	                				{{(sale.payment.total - sale.payment.commission - sale.payment.rawMaterial) * 0.70 | currency}}
	                			</td>
	                		</tr>
	                	</tbody>
                		<tfoot>
                			<tr ng-cloak>
                				<td></td>
                				<td></td>
                				<td class="text-right">Total:</td>
                				<td>{{filteredSales | sum:'delivery.cost' | currency}}</td>
                				<td>{{filteredSales | sum:'payment.total' | currency}}</td>
                				<td class="red">{{filteredSales | sum:'payment.commission' | currency}}</td>
                				<td class="red">{{filteredSales | sum:'payment.rawMaterial' | currency}}</td>
                				<td class="green">{{filteredSales | calc:'total' | currency}}</td>
                				<td class="red">{{filteredSales | calc:'splittings' | currency}}</td>
                				<td class="green">{{filteredSales | calc:'earnings' | currency}}</td>
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