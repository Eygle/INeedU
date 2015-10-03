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
        $scope.category = $scope.categories[0];
        console.log($scope.category);
        $scope.remuneration = 0;



        $scope.status = {
            isopen: false
        };

        $scope.toggleDropdown = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.status.isopen = !$scope.status.isopen;
        };
    }]);

INeedUControllers.controller('DemandCtrl', ['$scope',
    function($scope) {
        $scope.data = {
            title: "",
            categoryId: -1,
            remuneration: 0,
            duration: 1,
            durationLabel: "",
            date: "",
            periodic: false
        };

        $scope.categories = getCategories();
        $scope.category = $scope.categories[0];

        $scope.maxDate = new Date(2020, 5, 22);
        $scope.minDate = new Date();
        $scope.status = {
            opened: false
        };

        $scope.today = function() {
            $scope.dt = new Date();
        };
        $scope.today();

        $scope.open = function($event) {
            $scope.status.opened = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

    }]);