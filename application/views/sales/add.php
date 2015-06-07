<?php $this->load->view('header'); ?>

<!-- Page Content -->
<div id="page-wrapper"
     ng-controller="<?php echo $edit ? 'UpdateSaleCtrl' : 'AddSaleCtrl' ?>"
     <?php echo $this->uri->segment(3) ? 'ng-init="populateForm('. $this->uri->segment(3) .')"' : '' ?> >
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Registrar Venta</h1>

                <div ng-cloak ng-show="!isThereError && isSaved">
                    <div class="alert alert-success" >
                        <i class="fa fa-check-circle"></i>
                        Venta guardada correctamente. <a href="<?php echo site_url('sales'); ?>">Ir a la lista de ventas.</a>
                    </div>

                    <p class="text-center">
                        <a href="<?php echo site_url('sales/add'); ?>" class="btn btn-primary">Agregar otra venta</a>
                        <a href="<?php echo site_url('sales'); ?>" class="btn btn-default">Ir a la lista de ventas</a>
                    </p>
                </div>

                <div class="row add-sale-form"
                    ng-class="{'loading' : isLoading}"
                    ng-hide="!isThereError && isSaved">
                    <spinner></spinner>
                    <form name="saleForm" id="saleForm" novalidate class="col-xs-12">
                        <div class="row">
                            <div class="form-group form-inline col-xs-12">
                                <label for="date">
                                    <i class="fa fa-calendar"></i> Fecha de Venta:
                                </label>
                                <input name="date" type="text" class="form-control" datepicker ng-model="sale.date" required>
                            </div>
                        </div>

                        <h4>Información del Comprador</h4>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="customerName">Nombre</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="customerName" placeholder="Nombre del Comprador" ng-model="sale.name" required>
                                    <a href="" class="input-group-addon" ng-click="showCustomerSearch()">
                                        <i class="fa fa-user-plus"></i>
                                    </a>
                                </div>
                                <div load-customer-info show="showModal"></div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="MLUsername">Usuario en ML</label>
                                <input type="text" class="form-control" id="MLUsername" placeholder="Ejem. VENTAS_ND" ng-model="sale.user">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="correo@servidor.com" ng-model="sale.email">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="telephone">Número Telefónico</label>
                                <input type="text" class="form-control" id="telephone" placeholder="(123) 123 4567" ng-model="sale.phone">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label for="address">Dirección de Envío</label>
                                <textarea class="form-control" id="address" rows="5" ng-model="sale.delivery.address"></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group form-inline col-xs-12">
                                <label for="courier">Paquetería: </label>
                                <select class="form-control" id="courier" ng-model="sale.delivery.courier">
                                    <option value="Estafeta">Estafeta</option>
                                    <option value="Correos de México">Correos de México</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group form-inline col-xs-12">
                                <input type="checkbox" name="hasAddressee" id="hasAddressee" ng-model="hasAddressee">
                                <label for="hasAddressee"> Recibe una persona diferente</label>

                                <div ng-show="hasAddressee">
                                    <label for="addressee">Nombre:</label>
                                    <input type="text" class="form-control" name="addressee" placeholder="Nombre de quien recibe" ng-model="sale.delivery.addressee">

                                    <label for="addressee_phone">Teléfono: </label>
                                    <input type="text" class="form-control" name="addressee_phone" placeholder="(123) 123 4567" ng-model="sale.delivery.phone">
                                </div>
                            </div>
                        </div>

                        <h4>Detalle de la Venta</h4>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label for="cart">Productos Vendidos</label>
                                <textarea class="form-control" id="cart" rows="5" placeholder="Lista de productos separados por un salto de linea" ng-model="sale.package" required array-to-list></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="total">Total </label><br>
                                <input name="total" type="text" class="form-control" ng-model="sale.payment.total" required ng-currency>
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="shippingCost">Costo de Envío </label><br>
                                <input name="shippingCost" type="text" class="form-control" ng-model="sale.delivery.cost" ng-currency>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="commission">Comisión ML </label><br>
                                <input name="commission" type="text" class="form-control" ng-model="sale.payment.commission" ng-currency>
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="productionCost">Materia Prima </label><br>
                                <input name="productionCost" type="text" class="form-control" ng-model="sale.payment.rawMaterial" ng-currency>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="discount">¿Aplica Dividendo? </label><br>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <input name="discount" id="discount" type="checkbox" ng-model="sale.split_earnings">
                                    </div>

                                    <input type="text" class="form-control" readonly ng-model="discount" ng-currency>
                                </div>
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="earnings">Ganancia </label><br>
                                <input type="text" class="form-control" readonly ng-model="earnings" ng-currency>

                            </div>
                        </div>

                        <div class="row">
                            <br><br>
                            <button type="button" class="btn btn-default"
                                onClick="history.go(-1);">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-success"
                                ng-click="saveSale(sale)"
                                ng-disabled="saleForm.$invalid">
                                Guardar
                            </button>
                        </div>
                    </form>

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
