angular.module('app').controller('personDetailsController', [
  '$log', '$scope', 'personService', function($log, $scope, personService) {
    return $scope.onRouteChange = function(routeParams) {
      var id;
      id = routeParams.id;
      return personService.getPerson(id).then(function(results) {
        return $scope.person = results;
      });
    };
  }
]);
