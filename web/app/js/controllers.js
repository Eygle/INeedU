'use strict';

/* Controllers */

var INeedUControllers = angular.module('INeedUControllers', ['ngCookies']);

INeedUControllers.controller('NavBarCtrl', ['$scope', '$cookies', 'TranslationService',
    function($scope, $cookies, TranslationService) {
        TranslationService.getTranslation($scope, $cookies.lang);
    }]);

INeedUControllers.controller('HomeCtrl', ['$scope', '$cookies','TranslationService',
    function($scope, $cookies, TranslationService) {
        TranslationService.getTranslation($scope, $cookies.lang);
        $scope.categories = getCategories();

        $scope.query = "";
        $scope.category = $scope.categories[0].id;
        $scope.remuneration = 0;
    }]);