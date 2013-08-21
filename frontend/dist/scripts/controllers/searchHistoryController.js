angular.module('app').controller('searchHistoryController', [
  '$log', '$scope', 'messageService', function($log, $scope, messageService) {
    $scope.searchHistory = [];
    return messageService.subscribe('search', function(name, parameters) {
      return $scope.searchHistory.push(parameters);
    });
  }
]);
