<?php

include('conexion.php');


if(isset($_GET['accion'])){
    $accion = $_GET['accion'];
    if($accion == "leer"){
        $sql = "SELECT p.id, CONCAT(a.marca,' ', a.modelo,' ', a.no_serie) AS automovil, c.nombre AS dueño FROM Propiedad p 
                JOIN Autos a ON (p.autos_id_auto = a.id_auto) 
                JOIN Clientes c ON (p.clientes_id_cliente = c.id_cliente)
                WHERE 1";
        $result = $db->query($sql);
        if($result->num_rows > 0){
            while($fila = $result->fetch_assoc()){
                $item['id'] = $fila['id'];
                $item['automovil'] = $fila['automovil'];
                $item['dueño'] = $fila['dueño'];
                $set[] = $item;
            }
            $response["status"] = "OK";
            $response["mensaje"] = $set;
        }
        else{
            $response["status"] = "Error";
            $response["mensaje"] = "No hay registros";
        }

        echo json_encode($response);
    }
}
if(isset($_POST['accion'])){
    $accion = $_POST['accion'];    
    switch ($accion) {
        case 'agregar':
            if(isset($_POST['id_auto']) && isset($_POST['id_cliente'])) {
                $id_auto = $_POST['id_auto'];
                $id_cliente = $_POST['id_cliente'];
                if(checarIds($id_auto, $id_cliente)){
                    // Insertar los datos en la base de datos
                    $sql = "INSERT INTO Propiedad (autos_id_auto, clientes_id_cliente) VALUES (?,?)";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ii", $id_auto, $id_cliente);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Registro creado exitosamente";
                    } else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "No se pudo registrar";
                    }
                }else {
                    $response["status"] = "Error";
                    $response["mensaje"] = "No existe alguno de los ID de auto o cliente";
                }
                  
            } else {
                $response["status"] = "Error";
                $response["mensaje"] = "Faltan campos obligatorios";
            }
            echo  json_encode($response);
            break;
    
        case 'actualizar':
            if(isset($_POST['id'])) {
                $id=$_POST['id'];
                $sql = "SELECT * FROM Propiedad WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    if($row = $result->fetch_assoc()) {
                        $id_auto = isset($_POST['id_auto']) && !empty($_POST['id_auto'])  ? $_POST['id_auto'] : $row['autos_id_auto'];
                        $id_cliente = isset($_POST['id_cliente']) && !empty($_POST['id_cliente']) ? $_POST['id_cliente'] : $row['clientes_id_cliente'];
                        if(checarIds($id_auto,$id_cliente)){
                            // Insertar los datos en la base de datos
                            $sql = "UPDATE Propiedad SET autos_id_auto = ?, clientes_id_cliente = ? WHERE id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("iii", $id_auto, $id_cliente, $id);  
                            if($stmt->execute()){
                                $response["status"] = "OK";
                                $response["mensaje"] = "Registro actualizado exitosamente";
                            } else {
                                $response["status"] = "Error";
                                $response["mensaje"] = "No se pudo registrar";
                            }
                        }else{
                            $response["status"] = "Error";
                            $response["mensaje"] = "No existe alguno de los ID de auto o cliente";
                        } 
                    } 
                
                }else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "No existe ese ID";
                }
            } else {
                $response["status"] = "Error";
                $response["mensaje"] = "Faltan campos obligatorios";
            }
            echo  json_encode($response);
            break;
        case 'eliminar':
            if(isset($_POST['id'])) {
                $id=$_POST['id'];
                $sql = "SELECT * FROM Propiedad WHERE id = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $sql = "DELETE FROM Propiedad WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("i",$id);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Registro eliminado exitosamente";
                    } else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "No se pudo eliminar";
                    }
                }else{
                    $response["status"] = "Error";
                    $response["mensaje"] = "No existe ese ID";
                }
            }else{
                $response["status"] = "Error";
                $response["mensaje"] = "Faltan campos obligatorios";
            }
                
            echo  json_encode($response);
            break;
        default:
            break;
    }
}


function checarIds($id_auto, $id_cliente){
    global $db;
    $sql = "SELECT * FROM Autos WHERE id_auto = $id_auto";
    $result1 = $db->query($sql);
    $sql2 = "SELECT * FROM Clientes WHERE id_cliente = $id_cliente";
    $result2 = $db->query($sql2);
    return $result1->num_rows > 0 && $result2->num_rows > 0;
}

?>