<?php
class RobcoreNetAtmo {
	function rnpfw_tempExtNow($id_modulo, $id_stazione, $oggetto_meteo) {
	/*********************************************************
	MODULO ESTERNO
	*********************************************************/
	    	/* ---------------- TEMPERATURA ---------------- */
        	global $wpdb;
        	$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
        	$timestamp_ultima_lettura = $wpdb->get_row("SELECT time_misura FROM $tabella_prefixata WHERE id_modulo = '$id_modulo' AND valore_misurato = 'temp_ext_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
        	$timestamp_ultima_lettura = $timestamp_ultima_lettura[0];
        	$timestamp_ora = date("Y-m-d H:i:s");
        	// echo "ora: " . $timestamp_ora . "<br>";
        	// echo "misurata ultima: " . $timestamp_ultima_lettura . "<br>";
        	
        	if ((strtotime($timestamp_ora)) - (strtotime($timestamp_ultima_lettura)) > 300) {

			            $params_modulo = array("scale" =>"max",
			                            "type"=>"Temperature",
			                            "date_end"=>"last",
			                            "device_id"=>$id_stazione,
			                            "module_id"=>$id_modulo,
			                            "limit"=>1);
			            $dati_modulo = $oggetto_meteo->api("getmeasure", $params_modulo);
			            if (isset($dati_modulo)) {
			            	$temp_est_ora = $dati_modulo[0]["value"][0][0];
											$result = $wpdb->insert( 
												$wpdb->prefix . "robcore_netatmo_data", 
												array( 
													'misurazione' => $temp_est_ora,
													'time_misura' => $timestamp_ora,
													'id_device' => $id_stazione,
													'id_modulo' => $id_modulo,
													'valore_misurato' => "temp_ext_now"
												)
											 );
			            	return $temp_est_ora;
			            }
			} else {
				global $wpdb;
				$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
				$temp_est_ora = $wpdb->get_row("SELECT misurazione FROM $tabella_prefixata WHERE id_modulo = '$id_modulo' AND valore_misurato = 'temp_ext_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
				$temp_est_ora = $temp_est_ora[0];
				return $temp_est_ora;
			}
	}
	function rnpfw_humExtNow($id_modulo, $id_stazione, $oggetto_meteo) {

			/* ---------------- UMIDITA ---------------- */
        	global $wpdb;
        	$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
        	$timestamp_ultima_lettura = $wpdb->get_row("SELECT time_misura FROM $tabella_prefixata WHERE id_modulo = '$id_modulo' AND valore_misurato = 'hum_ext_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
        	$timestamp_ultima_lettura = $timestamp_ultima_lettura[0];
        	$timestamp_ora = date("Y-m-d H:i:s");
        	// echo "ora: " . $timestamp_ora . "<br>";
        	// echo "misurata ultima: " . $timestamp_ultima_lettura . "<br>";
        	
        	if ((strtotime($timestamp_ora)) - (strtotime($timestamp_ultima_lettura)) > 300) {
			            $params_modulo = array("scale" =>"max",
			                            "type"=>"Humidity",
			                            "date_end"=>"last",
			                            "device_id"=>$id_stazione,
			                            "module_id"=>$id_modulo,
			                            "limit"=>1);
			            $dati_modulo = $oggetto_meteo->api("getmeasure", $params_modulo);
			            if (isset($dati_modulo)) {
			            	$umidita_est_ora = $dati_modulo[0]["value"][0][0];
								$result = $wpdb->insert( 
									$wpdb->prefix . "robcore_netatmo_data", 
									array( 
										'misurazione' => $umidita_est_ora,
										'time_misura' => $timestamp_ora,
										'id_device' => $id_stazione,
										'id_modulo' => $id_modulo,
										'valore_misurato' => "hum_ext_now"
									)
								 );
			            	return $umidita_est_ora;
			            }
			    } else {
				global $wpdb;
				$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
				$umidita_est_ora = $wpdb->get_row("SELECT misurazione FROM $tabella_prefixata WHERE id_modulo = '$id_modulo' AND valore_misurato = 'hum_ext_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
				$umidita_est_ora = $umidita_est_ora[0];
				return $umidita_est_ora;
			}
	}
	/*********************************************************
	MODULO INTERNO
	*********************************************************/
	        /* ---------------- PRESSIONE ---------------- */

	        function rnpfw_pressNow($id_stazione, $oggetto_meteo) {
        	global $wpdb;
        	$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
        	$timestamp_ultima_lettura = $wpdb->get_row("SELECT time_misura FROM $tabella_prefixata WHERE id_device = '$id_stazione' AND valore_misurato = 'press_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
        	$timestamp_ultima_lettura = $timestamp_ultima_lettura[0];
        	$timestamp_ora = date("Y-m-d H:i:s");
        	// echo "ora: " . $timestamp_ora . "<br>";
        	// echo "misurata ultima: " . $timestamp_ultima_lettura . "<br>";
        	
        	if ((strtotime($timestamp_ora)) - (strtotime($timestamp_ultima_lettura)) > 300) {
			            $params = array("scale" =>"max",
			                            "type"=>"Pressure",
			                            "date_end"=>"last",
			                            "device_id"=>$id_stazione,
			                            "limit"=>1);
			            $dati_stazione = $oggetto_meteo->api("getmeasure", $params);
			            if(isset($dati_stazione[0]) && isset($dati_stazione[0]["beg_time"]))
			            {
			                $pressione_ora = $dati_stazione[0]["value"][0][0];
	    				    global $wpdb;
							$result = $wpdb->insert( 
								$wpdb->prefix . "robcore_netatmo_data", 
								array( 
									'misurazione' => $pressione_ora,
									'time_misura' => $timestamp_ora,
									'id_device' => $id_stazione,
									'valore_misurato' => "press_now"
								)
							 );
			                return $pressione_ora;
			            } 
			        } else {
				global $wpdb;
				$tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
				$pressione_ora = $wpdb->get_row("SELECT misurazione FROM $tabella_prefixata WHERE id_device = '$id_stazione' AND valore_misurato = 'press_now' ORDER BY time_misura DESC LIMIT 1", ARRAY_N);
				$pressione_ora = $pressione_ora[0];
				return $pressione_ora;
			}
	    } //chiudo funzione di richiesta dati meteo

	function rnpfw_ottieniIDElementi($tipo_elemento, $oggetto_meteo) {
		$listaDevice = $oggetto_meteo->api("devicelist");
		$array_moduli_stazioni = array();

		if ($tipo_elemento == "modulo") {
			foreach ($listaDevice['modules'] as $modulo) {
				$id_modulo = $modulo["_id"];
				$nome_modulo = $modulo["module_name"];
				$id_stazione = $modulo["main_device"];
				$array_id_modulo_stazione = array($id_modulo,$nome_modulo,$id_stazione);
				array_push($array_moduli_stazioni, $array_id_modulo_stazione);
			}
			return($array_moduli_stazioni);
		}
		$array_stazioni = array();
		if ($tipo_elemento == "stazione") {
			foreach ($listaDevice['devices'] as $device) {
				$id_stazione = $device["_id"];
				$nome_stazione = $device["station_name"];
				$array_id_nome_stazioni = array($id_stazione,$nome_stazione);
				array_push($array_stazioni, $array_id_nome_stazioni);
			}
			return $array_stazioni;
		}
		//print_r($listaDevice['modules']);
	}
	
	function rnpfw_ottieniFdaC($temperaturaC) {
		$temperaturaF = ($temperaturaC * 1.8) + 32;
		return $temperaturaF;
	}

	function rnpfw_ottieni_mmHg_da_hPa($press_in_hPa) {
		$press_in_mmHg = $press_in_hPa * 0.75006375541921;
		return $press_in_mmHg;
	}
} // chiudo classe
?>