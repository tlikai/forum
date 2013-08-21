angular.module('app').controller('gitHubController', [
  '$log', '$scope', '$location', 'gitHubService', function($log, $scope, $location, gitHubService) {
    $scope.search = function(searchTerm) {
      return $location.path("/github/" + searchTerm);
    };
    return $scope.onRouteChange = function(routeParams) {
      $scope.searchTerm = routeParams.searchTerm;
      return gitHubService.get($scope.searchTerm).then(function(results) {
        return $scope.repos = results;
      });
    };
  }
]);
