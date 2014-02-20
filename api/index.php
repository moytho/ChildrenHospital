<?php

require 'Slim/Slim.php';
require 'conexion.php';

$app = new Slim();

$app->get('/medicos', 'obtenerMedicos');
$app->get('/medicos/:id', 'obtenerMedico');
$app->post('/agregar_medico', 'agregarMedico');
$app->put('/medicos/:id', 'editarMedico');
$app->delete('/medicos/:id', 'eliminarMedico');

$app->get('/especialidades', 'obtenerEspecialidades');
$app->get('/especialidades/:id', 'obtenerEspecialidad');
$app->post('/agregar_especialidad', 'agregarEspecialidad');
$app->put('/especialidades/:id', 'editarEspecialidad');
$app->delete('/especialidades/:id', 'eliminarEspecialidad');

$app->get('/medicosespecialidades/:id', 'obtenerMedicoEspecialidades');
$app->post('/agregar_medicoespecialidad/:id', 'agregarMedicoEspecialidad');

$app->run();

function obtenerMedicos() {

    $sql = "select idMedico,CONCAT(Nombre,' ',Apellido) Nombre FROM medico where Estado=1 ORDER BY Nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerEspecialidades() {

    $sql = "select idEspecialidad,Nombre FROM especialidad where Estado=1 ORDER BY Nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerMedico($id) {
    $sql = "select idMedico,Nombre,Apellido,Disponibilidad FROM Medico WHERE idMedico=".$id." ORDER BY idMedico";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerMedicoEspecialidades($id) {
    $sql = "SELECT md.idMedicoEspecialidad,m.idMedico,CONCAT(m.Nombre,' ',m.Apellido) NombreMedico,e.idEspecialidad,e.Nombre NombreEspecialidad 
                    FROM medicoespecialidad md 
                    INNER JOIN especialidad e ON md.idEspecialidad=e.idEspecialidad
                    INNER JOIN medico m ON md.idMedico=m.idMedico 
                    where md.idMedico=".$id." ORDER BY e.Nombre";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function obtenerEspecialidad($id) {
    $sql = "select * FROM Especialidad WHERE idEspecialidad=".$id." ORDER BY idEspecialidad";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function agregarMedico() {
    $request = Slim::getInstance()->request();
    $medico = json_decode($request->getBody());
    $sql = "INSERT INTO medico (Nombre,Apellido,Disponibilidad) VALUES (:Nombre, :Apellido, :Disponibilidad)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("Nombre", $medico->Nombre);
        $stmt->bindParam("Apellido", $medico->Apellido);
        $stmt->bindParam("Disponibilidad", $medico->Disponibilidad);
        $stmt->execute();
        $medico->id = $db->lastInsertId();
        $db = null;
        echo json_encode($medico); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function agregarMedicoEspecialidad($id) {
    $request = Slim::getInstance()->request();
    $medico = json_decode($request->getBody());
    $sql = "INSERT INTO medicoespecialidad (idMedico,idEspecialidad) VALUES (".$id.", :idEspecialidad)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("idEspecialidad", $medico->idEspecialidad);
        $stmt->execute();
        $medico->id = $db->lastInsertId();
        $db = null;
        //echo json_encode($medico); 
        $sql = "SELECT md.idMedicoEspecialidad,m.idMedico,CONCAT(m.Nombre,' ',m.Apellido) NombreMedico,e.idEspecialidad,e.Nombre NombreEspecialidad 
                    FROM medicoespecialidad md 
                    INNER JOIN especialidad e ON md.idEspecialidad=e.idEspecialidad
                    INNER JOIN medico m ON md.idMedico=m.idMedico 
                    where md.idMedicoEspecialidad=".$medico->id."";
            try{
                $db = getConnection();
                $stmt = $db->query($sql);  
                $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
                $db = null;
                echo json_encode($wines);
            } catch(PDOException $e){
                echo '{"error":{"text":'. $e->getMessage() .'}}'; 
            }
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function agregarEspecialidad() {
    $request = Slim::getInstance()->request();
    $medico = json_decode($request->getBody());
    $sql = "INSERT INTO Especialidad (Nombre) VALUES (:Nombre)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("Nombre", $medico->Nombre);
        $stmt->execute();
        $medico->id = $db->lastInsertId();
        $db = null;
        echo json_encode($medico); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function editarMedico($id) {
    $request = Slim::getInstance()->request();
    $medicos = json_decode($request->getBody());
    $sql = "UPDATE medico SET Nombre=:Nombre, Apellido=:Apellido, Disponibilidad=:Disponibilidad WHERE idMedico=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("Nombre", $medicos->Nombre);
        $stmt->bindParam("Apellido", $medicos->Apellido);
        $stmt->bindParam("Disponibilidad", $medicos->Disponibilidad);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        //echo ($medicos);
        echo json_encode($medicos); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function editarEspecialidad($id) {
    $request = Slim::getInstance()->request();
    $medicos = json_decode($request->getBody());
    $sql = "UPDATE Especialidad SET Nombre=:Nombre WHERE idEspecialidad=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("Nombre", $medicos->Nombre);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        //echo ($medicos);
        echo json_encode($medicos); 
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function deleteUser($id) {
    $sql = "DELETE FROM users WHERE id=".$id;
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($wines);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function eliminarMedico($id) {
    $sql = "UPDATE medico set Estado=0 WHERE idMedico=".$id;
    //$sql = "DELETE FROM medico WHERE idMedico=".$id;
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo($wines);
        echo json_encode($wines);

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function eliminarEspecialidad($id) {
    $sql = "UPDATE Especialidad set Estado=0 WHERE idEspecialidad=".$id;
    //$sql = "DELETE FROM medico WHERE idMedico=".$id;
    try {
        $db = getConnection();
        $stmt = $db->query($sql);  
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //echo($wines);
        echo json_encode($wines);

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

?>