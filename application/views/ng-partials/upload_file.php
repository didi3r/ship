<div class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Adjuntar Archivos</h4>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    <button class="btn btn-primary"
                        ngf-select ng-model="files" ngf-multiple="multiple"
                        ng-disabled="isLoading">
                        Seleccionar Archivos
                    </button>
                </p>

                <div class="progress" ng-clock ng-show="progress != 0">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{progress}}%;">
                        {{progress}}%
                    </div>
                </div>

                <table class="table table-stripped" ng-clock ng-if="sale.files && sale.files.length > 0">
                    <thead>
                        <th>ID</th>
                        <th>Archvio</th>
                        <th class="text-right">Borrar</th>
                    </thead>
                    <tbody>
                        <tr ng-repeat="file in sale.files">
                            <td>#{{file.id}}</td>
                            <td><a href="{{file.url}}" target="_blank">{{file.name}}</a></td>
                            <td class="text-right">
                                <button class="btn btn-xs btn-danger"
                                    ng-click="deleteFile(sale.id, file.id)">
                                    <i class="fa fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->