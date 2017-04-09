  
  <?php
///////////////// CONEXION A LA BASE DE DATOS //////////////////
  $dbserver = "localhost";
  $dbuser = "admaptec_sibaseb";
  $password = "SIbase2017";
  $dbname = "admaptec_jmln2";
 
 //para mantener la correspondencia codigo mes, el bucket 0 no tiene datos
 $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
 $database = new mysqli($dbserver, $dbuser, $password, $dbname);

				
				$idalumno = ($_POST['idalumno']);
				$recibo = ($_POST['recibo']);
				$fecha = date('Y-m-d');
				$total = ($_POST['total']);
				
				$valores="(".$recibo.", ".$idalumno.", '".$fecha."', ".$total.");";
				$sql = "INSERT INTO cmb_recibo(NUMRECIBO, IDALUMNO, FECHA, TOTAL)
				VALUES".$valores;
				$sqlRes=$database->query($sql);


				$idcatedraticos = ($_POST['idmaestro']);
				$idcursos = ($_POST['idcurso']);
				$precios = ($_POST['precio']);
				$anios = ($_POST['anio']);
				$mesesIniciales = ($_POST['mesInicial']);
				$mesesFinales = ($_POST['mesFinal']);
				$subTotales = ($_POST['subtotal']);
				 
				///////////// SEPARAR VALORES DE ARRAYS, EN ESTE CASO SON 4 ARRAYS UNO POR CADA INPUT (ID, NOMBRE, CARRERA Y GRUPO////////////////////)
				while(true) {

				    //// RECUPERAR LOS VALORES DE LOS ARREGLOS ////////
				    $idcatedratico = current($idcatedraticos);
				    $idcurso = current($idcursos);
				    $precio = current($precios);
				    $anio = current($anios);
				    $mesInicial = current($mesesIniciales);
				    $mesFinal = current($mesesFinales);
				    $subTotal = current($subTotales);
				    
				    
				    ////// ASIGNARLOS A VARIABLES ///////////////////
				    $idcatedratico=(( $idcatedratico !== false) ? $idcatedratico : ", &nbsp;");
				    $idcurso=(( $idcurso !== false) ? $idcurso : ", &nbsp;");
				    $precio=(( $precio !== false) ? $precio : ", &nbsp;");
				    $anio=(( $anio !== false) ? $anio : ", &nbsp;");
				    $mesInicial=(( $mesInicial !== false) ? $mesInicial : ", &nbsp;");
				    $mesFinal=(( $mesFinal !== false) ? $mesFinal : ", &nbsp;");
				    $subTotal=(( $subTotal !== false) ? $subTotal : ", &nbsp;");
				    if($mesInicial == $mesFinal){
				    	$descripcion='Pago de: '.$meses[$mesInicial].'/'.$anio;
				    }else{
				    	$descripcion='Pago de: '.$meses[$mesInicial].'/'.$anio.'-'.$meses[$mesFinal].'/'.$anio;
				    }
				    //// CONCATENAR LOS VALORES EN ORDEN PARA SU FUTURA INSERCIÓN ////////
				    $valores="(".$idcurso.", ".$idcatedratico.", ".$idalumno.", ".$recibo.", ".$precio.", ".$subTotal.", '".$descripcion."', ".$mesFinal.", ".$anio."),";

				    //////// YA QUE TERMINA CON COMA CADA FILA, SE RESTA CON LA FUNCIÓN SUBSTR EN LA ULTIMA FILA /////////////////////
				    $valoresQ= substr($valores, 0, -1);
				    
				    ///////// QUERY DE INSERCIÓN ////////////////////////////
				    $sql = "INSERT INTO cmb_pago (IDCURSO, IDCATEDRATICO, IDALUMNO, NUMRECIBO, PRECIO, SUBTOTAL, DESCRIPCION, ULTIMOMES, ANIO) 
					VALUES $valoresQ";

					
					$sqlRes=$database->query($sql);

				    
				    // Up! Next Value
				    $idcatedratico = next($idcatedraticos);
				    $idcurso = next($idcursos);
				    $precio = next($precios);
				    $anio = next($anios);
				    $mesInicial = next($mesesIniciales);
				    $mesFinal = next($mesesFinales);
				    $subTotal = next($subTotales);
				    
				    // Check terminator
				    if($idcatedratico === false && $idcurso === false && $precio === false && $anio === false && $mesInicial === false && $mesFinal === false && $subTotal === false) break;
    
				}
					
					
			mysqli_close($database);	

			?>
