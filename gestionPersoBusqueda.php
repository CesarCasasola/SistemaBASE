<?php
////////////////// CONEXION A LA BASE DE DATOS //////////////////
 $dbserver = 'localhost';
 $dbuser = 'root';
 $password = '';
 $dbname = 'admaptec_jmln2';

 $database = new mysqli($dbserver, $dbuser, $password, $dbname);

 if($database->connect_errno) {
   die("No se pudo conectar a la base de datos");
 }

 if(isset($_POST['enviar']) ){
   $cod=$_POST['nombre'];
   $contra=$_POST['password'];

   $sql = "SELECT IDCATEDRATICO, nombre, FECHA_NACIMIENTO, PROFESION, telefono, compania, PASSWORD, ACTIVO FROM cmb_catedratico WHERE USUARIO='$cod'";
     $resultD=$database->query($sql) or mysql_error();
   $row=$resultD->fetch_array( MYSQLI_BOTH);
 $contrasena = $row["PASSWORD"];
 $nombress = $row['nombre'];
 $idCate = $row['IDCATEDRATICO'];
 $estado = $row['ACTIVO'];
  if ($contrasena == $contra && $estado==1)
  {
    echo '<input type="hidden" name="resto" value="ingreso">';
  }
  else if($contrasena != $contra || $estado==0){

    header('Location: fd_error_conexion.html');
  }
  mysqli_close($database);
 }
 else if(isset($_POST['reso'])){

   $sql = "SELECT IDCATEDRATICO, nombre, FECHA_NACIMIENTO, PROFESION, telefono, compania, PASSWORD FROM cmb_catedratico WHERE IDCATEDRATICO='".$_POST['reso']."'";
     $resultD=$database->query($sql) or mysql_error();
   $row=$resultD->fetch_array( MYSQLI_BOTH);
 $contrasena = $row["PASSWORD"];
 $nombress = $row['nombre'];
 $idCate = $row['IDCATEDRATICO'];

  mysqli_close($database);
 }

?>
<html lang="es">

	<head>
		<title></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>


		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<!-- Optional theme -->

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>




	</head>

	<body>

		<script>
				function deleteData(str){

					var id = str;
					var conf = window.confirm("¿Realmente desea eliminar al Catedratico con codigo: "+id+"?");
					if(conf == true){
						window.location = "delCatedratico.php?id="+id;
					}
				}

				function recargar(){
					location.reload();
				}

			</script>
      <nav class="navbar navbar-light" style="background-color: #66ccff;">
  			  <div class="container-fluid">
  			    <div class="navbar-header">
  				 <a class="navbar-brand" href="http://www.centromusicalbase.com/sibase">
  				 <span class="glyphicon glyphicon-home"></span>   Sistema Base</a>
  			   </div>
  	   	</nav>
		<header>
			<div class="alert alert-info text-center">
			<h2>GESTION DE CURSOS</h2>
			</div>
		</header>

		<form method="post" class="form-horizontal">
      <!--<h3 class="bg-primary text-center pad-basic no-btm">Inscribir Catedratico</h3>-->
      	<h3 class="bg-primary text-center pad-basic no-btm">
          <?php
          //$comp=$_POST['comp'];
            echo 'Registro de: '. $nombress;
            echo '<input type="hidden" name="reso" value="'.$idCate.'">';
         ?>
        </h3>

				</div>
        <div style="overflow-x:auto;" class="container right_div" align="right">
          <label>Buscar por nombre de Alumno:</label></br>
          <input name="nombres" placeholder="Nombres Completo"/></br></br>

          <input type="submit" name="Buscar" value="Buscar" class="btn btn-info"/>
        </div>
			</form>


			<div style="overflow-x:auto;">
			<table class="table table-active">
					<thead>
					<tr class="info">
						<th>#</th>
						<th>Nombre de Alumno</th>
						<th>Tutor/Encargado</th>
						<th>Telefono y Compañía</th>
						<th>Curso</th>
						<th>Cuota</th>
            <th>Catedrático</th>

						<th width="250" class="text-centered">Opcion</th>

				    	</tr>
				    	</thead>
					<tbody>


				    	<?php
				    	//for presenting data from DB
				    	$database = new mysqli($dbserver, $dbuser, $password, $dbname);

						  if($database->connect_errno) {
						    die("No se pudo conectar a la base de datos");
						  }

              $btnasignacion="";
              $btnaedisig="";

                $query4="SELECT `da`.`IDCATEDRATICO` AS `idT`, `c`.`idcurso` AS `idC`, `us`.`idalumno` AS `idA`,`us`.`nombre` AS `alumName`, `us`.`apellidos` AS `alumApe`, `us`.`PADRE` AS `Tutor`, `us`.`TELEFONO` AS `TEL`, `us`.`COMPANIA` AS `COMI` , `c`.`nombre` AS `CURNAME`, `da`.`nombre` AS `CATNAME`, `sig`.`PRECIO` AS `VALOR`\n"
    . "FROM cmb_asignacion sig JOIN cmb_alumno us, cmb_curso c, cmb_catedratico da\n"
    . "WHERE `sig`.`IDALUMNO` = `us`.`idalumno`\n"
    . "AND `sig`.`IDCURSO` = `c`.`idcurso`\n"
    . "AND `sig`.`IDCATEDRATICO` = `da`.`idcatedratico`\n"
    . "AND `us`.`ACTIVO` = 1 \n"
    . "AND `sig`.`IDCATEDRATICO` = ".$idCate."\n";
                $queryCte21= $database->query($query4);
                $btnaedisig='<button class="btn  btn-success btn-block" name="edit" value="asignar1"> Editar Asignación </button>';
                if(!isset($_POST['nombres']) || $_POST['nombres']==""){

                }else{

    $query5="SELECT `da`.`IDCATEDRATICO` AS `idT`, `c`.`idcurso` AS `idC`, `us`.`idalumno` AS `idA`, `us`.`nombre` AS `alumName`, `us`.`apellidos` AS `alumApe`, `us`.`PADRE` AS `Tutor`, `us`.`TELEFONO` AS `TEL`, `us`.`COMPANIA` AS `COMI` , `c`.`nombre` AS `CURNAME`, `da`.`nombre` AS `CATNAME`, `sig`.`PRECIO` AS `VALOR`\n"
    . "FROM cmb_asignacion sig JOIN cmb_alumno us, cmb_curso c, cmb_catedratico da\n"
    . "WHERE `sig`.`IDALUMNO` = `us`.`idalumno`\n"
    . "AND `sig`.`IDCURSO` = `c`.`idcurso`\n"
    . "AND `sig`.`IDCATEDRATICO` = `da`.`idcatedratico`\n"
    . "AND `us`.`ACTIVO` = 1 \n"
    . "AND `sig`.`IDCATEDRATICO` = ".$idCate."\n"
    . "AND ( UPPER(`us`.`nombre`) LIKE UPPER('%".$_POST['nombres']."%') \n"
    . "OR UPPER(`us`.`apellidos`) LIKE UPPER('%".$_POST['nombres']."'))";
                $queryCte21= $database->query($query5);
                $btnaedisig='<button class="btn btn-success btn-block" name="edit" value="asignar1"> Editar Asignación </button>';

              }

              $var = 1;
				    	while($registrosfiltrados  = $queryCte21->fetch_array( MYSQLI_BOTH))
				  {
                echo '<tr>
      				    	<td>'.$var++.'</td>
      				    	<td>'.$registrosfiltrados['alumName'].' '.$registrosfiltrados['alumApe'].'</td>
                    <td>'.$registrosfiltrados['Tutor'].'</td>
                    <td>'.$registrosfiltrados['TEL'].' -- '.$registrosfiltrados['COMI'].'</td>
                    <td>'.$registrosfiltrados['CURNAME'].'</td>
                    <td> Q.'.$registrosfiltrados['VALOR'].'</td>
                    <td>'.$registrosfiltrados['CATNAME'].'</td>
                    <td>
                    <button class="btn btn-info" data-toggle="modal" data-target="#edit-'.$registrosfiltrados['idA'].'">Ver Opciones</button>
                    <div class="modal fade" id="edit-'.$registrosfiltrados['idA'].'" tabindex="-1" role="dialog"
      				    	aria-labelledby="editLabel-'.$registrosfiltrados['idA'].'">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-tittle" id="editLabel-'.$registrosfiltrados['idA'].'">Opciones</h4>
                          </div>
                            <div class="modal-body form-group">

                            <form class="form-vertical" method="POST" action="Pago.php">
                              <button class="btn btn-info btn-block" name="pagos" value="Pago">PAGO</button>
                              <input type="hidden" name="idAlum" value="'.$registrosfiltrados['idA'].'">
                            </form>
                            <form class="form-vertical" method="POST" action="historial.php">
                                  <button class="btn btn-warning btn-block" name="historial" value="historial">HISTORIAL DE PAGO</button>
                                  <input type="hidden" name="idalummp" value="'.$registrosfiltrados['idA'].'"/>
                                  <input type="hidden" name="idcurso" value="'.$registrosfiltrados['idC'].'"/>
                            </form>
                            <form class="form-vertical" method="POST" action="asignaciones.php">
                              '.$btnasignacion.'
                                  <input type="hidden" name="idAlum" value"idAlum" value="'.$registrosfiltrados['idA'].'"/>
                            </form>
                            <form class="form-vertical" method="POST" action="asignaciones.php">

                              <input type="hidden" name="idalumno" value="'.$registrosfiltrados['idA'].'"/>
                              <input type="hidden" name="idcurso"  value="'.$registrosfiltrados['idC'].'"/>
                              <input type="hidden" name="idcatedratico" value="'.$registrosfiltrados['idT'].'"/>
                              '.$btnaedisig.'
                            </form>
                            </div>
                            <div class="modal-footer">
      				    						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      				    					</div>
                        </div>
                      </div>
                    </div>
                    </td>
      				    </tr>';

				   }

				   	mysqli_close($database);
				    	?>


				    	</tbody>
			</table>
			</div>


	</body>
</html>
