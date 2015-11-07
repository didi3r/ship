<spinner></spinner>
<div  class="panel panel-default"
    ng-class="{
        'panel-success' : sale.status == 'Finalizado',
        'panel-warning' : sale.status == 'Pagado',
        'panel-info' : sale.status == 'Enviando' || sale.status == 'En Camino',
        'panel-danger' : sale.status == 'Cancelado'
    }">
    <div class="panel-heading">
        <i class="fa fa-shopping-cart"></i> #{{sale.id}} |
        <span ng-if="sale.wc_id"><i class="fa fa-wordpress"></i> #{{sale.wc_id}} | </span>
        <i class="fa fa-calendar"></i> {{sale.date | date : 'dd/MMMM/yyyy'}}
        <span class="hidden-xs">|</span>
        <br class="visible-xs">
        <i class="fa"
            ng-class="{
                      'fa-clock-o' : sale.status == 'Pendiente',
                      'fa-money' : sale.status == 'Pagado',
                      'fa-truck' : sale.status == 'Enviando' || sale.status == 'En Camino',
                      'fa-check-circle' : sale.status == 'Finalizado',
                      'fa-times-circle' : sale.status == 'Cancelado'
            }">
        </i>
        {{sale.status}}

        <div class="buttons hidden-xs hidden-sm" ng-show="isAdmin" ng-cloak>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status == 'Pendiente'
                    && sale.payment.status == 'Pendiente'"
                    ng-click="paypalRequest(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-paypal"></i> Solicitud de Pago
            </button>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status == 'Pendiente'
                    && sale.payment.status == 'Pendiente'"
                    ng-click="markAsPaid(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-money"></i> Marcar Pagado
            </button>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status == 'Pagado'
                    && sale.payment.status == 'Pagado'"
                    ng-click="markAsUnpaid(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-money"></i> Marcar No Pagado
            </button>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status == 'Pagado'
                    && sale.delivery.status == 'Pendiente'"
                    ng-disabled="isSaleLoading(sale)"
                    ng-click="showFileUploader(sale)">
                <i class="fa fa-barcode"></i>

                {{sale.files ? 'Modificar Guía' : 'Adjuntar Guía'}}
            </button>
            <button class="btn btn-xs btn-default"
                    data-toggle="modal" data-target="#commentsModal-{{sale.id}}"
                    ng-if="sale.status == 'Pagado'
                    && sale.delivery.status == 'Pendiente'"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-truck"></i> Solicitar Envío
            </button>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status == 'Enviando'
                    && sale.delivery.status == 'Pendiente'"
                    ng-click="cancelShipment(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-truck"></i> Cancelar Envío
            </button>
            <button class="btn btn-xs btn-default"
                    ng-if="sale.status != 'En Camino'
                    && sale.status != 'Cancelado'
                    && sale.status != 'Finalizado'"
                    ng-click="updateSale(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-pencil"></i>
            </button>
            <button class="btn btn-xs btn-warning"
                    ng-if="sale.status != 'En Camino'
                    && sale.status != 'Cancelado'
                    && sale.status != 'Finalizado'"
                    ng-click="cancelSale(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-times"></i>
            </button>
            <button class="btn btn-xs btn-success"
                    ng-if="sale.status == 'En Camino'
                    && sale.delivery.status == 'Enviado'"
                    ng-click="markAsEnded(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-check"></i>
            </button>
            <button class="btn btn-xs btn-danger"
                    ng-click="deleteSale(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-trash"></i>
            </button>

        </div>

        <!-- Responsive Dropdown -->
        <div class="btn-group pull-right visible-xs visible-sm">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Acción <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu slidedown">
                <li>
                    <a ng-if="sale.status == 'Pendiente'
                        && sale.payment.status == 'Pendiente'"
                        ng-click="paypalRequest(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-paypal"></i> Solicitud de Pago
                    </a>
                </li>
                <li>
                    <a ng-if="sale.status == 'Pendiente'
                        && sale.payment.status == 'Pendiente'"
                        ng-click="markAsPaid(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-money"></i> Marcar Pagado
                    </a>
                </li>
                <li>
                    <a ng-if="sale.status == 'Pagado'
                        && sale.payment.status == 'Pagado'"
                        ng-click="markAsUnpaid(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-money"></i> Marcar No Pagado
                    </a>
                </li>
                <li>
                    <a ng-if="sale.status == 'Pagado'
                        && sale.delivery.status == 'Pendiente'"
                        ng-disabled="isSaleLoading(sale)"
                        ng-click="showFileUploader(sale)">
                        <i class="fa fa-barcode"></i>
                        {{sale.files ? 'Modificar Guía' : 'Adjuntar Guía'}}
                    </a>
                </li>
                <li>
                    <a
                        data-toggle="modal" data-target="#commentsModal-{{sale.id}}"
                        ng-if="sale.status == 'Pagado'
                        && sale.delivery.status == 'Pendiente'"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-truck"></i> Solicitar Envío
                    </a>
                </li>
                <li>
                    <a ng-if="sale.status == 'Enviando'
                        && sale.delivery.status == 'Pendiente'"
                        ng-click="cancelShipment(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-truck"></i> Cancelar Envío
                    </a>
                </li>
                <li>
                    <a ng-if="sale.status != 'En Camino'
                        && sale.status != 'Cancelado'
                        && sale.status != 'Finalizado'"
                        ng-click="updateSale(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-pencil"></i> Editar
                    </a>
                </li>
                <li class="divider"></li>
                <li class="success">
                    <a ng-if="sale.status == 'En Camino'
                        && sale.delivery.status == 'Enviado'"
                        ng-click="markAsEnded(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-check"></i> Finalizada
                    </a>
                </li>
                <li class="warning">
                    <a ng-if="sale.status != 'En Camino'
                        && sale.status != 'Cancelado'
                        && sale.status != 'Finalizado'"
                        ng-click="cancelSale(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-times"></i> Cancelada
                    </a>
                </li>
                <li class="danger">
                    <a ng-click="deleteSale(sale)"
                        ng-disabled="isSaleLoading(sale)">
                        <i class="fa fa-trash"></i> Eliminar
                    </a>
                </li>
            </ul>
        </div>
        <!-- / Responsive Dropdown -->
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-lg-3">
                <i class="fa fa-user"></i> <strong>{{sale.name}}</strong><br>
                <div ng-show="sale.user">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    (<span ng-truncate="sale.user" ng-truncate-limit="15"></span>)
                </div>
                <br>
                <span ng-if="sale.phone">
                    <i class="fa fa-phone"></i> {{sale.phone}}
                    <i class="fa {{sale.smsNotifications ? 'fa-bell-o' : 'fa-bell-slash-o'}}"></i>
                </span>
                <div ng-show="sale.email">
                    <br>
                    <i class="fa fa-envelope-o"></i> <span ng-truncate="sale.email" ng-truncate-limit="23"></span>
                </div>
            </div>
            <div class="col-xs-12 col-lg-3">
                <i class="fa fa-truck" ng-if="sale.delivery.method != 'Dia Siguiente'"></i>
                <i class="fa fa-plane" ng-if="sale.delivery.method == 'Dia Siguiente'"></i>
                {{sale.delivery.courier}}
                <span ng-if="sale.delivery.method"> ({{sale.delivery.method}})</span>
                <blockquote>{{sale.delivery.address}}</blockquote>
                <span ng-if="sale.delivery.hasRX">
                    <i class="fa fa-exclamation"></i> Esta dirección tiene RX
                </span>
                <div ng-show="sale.delivery.status == 'Enviado'">
                    <i class="fa fa-barcode"></i>
                    <strong>{{sale.delivery.trackCode}}</strong><br>
                    <span ng-if="sale.delivery.date">
                        <i class="fa fa-calendar"></i>
                        {{sale.delivery.date | date : 'dd/MMMM/yyyy'}}
                    </span>
                </div>

                <span ng-init="checkDeliveryStatus(sale)">
                    <div ng-show="sale.status == 'En Camino' && sale.delivery.trackCode && !sale.deliveryStatus">
                        <i class="fa fa-refresh fa-spin"></i> Cargando Estatus del envío
                    </div>
                    <div ng-show="sale.deliveryStatus">
                        <i class="fa" ng-class="{
                            'fa-exclamation-triangle red' : sale.deliveryStatus == 'No hay información disponible.',
                            'fa-clock-o' : sale.deliveryStatus == 'Pendiente en transito',
                            'fa-check green' : sale.deliveryStatus == 'Entregado'}">
                        </i>
                        {{sale.deliveryStatus}}
                    </div>
                </span>
            </div>
            <div class="col-xs-12 col-lg-3">
                <i class="fa fa-shopping-cart"></i> Paquete:
                <ul>
                    <li ng-repeat="product in sale.package track by $index">
                        {{product}}
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 col-lg-3">
                <strong class="total">Total: {{(sale.payment.total -- sale.delivery.cost) | currency}}</strong> <br>
                <small>Pedido: {{sale.payment.total | currency}} </small><br>
                <small>Envío: {{sale.delivery.cost | currency}} </small>
                <div ng-cloak ng-if="isAdmin">
                    <small><strong>Ganancia: {{(sale.payment.total - (sale.fromInversions ? 0 : sale.payment.rawMaterial) - sale.payment.commission) * (sale.splitEarnings ? 0.70 : 1)  | currency}}</strong></small>

                    <i class="fa fa-info-circle payment-breakdown"
                    data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="
                        <small>Pedido: {{sale.payment.total | currency}} </small><br>
                        <small>Comisión: -{{sale.payment.commission | currency}} </small><br>
                        <small>M. Prima: -{{sale.fromInversions ? 0 : sale.payment.rawMaterial | currency}} </small><br>
                        <small>Dividendo: -{{sale.splitEarnings ? (sale.payment.total - sale.payment.rawMaterial - sale.payment.commission) * 0.30 : 0 | currency}} </small><br>
                        ">
                    </i>
                </div>


                <div class="payment-status">
                    <i class="fa fa-bank" ng-if="sale.payment.method == 'Deposito'"></i>
                    <i class="fa fa-credit-card" ng-if="sale.payment.method == 'Tarjeta'"></i>
                    {{sale.payment.status}}<br>
                    <span ng-show="sale.payment.status == 'Pagado' && sale.payment.date">
                        <i class="fa fa-calendar"></i>
                        {{sale.payment.date | date : 'dd/MMMM/yyyy'}}
                    </span>
                </div>
            </div>
        </div>
        <div class="row" ng-show="sale.delivery.comments">
            <div class="col-xs-12">
                <br>
                <i class="fa fa-truck"></i>
                <strong>Comentarios del Envío:</strong>
                | <a href="" ng-click="deleteComments(sale)">Eliminar</a> <br>
                <p read-more ng-model="sale.delivery.comments" words="true" length="40"></p>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="commentsModal-{{sale.id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Comentarios del Envío</h4>
            </div>
            <div class="modal-body">
                <textarea id="comments-{{sale.id}}" class="form-control">{{sale.delivery.comments}}</textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary"
                    ng-click="requestShipment(sale)">
                    Solicitar Envío
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
