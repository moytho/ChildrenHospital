'use strict';

function MedicoListado($scope, $http) {
    $http.get('api/medicos').success(function(data) {
    $scope.medicos = data;
  });
}