<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Buscar Comprador</h4>
            </div>
            <div class="modal-body customer-search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" ng-model="search" ng-keyup="triggerSearch(search)">
                        <a href="" class="input-group-addon" ng-click="getSearchResults(search)">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>

                <div class="search-box" ng-class="{'loading' : isLoading}">
                    <spinner></spinner>

                    <div class="alert alert-info" ng-cloak ng-show="results.lenght == 0">
                        <i class="fa fa-exclamation-triangle"></i>
                        No se encontraron resultados
                    </div>

                    <table class="table" ng-cloak ng-hide="results.lenght == 0">
                        <thead>
                            <th>Nombre</th>
                            <th>Dirección</th>
                        </thead>
                    </table>

                    <div class="results" ng-cloak ng-hide="results.lenght == 0">
                        <table class="table table-striped">
                            <tbody>
                                <tr ng-repeat="result in results">
                                    <td>
                                        <a href="" ng-click="populateCustomerInfo(result)">{{result.name}}</a><br>
                                        {{result.email}}
                                    </td>
                                    <td><blockquote>{{result.address}}</blockquote></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->