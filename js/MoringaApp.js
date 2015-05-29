
var app = angular.module('MoringaApp', ['ng-currency']);

app.directive('shipment', function(){
	return {
		restrict: 'E',
		templateUrl: 'views/shipment-info.html'
	};
});

app.directive('sale', function(){
	return {
		restrict: 'E',
		templateUrl: 'views/sale-info.html'
	};
});

app.directive('collapsablePanel', function(){
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
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
		link: function(scope, element, attrs) {
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

app.controller('MenuCtrl', ['$scope', function ($scope) {
    $scope.$on('$includeContentLoaded', function(event) {
        $('#side-menu').metisMenu();
        
        $('button[data-target=".navbar-collapse"]').click();
        setTimeout(function(){
            $('button[data-target=".navbar-collapse"]').click();
        }, 500);
    });
}]);

app.controller('ShipmentsListCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.orderBy = '-date';
	$scope.shipments = [];

    $http.get('data/data.json').success(function(data) {
        $scope.shipments = data.items;
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

    $http.get('data/data.json').success(function(data) {
        $scope.sales = data.items;
    });
    
    $scope.cancelSale = function(sale) {
        if(confirm('¿Estás seguro de cancelar la venta?')) { 
            sale.status = "Cancelado";
        }
    };
    
    $scope.markAsPaid = function(sale) {
        sale.status = "Pagado";
        sale.payment.status = "Pagado";
    };
    
    $scope.markAsUnpaid = function(sale) {
        sale.status = "Pendiente";
        sale.payment.status = "Pendiente";
    };
    
    $scope.requestShipment = function(sale) {
        sale.status = "Enviando";
        
        sale.delivery.comments = $('#comments-' + sale.id).val();
        $scope.closeModal(sale);
    };
    
    $scope.cancelShipment = function(sale) {
        sale.status = "Pagado";
    };
    
    $scope.markAsEnded = function(sale) {
        sale.status = "Finalizado";
    };
    
     $scope.closeModal = function(shipment) {
        $('#commentsModal-' + shipment.id).modal('hide');
    };
    
}]);