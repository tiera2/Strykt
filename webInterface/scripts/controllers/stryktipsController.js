app.controller("MatchesCtrl", function($scope, $http) {
  $http.get('./api/stryktipset').
    success(function(data, status, headers, config) {
      $scope.matches = data;
    }).
    error(function(data, status, headers, config) {
      // log error
    });
});