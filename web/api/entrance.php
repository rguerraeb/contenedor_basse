<?php

    // DESARROLLO
	$conn = mysql_connect('127.0.0.1','lcempro_usr','loyalty_cempro_pwd');
	mysql_select_db('loyalty_cempro');
	
	// PRODUCCION
	//$conn = mysql_connect('127.0.0.1','masesa_pusr','m@Mass_ltyD163uVSR');
	//mysql_select_db('masesa_prod');

	/*	 
	 * Identifica y aplica el criterio de entrega de puntos
	 * Según group_filter_id y job_position_id
	 * 
	 */
	function get_points($sku_info,$staff_info)
	{
				
		$jobPositionId = $staff_info['job_position_id'];
		$skuId = $sku_info['id'];
		
		$reward = "SELECT * FROM reward_criteria WHERE job_position_id = '".$jobPositionId."'";
		$result_reward = mysql_query($reward,$conn);
		$result_num = mysql_num_rows($result_reward);
		
		if($result_num > 0)
		{			
			$reward_criteria = mysql_fetch_array($result_reward);
			$operation       = $reward_criteria['mathematical_operation'];
			
			$points = eval($sky_info['reward_point'].$operation);	
			return $points;
		} else {
			echo "No existen reglas de premio para esta posición de trabajo: $jobPositionId \n";
			exit;
		}
	}
	
	if(isset($_GET['phone']) && isset($_GET['sku_code']))
	{
		
		$phone = $_GET['phone'];
		$numero_motor = trim($_GET['sku_code']);
		
		//Primero identificamos si el telefono esta registrado. Si no esta registrado no puede participar
		$user = "SELECT * FROM staff WHERE phone_main = '".$phone."'";
		$result_user = mysql_query($user,$conn);
		$num_user = mysql_num_rows($result_user);
		if($num_user > 0)
		{
				
			$user_info = mysql_fetch_array($result_user);
			$staffId = $user_info['staff_id'];
			
			//Si pasa todas las validaciones, entonces guardamos el mensaje en sms_incoming a la espera de ser procesado
			//La logica es que se proceden despues de la ultima carga de ventas del dia por parte de masesa
			$insert = "INSERT INTO sms_incoming (phone,sms_string,staff_id,created_at) VALUES ('$phone','$numero_motor','$staffId',now())";
			if($insert_res = mysql_query($insert,$conn))
			{
				$response = "Se ha guardado correctamente el mensaje a la espera de ser procesado \n";
				if(isset($_GET['callback'])) {
				    echo $_GET['callback'].'('.json_encode($response).')';
			    } else {
				    echo json_encode($response);
			    }
				exit;
			} else {
				echo "No se pudo guardar el mensaje \n".mysql_error();
				exit;
			};	
			
			exit;
			
			
		
			$select = "SELECT * FROM sale WHERE sku_code = '".$sku."'";
			$result = mysql_query($select,$conn);
			$num    = mysql_num_rows($result);
			if($num > 0)
			{
				//Si existe el SKU entonces se procede a revisar si alguien mas no lo ha reportado
				$row_select = mysql_fetch_array($result);
				$saleId = $row_select['id'];
				
				$select2 = "SELECT * FROM sale_staff WHERE sale_id = '".$saleId."'";
				$result2 = mysql_query($select2, $conn);
				$num2 = mysql_num_rows($result2);
				if($num2 > 0)
				{
					//Ya existe venta reportada
					echo "Ya existe una venta reportada con este SKU\n";
					exit;
				} else {
					
					$select3 = "SELECT * FROM sale WHERE sku_code = '".$sku."'";
					$result3 = mysql_query($select3, $conn);
					$num3 = mysql_num_rows($result3);
					if($num3 > 0)
					{
						//Si existe el SKU se procede con el registro
						$sku_info = mysql_fetch_array($result3);
						$staffId  = $user_info['id'];
						$skuId    = $sku_info['id'];
						
						
						
						//$points = get_points($sku_info,$user_info);
						//echo $points;
						exit;
						if($points > 0)
						{
						
							
						
						} else {
							echo "Error en funcion get_points, la condición > 0 no se cumplio\n";
							exit;
						}		
						
					} else {
						echo "SKU no existe \n";
						exit;
					}
					
				}
				
				
			} else {
				echo "No existe venta reportada\n";
				exit;
			}
		} else {
			echo "Este numero: ".$phone." no esta registrado";
			exit;
		}
		
	}


?>
