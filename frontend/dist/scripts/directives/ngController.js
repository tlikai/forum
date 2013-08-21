angular.module('app').directive('ngController', [
  '$log', '$rootScope', function($log, $rootScope) {
    var link;
    link = function(scope, element, attrs, controller) {
      return $rootScope.$on("" + attrs.ngController + "$routeChangeSuccess", function(event, currentRoute, priorRoute) {
        if (scope.onRouteChange) {
          return scope.onRouteChange(currentRoute.params);
        }
      });
    };
    return {
      link: link,
      restrict: 'A'
    };
  }
]);
