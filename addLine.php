<?php
////////////////// CONEXION A LA BASE DE DATOS //////////////////
 $dbserver = '127.0.0.1';
 $dbuser = 'root';
 $password = 'dbn0w';
 $dbname = 'admaptec_jmln2';
 
 //para mantener la correspondencia codigo mes, el bucket 0 no tiene datos
 $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
 
 
 $recibo=$_POST['recibo'];
 $idalumno=$_POST['idalumno'];
 $asignacion=$_POST['asignacion'];
 $idcatedratico=substr($asignacion, 0, (strpos($asignacion, "-")));
 $idcurso=substr($asignacion, (strpos($asignacion, "-")+1));
 //echo $meses[(int)date('m')];
   	 $database = new mysqli($dbserver, $dbuser, $password, $dbname);

	 if($database->connect_errno) {
	   die("No se pudo conectar a la base de datos");
	 }

	$reciboE="SELECT * FROM cmb_recibo where NUMRECIBO=".$recibo;
  	$queryERecibo= $database->query($reciboE);
  	
  	//si la consulta no arroja un registro repetido
        if(mysqli_num_rows($queryERecibo)==0){
        	//se explora si existe algun registro de pago para la asignacion
        	$pago="SELECT * FROM cmb_pago p WHERE p.IDALUMNO=".$idalumno." AND(p.IDCATEDRATICO=".$idcatedratico." AND p.IDCURSO=".$idcurso.")";
  		$queryPagoAnterior= $database->query($pago);
  		
  		//obteniendo los datos que seran necesarios para todos los casos
  		$datos="SELECT ca.nombre AS CATEDRATICO, cu.nombre AS CURSO, a.PRECIO FROM cmb_asignacion a JOIN cmb_catedratico ca ON a.IDCATEDRATICO=ca.idcatedratico JOIN cmb_curso cu ON a.IDCURSO=cu.idcurso WHERE a.IDALUMNO=".$idalumno." AND(a.IDCATEDRATICO=".$idcatedratico." AND a.IDCURSO=".$idcurso.")";
		$queryDatos= $database->query($datos);

		while($registroDatos  = $queryDatos->fetch_array( MYSQLI_BOTH))
		{
		  	 $catedratico=$registroDatos['CATEDRATICO'];
		  	 $curso=$registroDatos['CURSO'];
		  	 $precio=$registroDatos['PRECIO'];
		}
		
		$codAsignacion= "'".$idcatedratico."-".$idcurso."'";
  	
	  	//si la consulta no arroja un registro anterior
		if(mysqli_num_rows($queryPagoAnterior)==0){
		
			$mesesOp="<option>Seleccione </option>";
			for($i=0; $i<5; $i++){
				if((((int)date('m'))+$i)<=12){
					$mesesOp=$mesesOp.'<option value="'.(((int)date('m'))+$i).'">'.$meses[(((int)date('m'))+$i)].' - '.date('Y').'</option>';
				}
			}
			
			
			
			echo '<div class="table-responsive">
			<table class="table bg-info"  id="tabla">
					<tr class="form-horizontal">
						<td>
						<div class="form-inline">
		    				   <label class="control-label col-sm-1">Maestro:</label>
						    <input readonly class="col-md-3" name="maestro[]" value="'.$catedratico.'"/>
						    <input type="hidden" name="idmaestro[]" value="'.$idcatedratico.'"/>
						    <label class="control-label col-sm-1">Curso:</label>
						    <input readonly class="col-md-3" name="curso[]" value="'.$curso.'"/>
						    <input type="hidden" name="idcurso[]" value="'.$idcurso.'"/>
						    <label class="control-label col-sm-1">Precio:</label>
						    <input readonly class="col-md-1" id="precio-'.$idcatedratico.'-'.$idcurso.'" name="precio[]" value="'.$precio.'">
						    				    
						    </div>
						</td>
						
				           </tr>
				           <tr class="form-horizontal">
						<td>					    
						    <div class="form-inline">
						    <label class="control-label col-sm-1" for="mesInicial">Desde:</label> 
						    <input class="col-md-2" id="mesInicial" value="'.$meses[((int)date('m'))].' - '.date('Y').'">	
						    <input type="hidden" name="mesInicial[]" id="mesInicial-'.$idcatedratico.'-'.$idcurso.'" value="'.((int)date('m')).'">
						    <input type="hidden" name="anio[]" value="'.((int)date('Y')).'">
						    <label class="control-label col-sm-2" for="mesFinal[]">Hasta:</label>
						    <select class="col-md-2" name="mesFinal[]" id="mesFinal-'.$idcatedratico.'-'.$idcurso.'" onclick="calcular('.$codAsignacion.');" required>
						    '.$mesesOp.'						    	
						    </select>
						    <label class="control-label col-sm-2">Sub-Total:</label>
						    <input readonly name="subtotal[]" class="col-md-1 bg-primary" value="" id="sub-'.$idcatedratico.'-'.$idcurso.'"/>
						    </div>
						</td>						
					</tr>
				</table>
				</div>';
				
		}else{//si existen registros anteriores
			$ultPago="SELECT p.ULTIMOMES, p.ANIO FROM cmb_pago p JOIN cmb_recibo r ON p.NUMRECIBO=r.NUMRECIBO WHERE p.IDALUMNO=".$idalumno." AND(p.IDCATEDRATICO=".$idcatedratico." AND p.IDCURSO=".$idcurso.") ORDER BY r.FECHA DESC, p.ANIO DESC, p.ULTIMOMES DESC LIMIT 1";
			$queryUltPago= $database->query($ultPago);

			while($registroUltPago  = $queryUltPago->fetch_array( MYSQLI_BOTH))
			{
			  	 $ultimoMes=$registroUltPago['ULTIMOMES'];
			  	 $anio=$registroUltPago['ANIO'];
			}				
			
			if($ultimoMes==12){//cuando el ultimo pago fue del ultimo mes del anio anterior, se cambia el
				$mes = 1;
				$anio = $anio+1;
			}else{
				$mes = $ultimoMes+1;
			}
			
			
			$mesesOp="<option>Seleccione </option>";
			for($i=0; $i<5; $i++){
				if(($mes+$i)<=12){
					$mesesOp=$mesesOp.'<option value="'.($mes+$i).'">'.$meses[$mes+$i].' - '.$anio.'</option>';
				}
			}
			
			echo '<div class="table-responsive">
			<table class="table bg-info"  id="tabla">
					<tr class="form-horizontal">
						<td>
						<div class="form-inline">
		    				   <label class="control-label col-sm-1">Maestro:</label>
						    <input readonly class="col-md-3" name="maestro[]" value="'.$catedratico.'"/>
						    <input type="hidden" name="idmaestro[]" value="'.$idcatedratico.'"/>
						    <label class="control-label col-sm-1">Curso:</label>
						    <input readonly class="col-md-3" name="curso[]" value="'.$curso.'"/>
						    <input type="hidden" name="idcurso[]" value="'.$idcurso.'"/>
						    <label class="control-label col-sm-1">Precio:</label>
						    <input readonly class="col-md-1" id="precio-'.$idcatedratico.'-'.$idcurso.'" name="precio[]" value="'.$precio.'">
						    				    
						    </div>
						</td>
						
				           </tr>
				           <tr class="form-horizontal">
						<td>					    
						    <div class="form-inline">
						    <label class="control-label col-sm-1" for="mesInicial">Desde:</label> 	
						    <input class="col-md-2" id="mesInicial" value="'.$meses[$mes].' - '.$anio.'">
						    <input type="hidden" name="mesInicial[]" id="mesInicial-'.$idcatedratico.'-'.$idcurso.'" value="'.$mes.'">
						    <input type="hidden" name="anio[]" value="'.$anio.'">
						    <label class="control-label col-sm-2" for="mesFinal[]">Hasta:</label>
						    <select class="col-md-2" name="mesFinal[]" id="mesFinal-'.$idcatedratico.'-'.$idcurso.'" onclick="calcular('.$codAsignacion.');" required>
						    '.$mesesOp.'						    	
						    </select>
						    <label class="control-label col-sm-2">Sub-Total:</label>
						    <input readonly name="subtotal[]" class="col-md-1 bg-primary" value="" id="sub-'.$idcatedratico.'-'.$idcurso.'"/>
						    </div>
						</td>						
					</tr>
				</table>
				</div>';
				
		}
        
        }else{
        	echo '<script>alert("El número de recibo que intenta usar ya existe.");</script>';
        }
?>
