angular.module('app').service('gitHubService', [
  '$log', '$q', '$resource', 'messageService', function($log, $q, $resource, messageService) {
    var Repo, get, self;
    self = this;
    Repo = $resource('https://api.github.com/users/:user/repos', {
      callback: 'JSON_CALLBACK'
    }, {
      get: {
        method: 'JSONP'
      }
    });
    get = function(criteria) {
      var defer;
      defer = $q.defer();
      Repo.get({
        user: criteria
      }, function(results) {
        messageService.publish('search', {
          source: 'GitHub',
          criteria: criteria
        });
        return defer.resolve(results.data);
      }, function(results) {
        $log.error('gitHubService error', results);
        return defer.reject(results);
      });
      return defer.promise;
    };
    return self.get = get;
  }
]);
