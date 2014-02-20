
var myModule=angular.module('CrudApp', []).

  config(['$routeProvider', function($routeProvider) {
  $routeProvider.
      when('/', {templateUrl: 'vp/home.html',controller:Home}).

      when('/medico', {templateUrl: 'vp/medico/listado.html', controller: MedicoListado}).
      when('/mediconuevo', {templateUrl: 'vp/medico/nuevo.html', controller: AgregarControlMedico}).
      when('/medicoeditar/:id', {templateUrl: 'vp/medico/editar.html', controller: EditarControlMedico}).

      when('/especialidad', {templateUrl: 'vp/especialidad/listado.html', controller: EspecialidadListado}).
      when('/especialidadnuevo', {templateUrl: 'vp/especialidad/nuevo.html', controller: AgregarControlEspecialidad}).
      when('/especialidadeditar/:id', {templateUrl: 'vp/especialidad/editar.html', controller: EditarControlEspecialidad}).

      when('/medicoespecialidad/:id', {templateUrl: 'vp/medicoespecialidad/listado.html', controller: AgregarControlMedicoEspecialidad}).

      otherwise({redirectTo: '/'});


}]);


function AgregarControlMedicoEspecialidad($scope, $http,$location,$routeParams) {
    var id = $routeParams.id;
    $scope.activePath = null;
    $scope.master = {};

    $http.get('api/medicosespecialidades/'+id).success(function(data) {
      $scope.medicoespecialidades = data;
    });

    $http.get('api/especialidades').success(function(data) {
      $scope.especialidades = data;
    });

    $scope.add_new = function(medicoespecialidad, AddNewForm) {
    
      $http.post('api/agregar_medicoespecialidad/'+id, medicoespecialidad).success(function(data){


        $scope.reset();
        $scope.activePath = $location.path('/medicoespecialidad/'+id);
      });

      $scope.reset = function() {
        $scope.medicoespecialidades = angular.copy($scope.master);
      };

      $scope.reset();

    };
}

function Home($scope,$http){
//
}

function MedicoListado($scope, $http) {
    $http.get('api/medicos').success(function(data) {
    $scope.medicos = data;
  });
}

function EspecialidadListado($scope, $http) {
    $http.get('api/especialidades').success(function(data) {
      $scope.especialidades = data;
    });
}

function AgregarControlMedico($scope, $http, $location) {
  $scope.master = {};
  $scope.activePath = null;

  $scope.add_new = function(medico, AddNewForm) {
  
    $http.post('api/agregar_medico', medico).success(function(){
      $scope.reset();
      $scope.activePath = $location.path('/medico');
    });

    $scope.reset = function() {
      $scope.medico = angular.copy($scope.master);
    };

    $scope.reset();

  };
}

function AgregarControlEspecialidad($scope, $http, $location) {
  $scope.master = {};
  $scope.activePath = null;

  $scope.add_new = function(especialidad, AddNewForm) {
  
    $http.post('api/agregar_especialidad', especialidad).success(function(){
      $scope.reset();
      $scope.activePath = $location.path('/especialidad');
    });

    $scope.reset = function() {
      $scope.especialidad = angular.copy($scope.master);
    };

    $scope.reset();

  };
}

function EditarControlMedico($scope, $http, $location, $routeParams) {
  var id = $routeParams.id;
  $scope.activePath = null;

  $http.get('api/medicos/'+id).success(function(data) {
    $scope.medicos = data;
  });


  $scope.update = function(medico){
    $http.put('api/medicos/'+id, medico).success(function(data) {
      $scope.medicos= data;
      $scope.activePath = $location.path('/medico');
    });
  };

  $scope.delete = function(medico) {

    var eliminarMedico = confirm('¿Estas completamente seguro de querer eliminar este Medico?');
    if (eliminarMedico) {
      $http.delete('api/medicos/'+id);
      $scope.activePath = $location.path('/medico');
    }
  };
}

function EditarControlEspecialidad($scope, $http, $location, $routeParams) {
  var id = $routeParams.id;
  $scope.activePath = null;

  $http.get('api/especialidades/'+id).success(function(data) {
    $scope.especialidades = data;
  });


  $scope.update = function(especialidad){
    $http.put('api/especialidades/'+id, especialidad).success(function(data) {
      $scope.especialidades= data;
      $scope.activePath = $location.path('/especialidad');
    });
  };

  $scope.delete = function(especialidad) {

    var eliminarEspecialidad = confirm('¿Estas completamente seguro de querer eliminar esta Especialidad?');
    if (eliminarEspecialidad) {
      $http.delete('api/especialidades/'+id);
      $scope.activePath = $location.path('/especialidad');
    }
  };
}