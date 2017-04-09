<?php
	include("mpdf60/mpdf.php");

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

	$NumRecibo = $_POST['recibonum'];
	$ValorTotal = $_POST['valor'];
	$NomRecibe = $_POST['nombre'];
	$anioActual = date("Y");
	$mesActual = date("m");
	$diaActual = date("d");
	$Nit = $_POST['nit'];
	$caop = $_POST['canti'];

	$query2 = "SELECT c.nombre, c.idcurso, p.DESCRIPCION, p.SUBTOTAL FROM cmb_pago p JOIN cmb_curso c ON p.IDCURSO = c.idcurso WHERE p.NUMRECIBO = $NumRecibo";
	$queryPago =  $database->query($query2);
	while ($registroPago = $queryPago->fetch_array( MYSQLI_BOTH)) {
		# code...
		$tablecor.= '<tr>
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
	$cabecera = '
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<!-- Optional theme -->

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

		<br>
		<div name = "head" id ="head">
		<p style="margin-left: 1em"> <IMG SRC="img/logo.png" WIDTH=160 HEIGHT=160 > </p>
		</div>
	';
	$html = '
		<fieldset>
	 	<h1>Recibo</h1>
	 	<p class="center sub-titulo">
	 		Nº <strong>'.$NumRecibo.'</strong> -
	 		VALOR <strong>Q.'.$ValorTotal.' </strong>
	 	</p>
	 		 	<p class="direita">

	 	Chiquimula, '.$diaActual.' de '.$mesName.' de '.$anioActual.'

	 			</p>
	 	</fieldset>
	 	<fieldset>
	 	<p>Recibido de: <strong>
	 		'.$NomRecibe.'
	 	</strong></p>
	 	<p>NIT: <strong>
	 	'.$Nit.'
	 	</strong></p>
	 	<p>Cantidad en Quetzales: <strong>'.$caop.'</strong></p>
	 	<p>Correspondente a: <strong> Pago de Mesualidad de Cursos</strong></p>


	 	<p>Detalle:
	 	<fieldset>
	 	<table border="1px" class="table table-active">
	 		<tr HEIGHT="7" >
	 			<td width="50">COD</td>
	 			<td width="500" >Descripción</td>
	 			<td width="120">Valor</td>
	 		</tr>'.$tablecor.'
			<tr>
						<td colspan="2">
							<p align="right"> Total:
						</td>
						<td>
						'.$ValorTotal.'
						</td>
						</tr>
	 	</table>
	 	</fieldset>

	 	</p>
	 	<p>
	 	Dirección <strong>Centro Musical Base, 9na Av. 5-50 Zona 1, Chiquimula. </strong></p>
	 </fieldset>';

	$mpdf=new mPDF();
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->SetHTMLHeader($css,1);
	$mpdf->SetHTMLHeader($cabecera);
	$css = file_get_contents("css/estilo.css");
	$mpdf->WriteHTML($css,1);
	$mpdf->WriteHTML($html);
	$mpdf->Output();

	exit;
