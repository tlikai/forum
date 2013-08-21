angular.module('app').directive('appTab', [
  '$log', function($log) {
    var link;
    link = function(scope, element, attrs, controller) {
      return controller.addTab(scope, attrs.tabId);
    };
    return {
      link: link,
      replace: true,
      require: '^appTabs',
      restrict: 'E',
      scope: {
        caption: '@'
      },
      templateUrl: '/views/directives/tab.html',
      transclude: true
    };
  }
]);
