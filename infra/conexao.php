<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "banco_exemplo";


$conn = new mysqli($host, $user, $password, $database, 6610);

if($conn-> connect_error){
   die ("Erro na conexão:" . $conn-> connect_error);
};

?>