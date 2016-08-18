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
        $scope.category = "-1";
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

INeedUControllers.controller('DemandCtrl', ['$scope', '$http',
    function($scope, $http) {
        $scope.data = {
            title: "",
            categoryId: -1,
            remuneration: 0,
            date: "",
            periodic: {
                monday: false,
                tuesday: false,
                wednesday: false,
                thursday: false,
                friday: false,
                saturday: false,
                sunday: false
            },
            undeterminateEnd: false
        };

        $scope.categories = getCategories();
        $scope.category = "-1";

        $scope.dates = {
            start: {
                maxDate: new Date(2020, 1, 1),
                minDate: new Date(),
                opened: false,
                date: new Date()
            },
            end: {
                maxDate: new Date(2020, 1, 1),
                minDate: new Date(),
                opened: false,
                date: new Date()
            }
        };

        $scope.changeUndeterminateEnd = function() {
            if ($scope.data.undeterminateEnd) {
                $scope.oldEndDate = $scope.dates.end.date;
                $scope.dates.end.date = "";
            } else {
                $scope.dates.end.date = $scope.oldEndDate;
            }
        };

        $scope.openDate = function(date) {
            date.opened = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

        $scope.getLocation = function(val) {
            return $http.get('//maps.googleapis.com/maps/api/geocode/json', {
                params: {
                    address: val,
                    sensor: false
                }
            }).then(function(response){
                return response.data.results.map(function(item){
                    return item.formatted_address;
                });
            });
        };
    }]);