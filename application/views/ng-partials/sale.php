<spinner></spinner>
<div  class="panel panel-default"
    ng-class="{
        'panel-success' : sale.status == 'Finalizado',
        'panel-warning' : sale.status == 'Pagado',
        'panel-info' : sale.status == 'Enviando' || sale.status == 'En Camino',
        'panel-danger' : sale.status == 'Cancelado'
    }">
    <div class="panel-heading">
        ID #{{sale.id}}, Vendida el {{sale.date | date : 'dd/MMMM/yyyy'}} |

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

        <div class="buttons"ng->
            <button class="btn btn-xs btn-default"
                    ng-show="sale.status == 'Pendiente'
                    && sale.payment.status == 'Pendiente'"
                    ng-click="markAsPaid(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-money"></i> Marcar Pagado
            </button>
            <button class="btn btn-xs btn-default"
                    ng-show="sale.status == 'Pagado'
                    && sale.payment.status == 'Pagado'"
                    ng-click="markAsUnpaid(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-money"></i> Marcar No Pagado
            </button>
            <button class="btn btn-xs btn-default"
                    ng-show="sale.status == 'Pagado'
                    && sale.delivery.status == 'Pendiente'"
                    ng-disabled="isSaleLoading(sale)"
                    ng-click="showFileUploader(sale)">
                <i class="fa fa-barcode"></i>

                {{sale.files ? 'Modificar Guía' : 'Adjuntar Guía'}}
            </button>
            <button class="btn btn-xs btn-default"
                    data-toggle="modal" data-target="#commentsModal-{{sale.id}}"
                    ng-show="sale.status == 'Pagado'
                    && sale.delivery.status == 'Pendiente'"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-truck"></i> Solicitar Envío
            </button>
            <button class="btn btn-xs btn-default"
                    ng-show="sale.status == 'Enviando'
                    && sale.delivery.status == 'Pendiente'"
                    ng-click="cancelShipment(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-truck"></i> Cancelar Envío
            </button>
            <button class="btn btn-xs btn-default"
                    ng-show="sale.status != 'En Camino'
                    && sale.status != 'Cancelado'
                    && sale.status != 'Finalizado'"
                    ng-click="updateSale(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-pencil"></i>
            </button>
            <button class="btn btn-xs btn-danger"
                    ng-show="sale.status != 'En Camino'
                    && sale.status != 'Cancelado'
                    && sale.status != 'Finalizado'"
                    ng-click="cancelSale(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-times"></i>
            </button>
            <button class="btn btn-xs btn-success"
                    ng-show="sale.status == 'En Camino'
                    && sale.delivery.status == 'Enviado'"
                    ng-click="markAsEnded(sale)"
                    ng-disabled="isSaleLoading(sale)">
                <i class="fa fa-check"></i>
            </button>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-lg-3">
                <i class="fa fa-user"></i> <strong>{{sale.name}}</strong><br>
                <div ng-show="sale.user">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    (<span ng-truncate="sale.user" ng-truncate-limit="15"></span>)
                </div>
                <div ng-show="sale.email">
                    <br>
                    <i class="fa fa-envelope-o"></i> <span ng-truncate="sale.email" ng-truncate-limit="23"></span>
                </div>
            </div>
            <div class="col-xs-12 col-lg-3">
                <i class="fa fa-truck"></i>
                {{sale.delivery.courier}}
                <blockquote>{{sale.delivery.address}}</blockquote>
                <div ng-show="sale.delivery.status == 'Enviado'">
                    <i class="fa fa-barcode"></i>
                    <span ng-if="sale.delivery.date">
                        ({{sale.delivery.date | date : 'dd/MMMM/yyyy'}})
                    </span>
                    <strong>{{sale.delivery.trackCode}}</strong>
                </div>
                <span ng-init="checkEstafetaStatus(sale)">
                    <i class="fa" ng-class="{
                        'fa-exclamation-triangle red' : sale.estafetaStatus == 'No hay información disponible',
                        'fa-clock-o' : sale.estafetaStatus == 'Pendiente en Tránsito',
                        'fa-check green' : sale.estafetaStatus == 'Entregado'}">
                    </i>
                    {{sale.estafetaStatus}}
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
                <i class="fa fa-usd"></i>
                <strong class="total">Total: {{sale.payment.total + sale.shippingCost | currency}}</strong> <br>
                Envío: {{sale.delivery.cost | currency}} <br>
                Ganancia: {{(sale.payment.total - sale.payment.rawMaterial - sale.payment.commission)  | currency}}

                <i class="fa fa-info-circle payment-breakdown"
                data-container="body" data-toggle="popover" data-placement="top" data-html="true" data-content="
                    Producto: {{sale.payment.total | currency}}<br>
                    Comisión: -{{sale.payment.commission | currency}}<br>
                    M. Prima: -{{sale.payment.rawMaterial | currency}}<br>
                    Dividendo: -{{(sale.payment.total - sale.payment.rawMaterial - sale.payment.commission) * 0.30 | currency}}">
                </i>


                <div class="payment-status">
                    <i class="fa fa-money"></i>
                    {{sale.payment.status}}
                </div>
            </div>
        </div>
        <div class="row" ng-show="sale.delivery.comments">
            <div class="col-xs-12">
                <br>
                <i class="fa fa-truck"></i> <strong>Comentarios del Envío:</strong> <br>
                {{sale.delivery.comments}}
                | <a href="">Eliminar</a>
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
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary"
                    ng-click="requestShipment(sale)">
                    Guardar
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->