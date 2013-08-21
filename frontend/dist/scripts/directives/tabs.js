angular.module('app').directive('appTabs', [
  '$log', function($log) {
    var controller;
    controller = [
      '$scope', '$element', '$rootScope', function($scope, $element, $rootScope) {
        $scope.tabs = [];
        $scope.select = function(tab) {
          if (tab.selected === true) {
            return;
          }
          angular.forEach($scope.tabs, function(tab) {
            return tab.selected = false;
          });
          return tab.selected = true;
        };
        return this.addTab = function(tab, tabId) {
          if ($scope.tabs.length === 0) {
            $scope.select(tab);
          }
          $scope.tabs.push(tab);
          if (tabId) {
            return $rootScope.$on("changeTab#" + tabId, function() {
              return $scope.select(tab);
            });
          }
        };
      }
    ];
    return {
      controller: controller,
      replace: true,
      restrict: 'E',
      scope: {},
      templateUrl: '/views/directives/tabs.html',
      transclude: true
    };
  }
]);
