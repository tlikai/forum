angular.module('app').filter('twitterfy', [
  '$log', function($log) {
    return function(username) {
      return "@" + username;
    };
  }
]);
