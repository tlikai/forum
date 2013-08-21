angular.module('app').config([
  '$routeProvider', function($routeProvider) {
    return $routeProvider.when('/github/:searchTerm', {
      controller: 'gitHubController',
      reloadOnSearch: true,
      resolve: {
        changeTab: [
          '$rootScope', function($rootScope) {
            return $rootScope.$broadcast('changeTab#gitHub');
          }
        ]
      }
    }).when('/people/:id', {
      controller: 'personDetailsController',
      reloadOnSearch: true,
      resolve: {
        changeTab: [
          '$rootScope', function($rootScope) {
            return $rootScope.$broadcast('changeTab#people');
          }
        ]
      }
    }).otherwise({
      redirectTo: '/github'
    });
  }
]);
