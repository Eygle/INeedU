'use strict';

/* Controllers */

var INeedUControllers = angular.module('INeedUControllers', []);

INeedUControllers.controller('NavBarCtrl', ['$scope',
    function($scope) {
    }]);

INeedUControllers.controller('HomeCtrl', ['$scope',
    function($scope) {
        $scope.categories = getCategories();

        $scope.query = "";
        $scope.category = $scope.categories[0].id;
        $scope.remuneration = 0;
    }]);