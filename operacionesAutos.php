<?php

include('conexion.php');

    if(isset($_GET['accion'])){
        $accion = $_GET['accion'];
        if($accion == "leer"){
            $sql = "SELECT * FROM Autos WHERE 1";
            $result = $db->query($sql);
            if($result->num_rows > 0){
                while($fila = $result->fetch_assoc()){
                    $item['id_auto'] = $fila['id_auto'];
                    $item['marca'] = $fila['marca'];
                    $item['modelo'] = $fila['modelo'];
                    $item['año'] = $fila['año'];
                    $item['no_serie'] = $fila['no_serie'];
                    $autos[] = $item;
                }
                $response["status"] = "OK";
                $response["mensaje"] = $autos;
            
            }
            else{
                $response["status"] = "Error";
                $response["mensaje"] = "No hay autos registrados";
                
            }
            echo json_encode($response);
        }else if($accion == "buscar"){
        
        }
    }
    if(isset($_POST['accion'])){
        $accion = $_POST['accion'];    
        switch ($accion) {
            case 'agregar':
                if(isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['año']) && isset($_POST['no_serie'])) {
                    $marca = $_POST['marca'];
                    $modelo = $_POST['modelo'];
                    $año = $_POST['año'];
                    $no_serie = $_POST['no_serie'];
                    //Comprobamos la longitud de las cadenas
                    if(strlen($marca)>20 || strlen($modelo)>15 || strlen($año)>4){
                        $response["status"]= "Error";
                        $response["mensaje"]="Los datos ingresados superan el límite permitido.";
                    }else{
                        // Insertar los datos en la base de datos
                        $sql = "INSERT INTO Autos (marca, modelo, año, no_serie) VALUES (?,?,?,?)";
                        $stmt = $db->prepare($sql);
                        $stmt->bind_param("ssii", $marca, $modelo, $año, $no_serie);  
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
                    $sql = "SELECT * FROM Autos WHERE id_auto = $id";
                    $result = $db->query($sql);
                    if($result->num_rows > 0){
                        if($row = $result->fetch_assoc()) {
                            $marca = isset($_POST['marca']) && !empty($_POST['marca'])  ? $_POST['marca'] : $row['marca'];
                            $modelo = isset($_POST['modelo']) && !empty($_POST['modelo']) ? $_POST['modelo'] : $row['modelo'];
                            $año = isset($_POST['año']) && !empty($_POST['año'])  ? $_POST['año'] : $row['año'];
                            $no_serie = isset($_POST['no_serie']) && !empty($_POST['no_serie'])  ? $_POST['no_serie'] : $row['no_serie'];
                            //Comprobamos la longitud de las cadenas
                            if(strlen($marca)>20 || strlen($modelo)>15 || strlen($año)>4){
                                $response["status"]= "Error";
                                $response["mensaje"]="Los datos ingresados superan el límite permitido.";
                            }else{
                                // Insertar los datos en la base de datos
                                $sql = "UPDATE Autos SET marca = ?, modelo = ?, año = ?, no_serie = ? WHERE id_auto = ?";
                                $stmt = $db->prepare($sql);
                                $stmt->bind_param("ssiii", $marca, $modelo, $año, $no_serie, $id);  
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
                    $sql = "SELECT * FROM Autos WHERE id_auto = $id";
                    $result = $db->query($sql);
                    if($result->num_rows > 0){
                        $sql = "DELETE FROM Autos WHERE id_auto = ?";
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