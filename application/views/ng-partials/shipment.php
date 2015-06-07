<spinner></spinner>
<div  class="panel panel-default"
    ng-class="{
        'panel-success' : shipment.delivery.status == 'Enviado'
    }">

    <div class="panel-heading">
        <i class="fa fa-calendar-o"></i> {{shipment.date}} |
        <i class="fa"
            ng-class="{
                'fa-clock-o' : shipment.delivery.status == 'Pendiente',
                'fa-check-circle' : shipment.delivery.status == 'Enviado'
            }">
        </i>
        {{shipment.delivery.status}}

        <span class="buttons">
            <button class="btn btn-xs btn-success"
                data-toggle="modal" data-target="#trackCodeModal-{{shipment.id}}"
                ng-show="shipment.status == 'Enviando'
                && shipment.delivery.status == 'Pendiente'">
                <i class="fa fa-truck"></i> Enviado
            </button>
            <button class="btn btn-xs btn-default"
                ng-show="shipment.status == 'En Camino'
                && shipment.delivery.status == 'Enviado'"
                ng-click="markAsUnshipped(shipment)">
                <i class="fa fa-times-circle"></i> No Enviado
            </button>
        </span>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Envio -->
            <div class="info col-xs-12 col-sm-6 col-md-5">
                <h4>Detalles del Envío</h4>
                <div ng-hide="shipment.delivery.addressee" ng-cloak>
                    <i class="fa fa-user"></i> Recibe: {{shipment.name}} <br>
                </div>
                <div ng-show="shipment.delivery.addressee">
                    <i class="fa fa-user"></i> Recibe: {{shipment.delivery.addressee}} <br>
                </div>
                <i class="fa fa-home"></i> Dirección: <br>
                <blockquote>{{shipment.delivery.address }}</blockquote>

                <div ng-hide="shipment.delivery.phone" ng-cloak>
                    <i class="fa fa-phone"></i> Teléfono: {{shipment.phone}}<br>
                </div>
                <div ng-show="shipment.delivery.phone">
                    <i class="fa fa-phone"></i> Teléfono: {{shipment.delivery.phone}}<br>
                </div>

                <div ng-if="shipment.files">
                    <a href="{{file.url}}" target="_blank" class="btn btn-xs btn-info btn-guia"
                        ng-repeat="file in shipment.files track by $index">
                        Descargar Guía {{$index + 1}}
                    </a>
                </div>

            </div>

            <!-- Paquete -->
            <div class="package col-xs-12 col-sm-6 col-md-4">
                <h4>Paquete</h4>
                <i class="fa fa-shopping-cart"></i> Contenido del Paquete:
                <ul>
                    <li ng-repeat="item in shipment.package track by $index">
                        {{item}}
                    </li>
                </ul>
                <i class="fa fa-truck"></i> Paqueteria: {{shipment.delivery.courier}}

                <div ng-show="shipment.delivery.status == 'Enviado'">
                    <i class="fa fa-barcode"></i> Código: <strong>{{shipment.delivery.trackCode}}</strong>
                </div>
            </div>

            <div class="notes col-xs-12" ng-show="shipment.delivery.comments">
                <h4>Notas</h4>
                <p>{{shipment.delivery.comments}}</p>
            </div>

        </div>
        <!-- /.row -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="trackCodeModal-{{shipment.id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Ingresa el Códido de Rastreo</h4>
            </div>
            <div class="modal-body">
                <div class="form-inline text-center">
                    <div class="form-group">
                        <label for="trackCodetrackCode-{{shipment.id}}">Código de Rastreo: </label>
                        <input id="trackCode-{{shipment.id}}" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cerrar
                </button>
                <button type="button" class="btn btn-primary"
                    ng-click="markAsShipped(shipment)">
                    Guardar
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->