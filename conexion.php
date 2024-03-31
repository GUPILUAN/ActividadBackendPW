<?php

$db_host = "localhost:3306";  
$db_username = "progWeb";     
$db_password = "abc123456";   
$db_database = "Concesionaria";

$db = new mysqli($db_host, $db_username, $db_password, $db_database);

if($db->connect_errno > 0){
    die("No es posible conectarse a la base de datos [ ". $db->connect_error . " ]");
}
///
$sql_autos = "CREATE TABLE IF NOT EXISTS Autos (
    id_auto  INT NOT NULL AUTO_INCREMENT,
    marca    VARCHAR(20) NOT NULL,
    modelo   VARCHAR(15) NOT NULL,
    año      INT NOT NULL,
    no_serie INT NOT NULL,
    PRIMARY KEY (id_auto)
)";

if (!$db->query($sql_autos)) {
die("Error al crear la tabla Autos: " . $db->error);
}

///
$sql_clientes = "CREATE TABLE IF NOT EXISTS Clientes (
        id_cliente INT NOT NULL AUTO_INCREMENT,
        nombre     VARCHAR(30) NOT NULL,
        email      VARCHAR(30) NOT NULL,
        PRIMARY KEY (id_cliente)
    )";

if (!$db->query($sql_clientes)) {
die("Error al crear la tabla Clientes: " . $db->error);
}

///
$sql_propiedad = "CREATE TABLE IF NOT EXISTS Propiedad (
        id  INT NOT NULL AUTO_INCREMENT,
        autos_id_auto INT NOT NULL,
        clientes_id_cliente INT NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (autos_id_auto) REFERENCES autos (id_auto),
        FOREIGN KEY (clientes_id_cliente) REFERENCES clientes (id_cliente)
    )";
if (!$db->query($sql_propiedad)) {
die("Error al crear la tabla Propiedad: " . $db->error);
}

echo "Tablas creadas (si no existían) correctamente\n";

?>
