'use strict';

/* App Module */

var INeedUApp = angular.module('INeedUApp', [
  'ngRoute',

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

      otherwise({
        redirectTo: '/'
      });
  }]);
