
var app = angular.module('MoringaApp', ['ng-currency']);

app.directive('shipment', function(){
	return {
		restrict: 'E',
		templateUrl: 'views/shipment-info.html'
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
    });
}]);

app.controller('ShipmentsCtrl', ['$scope', function ($scope) {
	$scope.shipments = [
		{
			id: 1,
			delivery: {
				addressee: 'Jhon Doe',
				address: {
					street: 'Azul Marino #124',
					zone: 'Colores',
					zip: '58000',
					city: 'Monterrey, Nuevo León'
				},
				phone: '443 312 4578'
			},
			package: {
				content: ['500gr Hoja Seca', '1kg Polvo']
			},
			courier: 'Estafeta',
			status: 'Pendiente',
			date: '22/05/2015'
		},
		{
			id: 2,
			delivery: {
				addressee: 'José Camargo',
				address: {
					street: 'Tequila #21',
					zone: 'Jornaleros',
					zip: '58150',
					city: 'Guadalajara, Jalisco'
				},
				phone: '443 326 3256'
			},
			package: {
				content: ['3kg Polvo']
			},
			courier: 'Estafeta',
			status: 'Enviado',
			date: '17/05/2015',
			comments: 'Lorem ipsum dolor sit amey consequteur amit...'
		},
		{
			id: 3,
			delivery: {
				addressee: 'Martha Flores',
				address: {
					street: 'Batman #4',
					zone: 'Super Heroes',
					zip: '85421',
					city: 'Merida, Yucatán'
				},
				phone: '443 985 6251'
			},
			package: {
				content: ['2 Bolsas 100gr Hoja', '100gr Semilla', '1 Jabón']
			},
			courier: 'Estafeta',
			status: 'Cancelado',
			date: '20/05/2015'
		}
	];

	$scope.orderBy = '-date';

}]);

app.controller('AddSaleCtrl', ['$scope', function ($scope) {
	$scope.grandTotal = 0;
	$scope.shippingCost = 100;
	$scope.productionCost = 0;
	$scope.commission = 0;
	$scope.discount = 0;
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
		}
	);

}]);

app.controller('SalesListCtrl', ['$scope', function ($scope) {
	$scope.sales = [
		{
			id: 1,
			date: new Date(),
			customer: 'Jhon Doe',
			items: ['500gr Polvo', '2kg Hoja Seca'],
			MLUser: 'RUGE8906',
			email: 'ruge89_06@hotmail.com',
			delivery: {
				addressee: 'José Camargo',
				address: {
					street: 'Tequila #21',
					zone: 'Jornaleros',
					zip: '58150',
					city: 'Guadalajara, Jalisco'
				},
				phone: '443 326 3256'
			},
			courier: 'Estafeta',
			paymentStatus: 'Pagado',
			grandTotal: 850,
			shippingCost: 100,
			commission: 7.50,
			productionCost: 60,
			totalProfit: 400
		},
		{
			id: 2,
			date: new Date(),
			customer: 'José Perez León',
			items: ['1kg Polvo', '100gr Semilla'],
			MLUser: 'RUIZ066',
			email: 'RUIZ.066@hotmail.com',delivery: {
				addressee: 'Martha Flores',
				address: {
					street: 'Batman #4',
					zone: 'Super Heroes',
					zip: '85421',
					city: 'Merida, Yucatán'
				},
				phone: '443 985 6251'
			},
			courier: 'Estafeta',
			paymentStatus: 'Pendiente',
			grandTotal: 850,
			shippingCost: 100,
			commission: 7.50,
			productionCost: 60,
			totalProfit: 400
		}
	];
}]);