'use strict';

/* Services */

var INeedUServices = angular.module('INeedUServices', ['ngResource']);

INeedUServices.factory('Phone', ['$resource',
  function($resource){
    return $resource('phones/:phoneId.json', {}, {
      query: {method:'GET', params:{phoneId:'phones'}, isArray:true}
    });
  }]);

INeedUServices.service('TranslationService', function ($resource) {

  this.getTranslation = function ($scope, language) {
    if (!language) {
      language = navigator.language || navigator.userLanguage;;
    }

    var path = 'lang/lang-' + language + '.json';
    var ssid = 'lang__' + language;

    if (sessionStorage) {
      if (sessionStorage.getItem(ssid)) {
        $scope.translation = JSON.parse(sessionStorage.getItem(ssid));
      } else {
        $resource(path).get(function(data) {
          $scope.translation = data;
          sessionStorage.setItem(ssid, JSON.stringify($scope.translation));
        });
      };
    } else {
      $resource(path).get(function (data) {
        $scope.translation = data;
      });
    }
  };
});