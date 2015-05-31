
var app = angular.module('MoringaApp', ['ng-currency']);

app.directive('shipment', function(){
	return {
		restrict: 'E',
		templateUrl: 'public/views/shipment-info.html'
	};
});

app.directive('sale', function(){
	return {
		restrict: 'E',
		templateUrl: 'public/views/sale-info.html'
	};
});

app.directive('collapsablePanel', function(){
	return {
		restrict: 'A',
		link: function($scope, $element, $attrs) {
			var panel = $(element).find('.panel');
			panel.find('.panel-heading').css('cursor', 'pointer').click(function(){
				var content = panel.find('.panel-body');
				if(content.hasClass('collapsed')) {
					content.slideDown().removeClass('collapsed');
				} else {
					content.slideUp().addClass('collapsed');
				}
			});
		}
	};
});

app.directive('datepicker', function(){
	return {
		restrict: 'A',
		link: function($scope, $element, $attrs) {
			$(element).datepicker({
				autoclose: true,
				format: 'dd/MM/yyyy',
				todayHighlight: true,
				language: 'es'
			});
			$(element).datepicker("setDate", new Date());
		}
	};
});

app.directive('ngTruncate', function(){
	return {
		restrict: 'A',
		scope: {
			text: "=ngTruncate",
			limit: "=ngTruncateLimit"
		},
		link: function($scope, $element, $attrs) {
			$element.empty();

			if($scope.text.length > $scope.limit) {
				$element.append($scope.text.substr(0, $scope.limit-1) + '&hellip;');
				$element.append(
					'<i class="fa fa-info-circle"' +
	                	'data-container="body" ' +
	                	'data-toggle="popover" ' +
	                	'data-placement="top" ' +
	                	'data-content="' + $scope.text + '">' +
	                '</i>	'
				);
			} else {
				$element.append($scope.text);
			}
		}
	};
});

app.controller('ShipmentsListCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.orderBy = '-date';
	$scope.shipments = [];
	$scope.isLoading = true;
	$scope.isThereError = false;

    $http.get('index.php?/api/sales')
    .success(function(data) {
        $scope.shipments = data.response;
    })
    .error(function() {
        $scope.isThereError = true;
    }).finally(function() {
        $scope.isLoading = false;
    });


    $scope.markAsShipped = function(shipment) {
        shipment.status = "En Camino";
        shipment.delivery.status = "Enviado";

        shipment.delivery.trackCode = $('#trackCode-' + shipment.id).val();
        $scope.closeModal(shipment);
    };

    $scope.markAsUnshipped = function(shipment) {
        if(confirm("¿Estás seguro de marcar el envío como No Eviado?")) {
            shipment.status = "Enviando";
            shipment.delivery.status = "Pendiente";
            shipment.delivery.trackCode = "";
        }
    };

    $scope.closeModal = function(shipment) {
        $('#trackCodeModal-' + shipment.id).modal('hide');
    };

}]);

app.controller('AddSaleCtrl', ['$scope', function ($scope) {
	$scope.grandTotal = 0;
	$scope.shippingCost = 100;
	$scope.productionCost = 0;
	$scope.commission = 0;
	$scope.discount = 0;
	$scope.earnings = 0;
	$scope.applyDiscount = true;

	$scope.$watchGroup(
		['grandTotal', 'productionCost', 'commission', 'applyDiscount'],
		function() {
			if($scope.applyDiscount) {
				$scope.discount = ($scope.grandTotal - $scope.productionCost - $scope.commission) * 0.30;
				$scope.discount = Math.round($scope.discount * 100) / 100;
			} else {
				$scope.discount = 0;
			}
			$scope.earnings = $scope.grandTotal - $scope.productionCost - $scope.commission - $scope.discount;
		}
	);

}]);

app.controller('SalesListCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.orderBy = '-date';
	$scope.sales = [];
	$scope.isLoading = true;
	$scope.isThereError = false;
	$scope.saleLoading = null;

    $http.get('index.php?/api/sales')
    .success(function(data) {
        $scope.sales = data.response;
    })
    .error(function() {
        $scope.isThereError = true;
    }).finally(function() {
        $scope.isLoading = false;
    });

    $scope.cancelSale = function(sale) {
        if(confirm('¿Estás seguro de cancelar la venta?')) {
            $scope.update_status('mark_as_cancelled', sale);
        }
    };

    $scope.markAsPaid = function(sale) {
    	$scope.update_status('mark_as_paid', sale);
    };

    $scope.markAsUnpaid = function(sale) {
    	$scope.update_status('mark_as_unpaid', sale);
    };

    $scope.requestShipment = function(sale) {
        $scope.saleLoading = sale;
        sale.delivery.comments = $('#comments-' + sale.id).val();

        $http.post('index.php?/api/' + action, {id: sale.id, comments: sale.delivery.comments})
        .success(function(data) {
        	if(data.response) {
        		sale.delivery.comments = data.response[0].delivery.comments;
        		sale.status = data.response[0].status;
        	} else {
        		alert(data.error);
        	}
        })
        .error(function() {
        	alert('Error al tratar de realizar la acción solicitada')
        }).
        finally(function () {
        	$scope.saleLoading = null;
        });

        $scope.closeModal(sale);
    };

    $scope.cancelShipment = function(sale) {
        $scope.update_status('cancel_shipment', sale);
    };

    $scope.markAsEnded = function(sale) {
        $scope.update_status('mark_as_finished', sale);
    };

     $scope.closeModal = function(shipment) {
        $('#commentsModal-' + shipment.id).modal('hide');
    };

    $scope.isSaleLoading = function(sale) {
	    return $scope.saleLoading === sale;
	};

	$scope.update_status = function(action, sale) {
		$scope.saleLoading = sale;

        $http.post('index.php?/api/' + action, {id: sale.id})
        .success(function(data) {
        	if(data.response) {
        		sale.payment.status = data.response[0].payment.status;
        		sale.delivery.status = data.response[0].delivery.status;
        		sale.status = data.response[0].status;
        	} else {
        		alert(data.error);
        	}
        })
        .error(function() {
        	alert('Error al tratar de realizar la acción solicitada')
        }).
        finally(function () {
        	$scope.saleLoading = null;
        });
	};

}]);