
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
    $scope.result_limit = 10;
    $scope.current_page = 1;
    $scope.total_pages = 1;

	$scope.orderBy = '-date';
	$scope.shipments = [];
	$scope.isLoading = true;
	$scope.isThereError = false;
    $scope.saleLoading = null;

    $scope.getSalesCollection =  function() {
        $scope.shipments = [];
        $scope.isLoading = true;
        $scope.isThereError = false;

        $http.get('index.php?/api/shipments/' + $scope.current_page + '/' + $scope.result_limit)
        .success(function(data) {
            $scope.shipments = data.response;
            $scope.total_pages = Math.ceil(data.total_rows / $scope.result_limit);
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    $scope.getPagesNumber = function() {
        return new Array($scope.total_pages);
    }

    $scope.nextPage = function() {
        $scope.current_page = $scope.current_page >= $scope.total_pages ? $scope.total_pages : $scope.current_page += 1;
        $scope.getSalesCollection();
    };

    $scope.prevPage = function() {
        $scope.current_page = $scope.current_page <= 1 ? 1 : $scope.current_page -= 1;
        $scope.getSalesCollection();
    };

    $scope.goToPage = function(number) {
        if(number <= $scope.total_pages && number >= 1) {
            $scope.current_page = number;
        }
        $scope.getSalesCollection();
    };

    $scope.isSaleLoading = function(sale) {
        return $scope.saleLoading === sale;
    };

    $scope.markAsShipped = function(shipment) {
        $scope.saleLoading = shipment;
        shipment.delivery.trackCode = $('#trackCode-' + shipment.id).val();

        $http.post('index.php?/api/mark_as_shipped', {id: shipment.id, code: shipment.delivery.trackCode})
        .success(function(data) {
            if(data.response) {
                shipment.delivery.trackCode = data.response[0].delivery.trackCode;
                shipment.delivery.status = data.response[0].delivery.status;
                shipment.status = data.response[0].status;
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

        $scope.closeModal(shipment);
    };

    $scope.markAsUnshipped = function(shipment) {
        if(confirm("¿Estás seguro de marcar el envío como No Eviado?")) {
            $scope.update_status('mark_as_unshipped', shipment);
        }
    };

    $scope.closeModal = function(shipment) {
        $('#trackCodeModal-' + shipment.id).modal('hide');
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

    // Initial List Population
    $scope.getSalesCollection();
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
    $scope.result_limit = 20;
    $scope.current_page = 1;
    $scope.total_pages = 1;

    $scope.orderBy = '-date';
	$scope.sales = [];
	$scope.isLoading = true;
	$scope.isThereError = false;
	$scope.saleLoading = null;

    $scope.getSalesCollection =  function() {
        $scope.sales = [];
        $scope.isLoading = true;
        $scope.isThereError = false;

        $http.get('index.php?/api/sales/' + $scope.current_page + '/' + $scope.result_limit)
        .success(function(data) {
            $scope.sales = data.response;
            $scope.total_pages = Math.ceil(data.total_rows / $scope.result_limit);
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    $scope.getPagesNumber = function() {
        return new Array($scope.total_pages);
    }

    $scope.nextPage = function() {
        $scope.current_page = $scope.current_page >= $scope.total_pages ? $scope.total_pages : $scope.current_page += 1;
        $scope.getSalesCollection();
    };

    $scope.prevPage = function() {
        $scope.current_page = $scope.current_page <= 1 ? 1 : $scope.current_page -= 1;
        $scope.getSalesCollection();
    };

    $scope.goToPage = function(number) {
        if(number <= $scope.total_pages && number >= 1) {
            $scope.current_page = number;
        }
        $scope.getSalesCollection();
    };

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

        $http.post('index.php?/api/request_shipment', {id: sale.id, comments: sale.delivery.comments})
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

    // Initial List Population
    $scope.getSalesCollection();
}]);