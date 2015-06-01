<?php $this->load->view('header'); ?>

<!-- Page Content -->
<div id="page-wrapper" ng-controller="AddSaleCtrl">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Registrar Venta</h1>

                <div class="row">
                    <form class="col-xs-12">
                        <div class="row">
                            <div class="form-group form-inline col-xs-12">
                                <label for="date">
                                    <i class="fa fa-calendar"></i> Fecha de Venta:
                                </label>
                                <input name="date" type="text" class="form-control" datepicker ng-model="date" value="<?php echo isset($date) ? $date : '' ?>">
                            </div>
                        </div>

                        <h4>Información del Comprador</h4>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="customerName">Nombre</label>
                                <input type="customerName" class="form-control" id="customerName" value="<?php echo isset($name) ? $name : '' ?>" placeholder="Nombre del Comprador">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="MLUsername">Usuario en ML</label>
                                <input type="text" class="form-control" id="MLUsername" value="<?php echo isset($user) ? $user : '' ?>" placeholder="Ejem. VENTAS_ND">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" value="<?php echo isset($email) ? $email : '' ?>" placeholder="correo@servidor.com">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="telephone">Número Telefónico</label>
                                <input type="text" class="form-control" id="telephone" value="<?php echo isset($phone) ? $phone : '' ?>" placeholder="(123) 123 4567">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label for="address">Dirección de Envío</label>
                                <textarea class="form-control" id="address" rows="5"><?php echo isset($delivery) ? $delivery['address'] : '' ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group form-inline col-xs-12">
                                <input type="checkbox" name="hasAddressee" id="hasAddressee" ng-model="hasAddressee" <?php echo isset($addressee) ? 'checked' : '' ?>>
                                <label for="hasAddressee"> Recibe una persona diferente</label>

                                <div ng-show="hasAddressee">
                                    <label for="addressee">Nombre:</label>
                                    <input type="text" class="form-control" name="addressee" value="<?php echo isset($delivery) ? $delivery['addressee'] : '' ?>" placeholder="Nombre de quien recibe">

                                    <label for="addressee_phone">Teléfono: </label>
                                    <input type="text" class="form-control" name="addressee_phone" value="<?php echo isset($delivery) ? $delivery['phone'] : '' ?>" placeholder="(123) 123 4567">
                                </div>
                            </div>
                        </div>

                        <h4>Detalle de la Venta</h4>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <label for="cart">Productos Vendidos</label>
                                <textarea class="form-control" id="cart" rows="5" placeholder="Lista de productos separados por un salto de linea"><?php echo isset($package) ? implode('\n', $package) : '' ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="total">Total: </label>
                                <input name="total" type="text" class="form-control" ng-model="grandTotal" ng-currency value="<?php echo isset($payment) ? $payment['total'] : '' ?>">
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="shippingCost">Costo de Envío: </label>
                                <input name="shippingCost" type="text" class="form-control" ng-model="shippingCost" ng-currency value="<?php echo isset($delivery['cost']) ? $delivery['cost'] : '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="commission">Comisión ML: </label>
                                <input name="commission" type="text" class="form-control" ng-model="commission" ng-currency value="<?php echo isset($payment) ? $payment['commission'] : '' ?>">
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="productionCost">Materia Prima: </label>
                                <input name="productionCost" type="text" class="form-control" ng-model="productionCost" ng-currency value="<?php echo isset($payment) ? $payment['rawMaterial'] : '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-6">
                                <label for="discount">¿Aplica Dividendo?: </label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <input name="discount" id="discount" type="checkbox" <?php echo isset($split_earnings) ? $split_earnings ? 'checked' : '' : 'checked' ?> ng-model="applyDiscount">
                                    </div>

                                    <input type="text" class="form-control" readonly ng-model="discount" ng-currency>
                                </div>
                            </div>
                            <div class="form-group form-inline col-xs-12 col-md-6">
                                <label for="earnings">Ganancia: </label>
                                <input type="text" class="form-control" readonly ng-model="earnings" ng-currency>

                            </div>
                        </div>

                        <h4>Pago</h4>
                        <div class="row">
                            <div class="form-group form-inline col-xs-12 col-md-4">
                                <label for="status">Estado: </label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Pendiente" <?php echo isset($payment) && $payment['status'] == 'Pendiente' ? 'selected' : ''?>>Pendiente</option>
                                    <option value="Pagado" <?php echo isset($payment) && $payment['status'] == 'Pagado' ? 'selected' : ''?>>Pagado</option>
                                    <option value="Cancelado" <?php echo isset($payment) && $payment['status'] == 'Cancelado' ? 'selected' : ''?>>Cancelado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <br><br>
                            <button type="button" class="btn btn-default">Cancelar</button>
                            <button type="submit" class="btn btn-success">Guardar</button>
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
