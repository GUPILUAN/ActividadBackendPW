<?php

include('conexion.php');

if(isset($_GET['accion'])){
    $accion = $_GET['accion'];
    if($accion == "leer"){
        $sql = "SELECT * FROM Clientes WHERE 1";
        $result = $db->query($sql);

        if($result->num_rows > 0){
            while($fila = $result->fetch_assoc()){
                $item['id_cliente'] = $fila['id_cliente'];
                $item['nombre'] = $fila['nombre'];
                $item['email'] = $fila['email'];
                $clientes[] = $item;
            }
            $response["status"] = "OK";
            $response["mensaje"] = $clientes;
        }
        else{
            $response["status"] = "Error";
            $response["mensaje"] = "No hay clientes registrados";
        }

        echo json_encode($response);
    }
}
if(isset($_POST['accion'])){
    $accion = $_POST['accion'];    
    switch ($accion) {
        case 'agregar':
            if(isset($_POST['nombre']) && isset($_POST['email'])) {
                $nombre = $_POST['nombre'];
                $email = $_POST['email'];
                
                //Comprobamos la longitud de las cadenas
                if(strlen($nombre)>30 || strlen($email)>30){
                    $response["status"]= "Error";
                    $response["mensaje"]="Los datos ingresados superan el límite permitido.";
                }else{
                    // Insertar los datos en la base de datos
                    $sql = "INSERT INTO Clientes (nombre, email) VALUES (?,?)";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("ss", $nombre, $email);  
                    if($stmt->execute()){
                        $response["status"] = "OK";
                        $response["mensaje"] = "Registro creado exitosamente";
                    } else {
                        $response["status"] = "Error";
                        $response["mensaje"] = "No se pudo registrar";
                    }
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
                $sql = "SELECT * FROM Clientes WHERE id_cliente = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    if($row = $result->fetch_assoc()) {
                        $nombre = isset($_POST['nombre']) && !empty($_POST['nombre'])  ? $_POST['nombre'] : $row['nombre'];
                        $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : $row['email'];
                        //Comprobamos la longitud de las cadenas
                        if(strlen($nombre)>30 || strlen($email)>30){
                            $response["status"]= "Error";
                            $response["mensaje"]="Los datos ingresados superan el límite permitido.";
                        }else{
                            // Insertar los datos en la base de datos
                            $sql = "UPDATE Clientes SET nombre = ?, email = ? WHERE id_cliente = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("ssi", $nombre, $email, $id);  
                            if($stmt->execute()){
                                $response["status"] = "OK";
                                $response["mensaje"] = "Registro actualizado exitosamente";
                            } else {
                                $response["status"] = "Error";
                                $response["mensaje"] = "No se pudo registrar";
                            }
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
                $sql = "SELECT * FROM Clientes WHERE id_cliente = $id";
                $result = $db->query($sql);
                if($result->num_rows > 0){
                    $sql = "DELETE FROM Clientes WHERE id_cliente = ?";
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
?>