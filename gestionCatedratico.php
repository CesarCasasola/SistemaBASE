

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



  $query="SELECT * FROM cmb_catedratico order by idcatedratico";
  $queryCatedratico= $database->query($query);
  mysqli_close($database);

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
			<h2>GESTIÓN DE CATEDRATICOS</h2>
			</div>
		</header>

		<form method="post" class="form-horizontal">
				<h3 class="bg-primary text-center pad-basic no-btm">Inscribir Catedratico</h3>

				<div style="overflow-x:auto;" class="container center_div">
				<table class="table bg-info"  id="tabla">
					<tr>
						<td><input required name="nombre" placeholder="Nombres Completo"/></td>
            <td><input required name="profesion" placeholder="Prefesion"/></td>
          	<td><label>Fecha de Nacimiento:</label>
						<input required type="date" name="fechNac"
						step="1" min="1900-01-01" max="<?php echo date('Y-m-d');?>"
						>
						</td>
					</tr>
					<tr>
						<td><input required name="telefono" placeholder="Teléfono"/></td>
						<td>
						<select required name="comp" class="btn-default">
							<option value="Claro">Claro</option>
							<option value="Tigo">Tigo</option>
							<option value="Movistar">Movistar</option>
						</select>
						</td>
					</tr>
          <tr>
            <td>

            </td>
          </tr>
					<tr>
						<td>
              <input required name="usuario" placeholder="Usuario"/>
            </td>
            <td>
              <input required  type="password" name="password" SIZE="tamaño" MAXLENGTH="10>longitud máxima" placeholder="Password"/>
            </td>
						<td>
						<input type="submit" name="insertar" value="Insertar Catedratico" class="btn btn-info"/>
						</td>
					</tr>
				</table>
				</div>

			</form>


			<?php

				$database = new mysqli($dbserver, $dbuser, $password, $dbname);

				if($database->connect_errno) {
				    die("No se pudo conectar a la base de datos");
				}else{

				//////////////////////// PRESIONAR EL BOTÓN //////////////////////////
				if(isset($_POST['insertar']))

				{

				$nombres=($_POST['nombre']);
        $profesion=($_POST['profesion']);
				$fecha=($_POST['fechNac']);
				$telefono=($_POST['telefono']);
				$compania=($_POST['comp']);
				$usuarios=($_POST['usuario']);
        $contrasenia=($_POST['password']);


				$consultaD="SELECT idcatedratico FROM cmb_catedratico WHERE PASSWORD='".$contrasenia."' AND (nombre='".$nombres."' AND FECHA_NACIMIENTO='".$fecha."' AND USUARIO='".$usuarios."');";
				$resultD=$database->query($consultaD) or mysql_error();
				//si la consulta no arroja un registro repetido
				if(mysqli_num_rows($resultD)==0){

				//////// QUERY DE INSERCIÓN ////////////////////////////
				$sql = "INSERT INTO cmb_catedratico (nombre, FECHA_NACIMIENTO, PROFESION, telefono, compania, USUARIO, PASSWORD, ACTIVO ) VALUES ('$nombres','$fecha','$profesion','$telefono','$compania','$usuarios','$contrasenia', 1)";

				$sqlRes=$database->query($sql) or mysql_error();
				echo '<script>recargar();</script>';
				}


				}
					mysqli_close($database);
				}



			?>


			<div style="overflow-x:auto;">
			<table class="table table-active">

					<thead>
					<tr class="info">
						<th>#</th>
						<th>Nombres</th>
						<th>Fecha Nacimiento</th>
						<th>Profesion</th>
						<th>Telefono</th>
						<th>Compañía</th>
						<th>Usuario</th>

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


				    	while($registroCatedraticos  = $queryCatedratico->fetch_array( MYSQLI_BOTH))
				  {

            //creacion de elemento grafico para representar alumno activo o inactivo
            $botonActivo="";
            if($registroCatedraticos['ACTIVO'] == 1){
              $botonActivo='<button type="button" class="btn  btn-success" disabled> Activo </button>';
            }else{
              $botonActivo='<button type="button" class="btn  btn-danger" disabled>Inactivo</button>';
            }

            $checkActivo="";
  					if($registroCatedraticos['ACTIVO'] == 1){
  					 	$checkActivo='<label class="form-check-label">
  											 <input type="radio" class="form-check-input" name="activo" checked value="1">
  											 Activo
  										    </label></br>
  									<label class="form-check-label">
  											 <input type="radio" class="form-check-input" name="activo" value="0">
  											 Inactivo
  										    </label>';
  					}else{
  						$checkActivo='<label class="form-check-label">
  											 <input type="radio" class="form-check-input" name="activo" value="1">
  											 Activo
  										    </label></br>
  									<label class="form-check-label">
  											 <input type="radio" class="form-check-input" name="activo" value="0" checked>
  											 Inactivo
  										    </label>';
  					}
				  echo '<tr>
				    	<td>'.$registroCatedraticos['idcatedratico'].'</td>
				    	<td>'.$registroCatedraticos['nombre'].'</td>
				    	<td>'.$registroCatedraticos['FECHA_NACIMIENTO'].'</td>
				    	<td>'.$registroCatedraticos['PROFESION'].'</td>
				    	<td>'.$registroCatedraticos['telefono'].'</td>
				    	<td>'.$registroCatedraticos['compania'].'</td>
				    	<td>'.$registroCatedraticos['USUARIO'].'</td>
              <td>'.$botonActivo.'
				    	<button class="btn btn-warning" data-toggle="modal" data-target="#edit-'.$registroCatedraticos['idcatedratico'].'">Editar</button>

				    	<div class="modal fade" id="edit-'.$registroCatedraticos['idcatedratico'].'" tabindex="-1" role="dialog"
				    	aria-labelledby="editLabel-'.$registroCatedraticos['idcatedratico'].'">
				    		<div class="modal-dialog" role="document">
				    			<div class="modal-content">
				    				<div class="modal-header">
				    					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
				    					<h4 class="modal-tittle" id="editLabel-'.$registroCatedraticos['idcatedratico'].'">Editar</h4>
				    				</div>
				    				<form class="form-vertical" method="POST" action="upCatedratico.php">
				    					<div class="modal-body form-group">
				    						<label class"control-label col-sm-2">Nombres: </label><input class="form-control" required name="nombre" id="nombre-'.$registroCatedraticos['idcatedratico'].'" value="'.$registroCatedraticos['nombre'].'" /></br>
                        <input type="hidden" name="ide" value="'.$registroCatedraticos['idcatedratico'].'"/>
				    						<label class"control-label col-sm-2">Fecha de Nacimiento:</label>
										<input class="form-control" required type="date" name="fechNac" id="fechNac-'.$registroCatedraticos['idcatedratico'].'" step="1" min="1900-01-01" max="<?php echo date('.'Y-m-d'.');?>" value="'.$registroCatedraticos['FECHA_NACIMIENTO'].'" ></br>
										<label class"control-label col-sm-2">Profesion: </label><input class="form-control" required name="profesion" id="profesion'.$registroCatedraticos['idcatedratico'].'" value="'.$registroCatedraticos['PROFESION'].'" /></br>
										<label class"control-label col-sm-2">Teléfono: </label><input class="form-control" required name="telefono" id="telefono-'.$registroCatedraticos['idcatedratico'].'"  value="'.$registroCatedraticos['telefono'].'" /></br>
                    <label class"control-label col-sm-2">Compañía: </label><select class="form-control" required name="comp" id="comp-'.$registroCatedraticos['idcatedratico'].'" class="btn-default" value="'.$registroCatedraticos['compania'].'">
							<option value="Claro">Claro</option>
							<option value="Tigo">Tigo</option>
							<option value="Movistar">Movistar</option>
                  	</select>
                    <label class"control-label col-sm-2">Usuario: </label><input class="form-control" required name="usuarios" id="usuario-'.$registroCatedraticos['idcatedratico'].'" value="'.$registroCatedraticos['USUARIO'].'" /></br>
                    <label class"control-label col-sm-2">Password: </label><input type="password" class="form-control" required name="pass" id="pass-'.$registroCatedraticos['idcatedratico'].'" value="'.$registroCatedraticos['PASSWORD'].'" /></br>
						</select>
            <div class="form-check">
               '.$checkActivo.'
             </div>

				    					</div>
				    					<div class="modal-footer">
				    						<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				    						<input type="submit" name="edit" value="Actualizar" class="btn btn-primary"/>
				    					</div>
				    				</form>
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
