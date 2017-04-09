<?php
////////////////// CONEXION A LA BASE DE DATOS //////////////////
  $dbserver = "localhost";
  $dbuser = "admaptec_sibaseb";
  $password = "SIbase2017";
  $dbname = "admaptec_jmln2";
 
 //para mantener la correspondencia codigo mes, el bucket 0 no tiene datos
 $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
?>




<html lang="es">

	<head>
		<title>Pago</title>
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

     <nav class="navbar navbar-light" style="background-color: #66ccff;">
      <div class="container-fluid">
        <div class="navbar-header">
       <a class="navbar-brand" href="http://www.centromusicalbase.com/sibase/">
       <span class="glyphicon glyphicon-home"></span>   Sistema Base</a>
       </div>
    </nav>

  <header>
    <div class="alert alert-info text-center">
    <h2>REGISTRO DE PAGOS</h2>
    </div>
  </header>
  
  <script>
  	 function botonAdd(e){
                e.preventDefault(); //evitar submit del form
		var idal = document.getElementById("idalumno").value;
		var recibo = document.getElementById("recibo").value;
		var asign = $("#asignacion").val();
		var parametros = {
                "idalumno" : idal,
                "recibo" : recibo,
                "asignacion" : asign                
        	};
        	
        	$.ajax({
        		data:	parametros,
        		url:	'addLine.php',
        		type: 	'post',
        		success: 	function(data){
				    $('#lista').append(data);
				    },
			error : 	function() {
			    console.log('error');
			}
        	});
        
	}
	
	function botonAceptar(e){
		e.preventDefault();
		$.post( "instPago.php", $( "#formulario" ).serialize() );
		$.post( "ImpreRecibo.php" );
		document.getElementById("actionButton").innerHTML = '<form method="post" action="ImpreRecibo.php" class="form-horizontal"><div style="text-align:center"> <input type="hidden" name="NoRecibo" value="'+$('#recibo').val()+'"><input type="submit" id="btnGenerar" name="recibo" class="btn btn-primary" value="Generar recibo"></div></div></form>';

	}
	
	$(document).ready(function() {
	    $('#btnAdd').click(botonAdd);
	    $('#btnAceptar').click(botonAceptar);
	    
	});
	
	function calcular(sufijo){
					var precio = document.getElementById("precio-"+sufijo).value;
					var mesF = $("#mesFinal-"+sufijo).val();
					var sub = Number(precio) * (Number(mesF)-Number(document.getElementById("mesInicial-"+sufijo).value))+Number(precio);
					document.getElementById("sub-"+sufijo).value = sub+'.00';
					var total = 0;
					$("[id^=sub-]").each(function(){
						total= total + Number($(this).val());
					});
					document.getElementById('total').value = total+'.00';
				}
				
	


  </script>
  
  <?php


  	 $idalumno=$_POST['idalumno'];

  	 $database = new mysqli($dbserver, $dbuser, $password, $dbname);

	 if($database->connect_errno) {
	   die("No se pudo conectar a la base de datos");
	 }

	$alumno="SELECT nombre, apellidos FROM cmb_alumno where idalumno=".$idalumno;
  	$queryAlumnos= $database->query($alumno);

  	while($registroAlumno  = $queryAlumnos->fetch_array( MYSQLI_BOTH))
	{
  	 $nombre=$registroAlumno['nombre'];
  	 $apellido=$registroAlumno['apellidos'];
   	}
   	
   	//listar opciones del combobox de asignaciones
   	$asignaciones = "SELECT a.IDCATEDRATICO, a.IDCURSO, ca.nombre as CATEDRATICO, cu.nombre as CURSO FROM cmb_asignacion a JOIN cmb_curso cu ON a.IDCURSO=cu.idcurso JOIN cmb_catedratico ca ON a.IDCATEDRATICO=ca.IDCATEDRATICO WHERE a.IDALUMNO=".$idalumno;
   	$queryAsign=$database->query($asignaciones);
   	$asignOptions="";
   	while($registroAsign = $queryAsign->fetch_array(MYSQLI_BOTH)){
   		$asignOptions = $asignOptions.'<option value="'.$registroAsign['IDCATEDRATICO'].'-'.$registroAsign['IDCURSO'].'">'.$registroAsign['CATEDRATICO'].' - '.$registroAsign['CURSO'].'</option>';
   	}

	mysqli_close($database);
  	
  ?>
  
  <form class="form-horizontal" id="formulario">
  	<div class="form-group">
		    <label class="control-label col-sm-5" for="nombre">Nombre del alumno:</label>
		    <div class="col-sm-3">
		      <input type="hidden" name="idalumno" id="idalumno" value="<?php echo $idalumno;?>">
		      <input class="form-control" id="nombre" name="nombre" value="<?php echo $nombre.' '.$apellido;?>" readonly>
		    </div>
	     </div>
	     
	  <div class="form-group">
	    <label class="control-label col-sm-5" for="fecha">Fecha:</label>
	    <div class="col-sm-3">
	      <input class="form-control" id="fecha" name="fecha" value="<?php echo date('d-m-Y');?>" readonly>
	    </div>    
	  </div>
	  
	  <div class="form-group">
	    <label class="control-label col-sm-5" for="recibo">Número de recibo:</label>
	    <div class="col-sm-3">
	      <input class="form-control" id="recibo" name="recibo" value="" required>
	    </div>    
	  </div>
	  
	  <div class="form-group">
	    <label class="control-label col-sm-5" for="asignacion">Maestro-Curso:</label>
	    <div class="col-sm-3">
	      <select class="form-control" id="asignacion" name="asignacion">
	      <?php echo $asignOptions;?>
	      </select>
	    </div>
	    <div class="col-sm-2">
	      <button name="btnAdd" id="btnAdd" class="btn btn-success btn-md" ><span class="glyphicon glyphicon-plus"></span> Agregar</button>
	    </div>
	  </div> 
	  <h3 class="bg-primary text-center pad-basic no-btm">Asignaciones a pagar: </h3>
	  <div id="lista"></div></br>
	  
	  <div class="form-group">
		    <label class="control-label col-sm-5" for="total">Total:</label>
		    <div class="col-sm-2">
		    <input class="form-control" id="total" name="total" value="" readonly>
		    </div>
		    <input type="submit" id="btnAceptar" name="insertar" class="col-sm-2 btn btn-warning" value="Aceptar">	    
	     </div>	  
	  </form>
	  <div id="actionButton"></div>
	
  </body>
  </html>
  
