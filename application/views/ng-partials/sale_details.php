<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Detalles de la Venta</h4>
            </div>
            <div class="modal-body">
                <h4>Datos de Comprador</h4>
                <div class="row">
                    <div class="col-xs-12 col-lg-6">
                        <i class="fa fa-user"></i> <strong>{{sale.name}}</strong><br>
                        <div ng-show="sale.user">
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            ({{sale.user}}</span>)
                        </div>
                        <div ng-show="sale.email">
                            <br>
                            <i class="fa fa-envelope-o"></i> {{sale.email}}
                        </div>
                        <div ng-show="sale.phone">
                            <i class="fa fa-phone"></i> Teléfono: {{sale.phone}}
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6">
                        <div ng-hide="sale.delivery.addressee">
                            <i class="fa fa-user"></i> Recibe: {{sale.name}}
                        </div>
                        <div ng-show="sale.delivery.addressee">
                            <i class="fa fa-user"></i> Recibe: {{sale.delivery.addressee}}
                        </div>
                        <blockquote>{{sale.delivery.address}}</blockquote>
                        <div ng-hide="!sale.phone || sale.delivery.phone">
                            <i class="fa fa-phone"></i> Teléfono: {{sale.phone}}
                        </div>
                        <div ng-show="sale.delivery.phone">
                            <i class="fa fa-phone"></i> Teléfono: {{sale.delivery.phone}}
                        </div>
                    </div>
                </div>

                <h4>Detalle del Producto</h4>
                <div class="row">
                    <div class="col-xs-12 col-lg-6">
                        <i class="fa fa-shopping-cart"></i> Paquete:
                        <ul>
                            <li ng-repeat="product in sale.package track by $index">
                                {{product}}
                            </li>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-lg-6">
                        <i class="fa fa-truck"></i>
                        Paquetería: {{sale.delivery.courier}} <br>

                        <i class="fa fa-clock-o"></i>
                        Estatus: {{sale.delivery.status}}

                        <div ng-show="sale.delivery.status == 'Enviado'">
                            <div ng-if="sale.delivery.date">
                                <i class="fa fa-calendar"></i>
                                Enviado el: {{sale.delivery.date | date : 'dd/MMMM/yyyy'}}
                            </div>
                            <i class="fa fa-barcode"></i>
                            Rastreo:
                            <strong>{{sale.delivery.trackCode}}</strong>
                        </div>
                    </div>
                </div>

                <h4>Detalle del Pago</h4>
                <div class="row">
                    <div class="col-xs-12 col-lg-6">
                        <i class="fa fa-money"></i> {{sale.payment.status}} <br><br>
                        <i class="fa fa-usd"></i> Comisión: <span class="red">-{{sale.payment.commission | currency}}</span> <br>
                        <i class="fa fa-usd"></i> M. Prima: <span class="red">-{{sale.payment.rawMaterial | currency}}</span> <br>
                    </div>
                    <div class="col-xs-12 col-lg-6">
                        <br><br>
                        <i class="fa fa-usd"></i> Envío: {{sale.delivery.cost | currency}} <br>
                        <strong class="total">
                            <i class="fa fa-usd"></i> Total: <span class="green">{{sale.payment.total | currency}}</span> <br>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->