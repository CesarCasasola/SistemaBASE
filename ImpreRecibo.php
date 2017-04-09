<?php
/*	$dbserver = "localhost";
	$dbuser = "root";
	$password = "";
	$dbname = "admaptec_jmln2";*/

	$dbserver = 'localhost';
  $dbuser = 'admaptec_sibaseb';
  $password = 'SIbase2017';
  $dbname = 'admaptec_jmln2';

 	$database = new mysqli($dbserver, $dbuser, $password, $dbname);

 	if($database->connect_errno) {
	 die("No se pudo conectar a la base de datos");
 	}

	if (isset($_POST['recibo'])) {
		# code...
	//	$NumRecibo = $_POST['NoRecibo'];

	}

	$NumRecibo = 2;
	$query1 = "SELECT * FROM cmb_recibo WHERE NUMRECIBO = $NumRecibo";
	$queryRecibo = $database->query($query1);

	while ($registroRecibo = $queryRecibo->fetch_array( MYSQLI_BOTH)) {
		# code...
			$ValorTotal = $registroRecibo['TOTAL'];
	}

	$anioActual = date("Y");
	$mesActual = date("m");
	$diaActual = date("d");

?>
<html>
<header>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<!-- Optional theme -->

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

	<link href="css/estilo.css"rel="stylesheet" type="text/css">




</header>
<body>
<div class="alert alert-info text-center">
	 <h1>Pre-Visualización de recibo</h1>
</div>
	<fieldset>
	 	<h1>Recibo</h1>
	 	<p class='center sub-titulo'>
	 	<?php
	 		echo 'Nº <strong>'.$NumRecibo.'</strong> -
	 		VALOR <strong>Q.'.$ValorTotal.' </strong>';
	 		?>
	 	</p>
	 		 	<p class='direita'>
	 	<?php
	 	switch ($mesActual){
	 		case 1:
	 			$mesName = "Enero";
	 			break;
	 		case 2:
	 			$mesName = "Febrero";
	 			break;
	 		case 3:
	 			$mesName = "Marzo";
	 			break;
	 		case 4:
	 			$mesName = "Abril";
	 			break;
	 		case 5:
	 			$mesName = "Mayo";
	 			break;
	 		case 6:
	 			$mesName = "Junio";
	 			break;
	 		case 7:
	 			$mesName = "Julio";
	 			break;
	 		case 8:
	 			$mesName = "Agosto";
	 			break;
	 		case 9:
	 			$mesName = "Septiembre";
	 			break;
	 		case 10:
	 			$mesName = "Octubre";
	 			break;
	 		case 11:
	 			$mesName = "Noviembre";
	 			break;
	 		case 12:
	 			$mesName = "Diciembre";
	 			break;
	 	}
	 	echo 'Chiquimula, '.$diaActual.' de '.$mesName.' de '.$anioActual.'';
	 	?>
	 	</fieldset>
	 	<fieldset>
	 	</p>
			<form method="post" class="form-horizontal"  action="index2.php">
				<!--Envios Ocultos -->
				<?php
				echo '<input type="hidden" name="valor" value="'.$ValorTotal.'"/>
							<input type="hidden" name="recibonum" value="'.$NumRecibo.'"/>';
			//	echo ''.$NumRecibo.'';
				?>
	 	<p>Recibido de: <strong>
				<input required name="nombre" placeholder="Nombre Completo" />
	 </strong></p>

	 	<p>NIT: <strong>
			<input required name="nit" placeholder="1234567-8"/>
	 	</strong></p>
	 	<p>Cantidad en Quetzales: <strong>
				<input required name="canti" placeholder="Cien"/>
		</strong></p>
	 	<p>Correspondente a: <strong> Pago de Mesualidad de Cursos</strong></p>


	 	<p>Detalle:
	 	<table border="1px" class="table table-active" name="tab">
	 		<tr HEIGHT="7" >
	 			<td width="50">COD</td>
	 			<td>Descripción</td>
	 			<td width="120">Valor</td>
	 		</tr>

			<?php
				$query2 = "SELECT c.nombre, c.idcurso, p.DESCRIPCION, p.SUBTOTAL FROM cmb_pago p JOIN cmb_curso c ON p.IDCURSO = c.idcurso WHERE p.NUMRECIBO = $NumRecibo";
				$queryPago =  $database->query($query2);
				while ($registroPago = $queryPago->fetch_array( MYSQLI_BOTH)) {
					# code...
					echo '<tr>
								<td wiwidth="50">
								'.$registroPago['idcurso'].'
								</td>
								<td>
								'.$registroPago['nombre'].' '.$registroPago['DESCRIPCION'].'
								</td>
								<td>
								'.$registroPago['SUBTOTAL'].'
								</td>
								</tr>';
				}
				echo '<tr>
							<td colspan="2">
								<p align="right"> Total:
							</td>
							<td>
							'.$ValorTotal.'
							</td>
							</tr>';
			?>
	 	</table>
	 	</p>

	 </fieldset>
	 <br>
	 <center>
	 	<input type="submit" name="insertar" value="Imprimir Recibo" class="btn btn-info"/>
	</center>
	<br>
		</form>
</body>
</html>
