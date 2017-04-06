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



//  $query="SELECT * FROM cmb_catedratico WHERE ACTIVO=1 order by idcatedratico";
//  $queryCatedratico= $database->query($query);
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
			<h2>HISTORIAL DE PAGO</h2>
			</div>
		</header>

		<form method="post" class="form-horizontal">
      <!--<h3 class="bg-primary text-center pad-basic no-btm">Inscribir Catedratico</h3>-->
      	<h3 class="bg-primary text-center pad-basic no-btm">
          <?php
            if(isset($_POST['historial'])){
          $comp=$_POST['idalumno'];
          $cur =$_POST['idcurso'];
        //  echo " ". $comp. " ".$cur." ";

            $database = new mysqli($dbserver, $dbuser, $password, $dbname);

            $query2="SELECT * FROM cmb_pago WHERE IDCURSO = $comp AND IDALUMNO = $cur ORDER BY NUMRECIBO DESC LIMIT 1";
            $queryCte= $database->query($query2);
            //Busqueda de valores

             $row_cnt = $queryCte->num_rows;
          if($row_cnt>0){
            $registroCte  = $queryCte->fetch_array( MYSQLI_BOTH);
            $mespuntero = $registroCte['ULTIMOMES'];
            $aniopuntero = $registroCte['ANIO'];
            $idAL =  $registroCte['IDCATEDRATICO'];
            $idCU =  $registroCte['IDCURSO'];
          //  echo $idAL;
            $queryAL="SELECT * FROM cmb_alumno WHERE idalumno = $idAL";
            $queryLUM= $database->query($queryAL);
            //Busqueda de valores v.2
            $registroALUM = $queryLUM->fetch_array( MYSQLI_BOTH);
            $nomAlumno = $registroALUM['nombre'];
            $nomApellido = $registroALUM['apellidos'];
            $BornDate = $registroALUM['FECHA_NACIMIENTO'];
            $FatherAlum = $registroALUM['PADRE'];
            $CelAlum = $registroALUM['TELEFONO'];
            $CompAlum = $registroALUM['COMPANIA'];

            $queryCurso="SELECT * FROM cmb_curso WHERE idcurso = $idCU";
            $queryVC = $database->query($queryCurso);
            //Busqueda de valores v.3
            $registroCurs = $queryVC->fetch_array( MYSQLI_BOTH);
            $nomCurso = $registroCurs['nombre'];
          //  echo 'Registro de: '.$nomAlumno.' '.$nomApellido.' y del curso: '.$nomCurso.' ';
            }
            elseif ($row_cnt==0) {
              # code...
              $queryAL="SELECT * FROM cmb_alumno WHERE idalumno = $comp";
              $queryLUM= $database->query($queryAL);
              //Busqueda de valores v.2
              $registroALUM = $queryLUM->fetch_array( MYSQLI_BOTH);
              $nomAlumno = $registroALUM['nombre'];
              $nomApellido = $registroALUM['apellidos'];
              $BornDate = $registroALUM['FECHA_NACIMIENTO'];
              $FatherAlum = $registroALUM['PADRE'];
              $CelAlum = $registroALUM['TELEFONO'];
              $CompAlum = $registroALUM['COMPANIA'];

              $queryCurso="SELECT * FROM cmb_curso WHERE idcurso = $cur";
              $queryVC = $database->query($queryCurso);
              //Busqueda de valores v.3
              $registroCurs = $queryVC->fetch_array( MYSQLI_BOTH);
              $nomCurso = $registroCurs['nombre'];
            //  echo 'Registro de: '.$nomAlumno.' '.$nomApellido.' y del curso: '.$nomCurso.' ';
            }
            $queryInfo = "SELECT * FROM cmb_asignacion WHERE IDALUMNO = $comp AND IDCURSO = $cur";
            $queryIN = $database->query($queryInfo);

            $registroInfo = $queryIN->fetch_array( MYSQLI_BOTH);
            $infoHorario = $registroInfo['HORARIO'];
            $infoDia = $registroInfo['DIA'];
            $infoPrecio = $registroInfo['PRECIO'];
            $infoCate = $registroInfo['CATEGORIA'];

          }?>
        </h3>
        <div style="overflow-x:auto;" class="container center_div">
          <?php
          echo  '
        <table class="table bg-info"  id="tabla">
          <tr>
          <td colspan="3">
          <label class="control-label " for="nombre">Información del Alumno</label>
          </td>
          </tr>
          <tr>
            <td>
              Nombre:
              <input class="form-control" id="nombre" name="nombre" value="'.$nomAlumno.' '.$nomApellido.'" disabled="true"/>
            </td>
            <td>
            Padre/Encargado:
            <input class="form-control" name="padre" value="'.$FatherAlum.'" disabled ="true"/>
            </td>
            <td>
            Telefono y Compañia:
            <input class="form-control" name="telefpno" value="'.$CelAlum.' - '.$CompAlum.'" disabled ="true"/>
            </td>
          </tr>
          <tr>
          <td colspan="3">
          <label class="control-label " for="noe">Información del Curso</label>
          </td>
          </tr>
          <tr>
            <td>
            Curso:
            <input class="form-control" name="curs" value="'.$nomCurso.'"  disabled="true"/>
            </td>
            <td>
             Horario: (día-hora)
             <input class="form-control" name="hpr" value="'.$infoDia.' - '.$infoHorario.'" disabled="true"/>
            </td>
            <td>
              Mensualidad:
              <input class="form-control" name="mensu" value="Q.'.$infoPrecio.'" disabled="true"/>
            </td>

        </table>
        </div>
      ';
      ?>
				<div style="overflow-x:auto;" class="container center_div">

        </div>
			</form>




			<div style="overflow-x:auto;">
			<table class="table table-active">
					<thead>
					<tr class="info">
						<th>Año</th>
						<th>Enero</th>
						<th>Febrero</th>
						<th>Marzo</th>
            <th>Abril</th>
						<th>Mayo</th>
						<th>Junio</th>
            <th>Julio</th>
            <th>Agosto</th>
            <th>Septiembre</th>
            <th>Octubre</th>
            <th>Noviembre</th>
            <th>Diciembre</th>

				    	</tr>
				    </thead>
					<tbody>


				    	<?php
				    	//for presenting data from DB
				    	$database = new mysqli($dbserver, $dbuser, $password, $dbname);

						  if($database->connect_errno) {
						    die("No se pudo conectar a la base de datos");
						  }

              $anioActual = date("Y");
              $anioAnterior = $anioActual-1;
              $meses = 12;
              $Mmes = 1;
            if ($row_cnt==0) {
              # code...
              while ($anioAnterior <= $anioActual) {
                # code...

                echo '
                      <tr>
                      <td>'.$anioAnterior.' </td>';
                for ($i=0; $i < $meses ; $i++) {
                  # code...
                  echo '<td>Sin Cancelar</td>';
                }
                echo '</tr>';
                $anioAnterior++;
              }
            }
            elseif ($row_cnt>0) {
              # code...
              while ($anioAnterior <= $anioActual){

                echo '
                      <tr>
                      <td>'.$anioAnterior.' </td>';
                if ($anioAnterior < $aniopuntero) {
                  # code...
                  for ($i=0; $i < $meses ; $i++) {
                    # code...
                      echo '<td>Cancelado</td>';

                  }
                }
                elseif ($anioAnterior == $aniopuntero) {
                  # code...
                  for ($i=0; $i < $meses ; $i++) {
                    # code...
                    if($Mmes <= $mespuntero ){
                      echo '<td>Cancelado</td>';
                    }
                    elseif ($Mmes>$mespuntero ) {
                      # code...
                      echo '<td>Sin Cancelar</td>';
                    }

                    $Mmes++;
                  }
                }
                elseif ($anioAnterior > $aniopuntero) {
                  # code...
                  for ($i=0; $i < $meses ; $i++){
                    echo '<td>Sin Cancelar</td>';
                  }
                }
                echo '</tr>';
                $Mmes = 1;
                $anioAnterior++;
              }
				        }

				   	mysqli_close($database);
				    	?>


				    	</tbody>
			</table>
			</div>


	</body>
  <footer>
    <nav class="navbar navbar-light" style="background-color: #66ccff;">
        <div class="container-fluid">
          <div class="navbar-header">
         <a class="navbar-brand" href="gestionBusqueda.php">
         <span class="glyphicon glyphicon-folder-open"></span> Gestor de Busqueda Alumno-Curso</a>
         </div>
      </nav>
  </footer>
</html>
