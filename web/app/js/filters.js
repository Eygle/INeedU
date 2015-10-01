'use strict';

/* Filters */

angular.module('INeedUFilters', []).filter('checkmark', function() {
  return function(input) {
    return input ? '\u2713' : '\u2718';
  };
});
