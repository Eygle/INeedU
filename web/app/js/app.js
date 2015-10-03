'use strict';

/* App Module */

var INeedUApp = angular.module('INeedUApp', [
    'ngRoute',
    'ui.bootstrap',

    'INeedUControllers',
    'INeedUFilters',
    'INeedUServices'
]);
//'ngMap'

INeedUApp.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'partials/home-page.html',
                controller: 'HomeCtrl'
            }).
            when('/create-demand', {
                templateUrl: 'partials/create-demand.html',
                controller: 'DemandCtrl'
            }).
            when('/create-offer', {
                templateUrl: 'partials/create-offer.html',
                controller: 'OfferCtrl'
            }).
            otherwise({
                redirectTo: '/'
            });
    }]);
