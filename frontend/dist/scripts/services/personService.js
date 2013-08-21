angular.module('app').service('personService', [
  '$log', '$q', '$resource', function($log, $q, $resource) {
    var Person, get, getPerson, save, self;
    self = this;
    Person = $resource('./people/:id');
    get = function() {
      var defer;
      defer = $q.defer();
      Person.query({}, function(results) {
        return defer.resolve(results);
      }, function(results) {
        $log.error('personService.query error', results);
        return defer.reject(results);
      });
      return defer.promise;
    };
    getPerson = function(id) {
      var defer;
      defer = $q.defer();
      Person.get({
        id: id
      }, function(results) {
        return defer.resolve(results);
      }, function(results) {
        $log.error('personService.get error', results);
        return defer.reject(results);
      });
      return defer.promise;
    };
    save = function(person) {
      var defer, newPerson;
      defer = $q.defer();
      newPerson = new Person(person);
      newPerson.$save(function(results) {
        return defer.resolve(results);
      }, function(results) {
        $log.error('personService.save error', results);
        return defer.reject(results);
      });
      return defer.promise;
    };
    self.get = get;
    self.getPerson = getPerson;
    return self.save = save;
  }
]);
