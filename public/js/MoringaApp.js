
var app = angular.module('MoringaApp', ['chart.js','ngResource', 'ng-currency']);

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

app.directive('spinner', function(){
	return {
		restrict: 'E',
		templateUrl: 'public/views/spinner.html'
	};
});

app.directive('collapsablePanel', function(){
	return {
		restrict: 'A',
		link: function($scope, $element, $attrs) {
			var panel = $($element).find('.panel');
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
        require: 'ngModel',
        scope: {
          ngModel: '=',
          defaultDate: '@datepicker'
        },
		link: function($scope, $element, $attrs, ngModel) {
            $($element).datepicker({
                autoclose: true,
                format: 'dd/M/yyyy',
                todayHighlight: true
            });

            ngModel.$formatters.push(function(value) {
                if(value !== undefined) {
                    var date = moment(value);
                    return date.format('DD/MMM/YYYY');
                }
            });

            ngModel.$parsers.push(function(input) {
                var date = moment(input, 'DD/MMM/YYYY');
                return date.format('YYYY-MM-DD');
            });

            if($scope.defaultDate) {
                var newDate = moment($scope.defaultDate)
                ngModel.$setViewValue(newDate.format('YYYY-MM-DD'));
                $($element).datepicker('setDate', newDate.toDate());
            }


            $scope.$watch('ngModel', function(newValue) {
                if(newValue !== undefined) {
                    $($element).datepicker('update');
                }
            });
		}
	};
});

app.directive('arrayToList', function(){
    return {
        restrict: 'A',
        require: 'ngModel',
        scope: {
          ngModel: '='
        },
        link: function($scope, $element, $attrs, ngModel) {
            ngModel.$formatters.push(function(value) {
                if(value !== undefined) {
                    return (value || []).join('\n');
                }
            });

            ngModel.$parsers.push(function(input) {
                    return input.split('\n');
            });
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

app.filter('sum', ['$parse', function ($parse) {
    return function (input, property) {
        var i = input instanceof Array ? input.length : 0,
            p = $parse(property);

        if (typeof property === 'undefined' || i === 0) {
            return i;
        } else if (isNaN(p(input[0]))) {
            throw 'filter total can count only numeric values';
        } else {
            var total = 0;
            while (i--)
                total += parseFloat(p(input[i]));
            return total;
        }
    };
}]);

app.filter('calc', function () {
    return function (data, type) {
        if(data === undefined) return 0;
        if(type === undefined) type = 'total';
        var total = 0;
        for (var i = 0; i < data.length; i++) {
            var subtotal = parseFloat(data[i].payment.total - data[i].payment.commission - data[i].payment.rawMaterial);
            switch(type) {
                case 'total':
                    total += subtotal;
                    break;
                case 'splittings':
                    total += (subtotal * 0.30);
                    break;
                case 'earnings':
                    total += (subtotal * 0.70);
                    break;
            }
        };

        return total;
    };
});

app.factory('Sale', function($resource) {
    return $resource('index.php/api/sale/:id', { id: '@id' }, {
        update: {
            method: 'PUT' // this method issues a PUT request
        }
    });
});

app.factory('Expense', function($resource) {
    return $resource('index.php/api/expense/:id', { id: '@id' }, {
        update: {
            method: 'PUT' // this method issues a PUT request
        }
    });
});

app.factory('Inversion', function($resource) {
    return $resource('index.php/api/inversion/:id', { id: '@id' }, {
        update: {
            method: 'PUT' // this method issues a PUT request
        }
    });
});

app.factory('Transfer', function($resource) {
    return $resource('index.php/api/transfer/:id', { id: '@id' }, {
        update: {
            method: 'PUT' // this method issues a PUT request
        }
    });
});

app.controller('DashboardCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.isLoading = false;

    $scope.totalSalesThisWeek = 0;
    $scope.totalPendingShipments = 0;

    // History Chart
    $scope.historyChart = {};
    $scope.historyChart.labels = ['Viernes', 'Sábado', 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves'];
    $scope.historyChart.series = ['Semana Actual', 'Semana Pasada'];
    $scope.historyChart.data = [];

    // Total Sales Chrat
    $scope.salesChart = {};
    $scope.salesChart.labels = ['Finalizadas', 'Canceladas'];
    $scope.salesChart.data = [];

    $http.get('index.php?/api/sales_resume')
    .success(function(data) {
        if(data.error) {
            alert(data.error);
        } else {
            $scope.totalEnded = data.total_ended;
            $scope.totalCancelled = data.total_cancelled;
            $scope.totalSalesThisWeek = data.total_sales_this_week;
            $scope.totalPendingShipments = data.total_pending_shipments;
            $scope.mostActiveBuyers = data.most_active_buyers;

            $scope.historyChart.data[0] = data.sales_this_week.sales;
            $scope.historyChart.data[1] = data.sales_last_week.sales;

            $scope.salesChart.data = [data.total_ended, data.total_cancelled];
        }
    });
}]);

app.controller('ShipmentsListCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.result_limit = 10;
    $scope.current_page = 1;
    $scope.total_pages = 1;
    $scope.total_rows = 0;

	$scope.orderBy = '-date';
	$scope.shipments = [];
	$scope.isLoading = false;
	$scope.isThereError = false;
    $scope.saleLoading = null;

    $scope.getSalesCollection =  function() {
        $scope.shipments = [];
        $scope.isLoading = true;
        $scope.isThereError = false;

        $http.get('index.php?/api/shipments/' + $scope.current_page + '/' + $scope.result_limit)
        .success(function(data) {
            $scope.shipments = data.response;
            $scope.total_rows = data.total_rows;
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
            if(data.error) {
                alert(data.error);
            } else {
                shipment.delivery.trackCode = data.delivery.trackCode;
                shipment.delivery.status = data.delivery.status;
                shipment.status = data.status;
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
            if(data.error) {
                alert(data.error);
            } else {
                sale.payment.status = data.payment.status;
                sale.delivery.status = data.delivery.status;
                sale.status = data.status;
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

app.controller('AddSaleCtrl', ['$scope', 'Sale', function ($scope, Sale) {
	$scope.isSaved = false;
    $scope.isLoading = false;
    $scope.isThereError = false;

    $scope.hasAddressee = false;
    $scope.discount = 0;
    $scope.earnings = 0;

    $scope.sale = new Sale();
    $scope.sale.date = new Date();
	$scope.sale.name = "";
	$scope.sale.user = "";
	$scope.sale.email = "";
	$scope.sale.phone = "";
	$scope.sale.package = [];

    $scope.sale.delivery = {};
	$scope.sale.delivery.addressee = '';
    $scope.sale.delivery.phone = '';
	$scope.sale.delivery.address = '';
	$scope.sale.delivery.courier = 'Estafeta';
	$scope.sale.delivery.cost = 100;

    $scope.sale.payment = {};
	$scope.sale.payment.total = 0;
	$scope.sale.payment.commission = 0;
	$scope.sale.payment.rawMaterial = 0;
    $scope.sale.split_earnings = true;

    $scope.$watchGroup(
        ['sale.payment.total', 'sale.payment.rawMaterial', 'sale.payment.commission', 'sale.split_earnings'],
        function() {
            if($scope.sale.split_earnings) {
                $scope.discount = ($scope.sale.payment.total - $scope.sale.payment.rawMaterial - $scope.sale.payment.commission) * 0.30;
                $scope.discount = Math.round($scope.discount * 100) / 100;
            } else {
                $scope.discount = 0;
            }
            $scope.earnings = $scope.sale.payment.total - $scope.sale.payment.rawMaterial - $scope.sale.payment.commission - $scope.discount;
        }
    );

    $scope.saveSale = function(sale) {
        $scope.isLoading = true;
        $("#saleForm :input").prop("disabled", true);
        Sale.save($scope.sale, function() {
            $scope.isSaved = true;
            $("body").animate({scrollTop: 0}, "slow");
            $("#saleForm :input").prop("disabled", false);
            $scope.isLoading = false;
        });
    };

}]);

app.controller('UpdateSaleCtrl', ['$scope', 'Sale', function ($scope, Sale) {
    $scope.isSaved = false;
    $scope.isLoading = false;
    $scope.isThereError = false;

    $scope.hasAddressee = false;
	$scope.discount = 0;
	$scope.earnings = 0;

    $scope.populateForm = function(id) {
        $scope.isLoading = true;

        $scope.sale = Sale.get({ id: id }, function(data) {
            $scope.isLoading = false;
            $scope.hasAddressee = $scope.sale.delivery.addressee ? true : false;
            $scope.$watchGroup(
                ['sale.payment.total', 'sale.payment.rawMaterial', 'sale.payment.commission', 'sale.split_earnings'],
                function() {
                    if($scope.sale.split_earnings) {
                        $scope.discount = ($scope.sale.payment.total - $scope.sale.payment.rawMaterial - $scope.sale.payment.commission) * 0.30;
                        $scope.discount = Math.round($scope.discount * 100) / 100;
                    } else {
                        $scope.discount = 0;
                    }
                    $scope.earnings = $scope.sale.payment.total - $scope.sale.payment.rawMaterial - $scope.sale.payment.commission - $scope.discount;
                }
            );
        });
    };

    $scope.saveSale = function(sale) {
        $scope.sale.$update(function() {
            $scope.isSaved = true;
            $("body").animate({scrollTop: 0}, "slow");
        });
    };
}]);

app.controller('SalesListCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.result_limit = 20;
    $scope.current_page = 1;
    $scope.total_pages = 1;
    $scope.total_rows = 0;

    $scope.orderBy = '-date';
	$scope.sales = [];
	$scope.isLoading = false;
	$scope.isThereError = false;
	$scope.saleLoading = null;

    $scope.getSalesCollection =  function() {
        $scope.sales = [];
        $scope.isLoading = true;
        $scope.isThereError = false;

        $http.get('index.php?/api/sales/' + $scope.current_page + '/' + $scope.result_limit)
        .success(function(data) {
            $scope.sales = data.response;
            $scope.total_rows = data.total_rows;
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
        	if(data.error) {
        		alert(data.error);
        	} else {
        		sale.delivery.comments = data.delivery.comments;
        		sale.status = data.status;
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

    $scope.updateSale = function(sale) {
        window.location = "index.php/sales/update/" + sale.id;
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
        	if(data.error) {
        		alert(data.error);
        	} else {
        		sale.payment.status = data.payment.status;
        		sale.delivery.status = data.delivery.status;
        		sale.status = data.status;
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

app.controller('HistoryCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter) {
    $scope.isLoading = false;
    $scope.isThereError = false;
    $scope.totalRows = 0;

    $scope.sales = [];

    $scope.getHistory = function(startDate, endDate) {
        $scope.isLoading = true;
        $scope.isThereError = false;
        $scope.totalRows = 0;

        startDate = moment(startDate).format('YYYY-MM-DD');
        endDate = moment(endDate).format('YYYY-MM-DD');

        $http.get('index.php?/api/history/' + startDate + '/' + endDate)
        .success(function(data) {
            if(data.error) {
                alert(data.error);
            } else {
                $scope.sales = data.response;
                $scope.totalRows = data.total_rows;
            }
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    setTimeout(function() {
        $scope.getHistory($scope.sinceDate, $scope.toDate);
    }, 300);
}]);

app.controller('AddExpenseCtrl', ['$scope', '$http', 'Expense', function ($scope, $http, Expense) {
    $scope.isLoading = false;
    $scope.isSaved = false;

    $scope.expense = new Expense();
    $scope.expense.description = '';
    $scope.expense.total = 0;

    $scope.saveExpense = function(expense) {
        if(expense.description != '' && expense.total != 0) {
            if(expense.total <= 0) {
                alert('El total del gasto no puede ser menor o igual a 0')
            } else {
                $scope.isSaved = false;
                $scope.isLoading = true;
                $("#AddExpenseForm :input").prop("disabled", true);

                Expense.save(expense, function() {
                    $("body").animate({scrollTop: 0}, "slow");
                    $("#AddExpenseForm :input").prop("disabled", false);
                    $scope.isLoading = false;
                    $scope.isSaved = true;

                    $scope.expense.date = moment().format('YYYY-MM-DD');
                    $scope.expense.description = '';
                    $scope.expense.total = 0;

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                });
            }
        }
    };
}]);

app.controller('ExpensesCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.isLoading = false;
    $scope.isThereError = false;
    $scope.totalRows = 0;

    $scope.expenses = [];

    $scope.getExpenses = function(startDate, endDate) {
        $scope.isLoading = true;
        $scope.isThereError = false;
        $scope.totalRows = 0;

        startDate = moment(startDate).format('YYYY-MM-DD');
        endDate = moment(endDate).format('YYYY-MM-DD');

        $http.get('index.php?/api/expenses/' + startDate + '/' + endDate)
        .success(function(data) {
            if(data.error) {
                alert(data.error);
            } else {
                $scope.expenses = data.response;
                $scope.totalRows = data.total_rows;
            }
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    setTimeout(function() {
        $scope.getExpenses($scope.sinceDate, $scope.toDate);
    }, 300);
}]);

app.controller('AddInversionCtrl', ['$scope', '$http', 'Inversion', function ($scope, $http, Inversion) {
    $scope.isLoading = false;
    $scope.isSaved = false;

    $scope.inversion = new Inversion();
    $scope.inversion.description = '';
    $scope.inversion.total = 0;

    $scope.saveInversion = function(inversion) {
        if(inversion.description != '' && inversion.total != 0) {
            if(inversion.total <= 0) {
                alert('El total del gasto no puede ser menor o igual a 0')
            } else {
                $scope.isSaved = false;
                $scope.isLoading = true;
                $("#AddExpenseForm :input").prop("disabled", true);

                Inversion.save(inversion, function() {
                    $("body").animate({scrollTop: 0}, "slow");
                    $("#AddExpenseForm :input").prop("disabled", false);
                    $scope.isLoading = false;
                    $scope.isSaved = true;

                    $scope.inversion.date = moment().format('YYYY-MM-DD');
                    $scope.inversion.description = '';
                    $scope.inversion.total = 0;

                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                });
            }
        }
    };
}]);

app.controller('InversionsCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.isLoading = false;
    $scope.isThereError = false;
    $scope.totalRows = 0;

    $scope.inversions = [];

    $scope.getInversions = function(startDate, endDate) {
        $scope.isLoading = true;
        $scope.isThereError = false;
        $scope.totalRows = 0;

//        startDate = moment(startDate).format('YYYY-MM-DD');
//        endDate = moment(endDate).format('YYYY-MM-DD');

        $http.get('index.php?/api/inversions/')
        .success(function(data) {
            if(data.error) {
                alert(data.error);
            } else {
                $scope.inversions = data.response;
                $scope.totalRows = data.total_rows;
            }
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    setTimeout(function() {
        $scope.getInversions();
    }, 300);
}]);

app.controller('AddTransferCtrl', ['$scope', '$http', 'Transfer', function ($scope, $http, Transfer) {
    $scope.isLoading = false;
    $scope.isSaved = false;

    $scope.transfer = new Transfer();
    $scope.transfer.account = '';
    $scope.transfer.total = 0;

    $scope.saveTransfer = function(transfer) {
        if(transfer.total <= 0) {
            alert('La transferencia no puede ser menor o igual a 0')
        } else {
            $scope.isSaved = false;
            $scope.isLoading = true;
            $("#AddTransferForm :input").prop("disabled", true);

            Transfer.save(transfer, function() {
                $("body").animate({scrollTop: 0}, "slow");
                $("#AddTransferForm :input").prop("disabled", false);
                $scope.isLoading = false;
                $scope.isSaved = true;

                $scope.transfer.date = moment().format('YYYY-MM-DD');
                $scope.transfer.account = '';
                $scope.transfer.total = 0;

                setTimeout(function(){
                    location.reload();
                }, 1000);
            });
        }
    };
}]);

app.controller('TransfersCtrl', ['$scope', '$http', function ($scope, $http) {
    $scope.isLoading = false;
    $scope.isThereError = false;
    $scope.totalRows = 0;

    $scope.transfers = [];
    $scope.totalRawMaterial = 0;
    $scope.payedRawMaterial = 0;
    $scope.transferedRawMaterial = 0;
    $scope.pendingRawMaterial = 0;
    $scope.totalSplittings = 0;
    $scope.expensesSplittings = 0;
    $scope.transferedSplittings = 0;
    $scope.pendingSplittings = 0;

    $scope.getTransfers = function() {
        $scope.isLoading = true;
        $scope.isThereError = false;
        $scope.totalRows = 0;

        $http.get('index.php?/api/transfers')
        .success(function(data) {
            if(data.error) {
                alert(data.error);
            } else {
                $scope.transfers = data.response;
                $scope.totalRawMaterial = data.total_raw_material;
                $scope.payedRawMaterial = data.payed_raw_material;
                $scope.transferedRawMaterial = data.transfered_raw_material;
                $scope.pendingRawMaterial = data.pending_raw_material;
                $scope.totalSplittings = data.total_splittings;
                $scope.expensesSplittings = data.expenses_splittings;
                $scope.transferedSplittings = data.transfered_splittings;
                $scope.pendingSplittings = data.pending_splittings;

                $scope.totalRows = data.total_rows;
            }
        })
        .error(function() {
            $scope.isThereError = true;
        }).finally(function() {
            $scope.isLoading = false;
        });
    };

    $scope.getTransfers();
}]);