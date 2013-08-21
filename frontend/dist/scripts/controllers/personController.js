angular.module('app').controller('personController', [
  '$log', '$scope', '$location', 'personService', function($log, $scope, $location, personService) {
    $scope.people = [];
    $scope.insertPerson = function(person) {
      return personService.save(person).then(function(results) {
        $scope.error = '';
        $scope.person = {};
        $scope.people.push(results);
        return $location.path("/people/" + results.id);
      }, function(results) {
        if (results.status === 403) {
          return $scope.error = results.data;
        }
      });
    };
    return personService.get().then(function(results) {
      return $scope.people = results;
    });
  }
]);
