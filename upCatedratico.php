<?php

 $dbserver = 'localhost';
 $dbuser = 'root';
 $password = '';
 $dbname = 'admaptec_jmln2';

  $database = new mysqli($dbserver, $dbuser, $password, $dbname);

  if($database->connect_errno) {
    die("No se pudo conectar a la base de datos");
  }

  if(isset($_POST['edit'])){
  	$id=$_POST['ide'];
  	$nombre=$_POST['nombre'];
  	$fecha=$_POST['fechNac'];
    $profesi=$_POST['profesion'];
  	$tel=$_POST['telefono'];
  	$comp=$_POST['comp'];
  	$cod=$_POST['usuarios'];
    $contrasenia=$_POST['pass'];

    if($_POST['activo'] == 1){
      $query="UPDATE cmb_catedratico SET nombre='$nombre', FECHA_NACIMIENTO='$fecha', PROFESION='$profesi', telefono='$tel', compania='$comp', USUARIO='$cod', PASSWORD='$contrasenia', ACTIVO=1 WHERE idcatedratico='$id'";

    }else{
      $query="UPDATE cmb_catedratico SET nombre='$nombre', FECHA_NACIMIENTO='$fecha', PROFESION='$profesi', telefono='$tel', compania='$comp', USUARIO='$cod', PASSWORD='$contrasenia', ACTIVO=0 WHERE idcatedratico='$id'";

    }
    $result=$database->query($query);
  	mysqli_close($database);
  	header('Location:	gestionCatedratico.php');
  }
?>
