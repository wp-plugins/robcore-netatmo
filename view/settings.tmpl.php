<style>
td.td-pagina-settings {
	vertical-align: top;
	padding: 20px;
}
</style>
<table id="tabella-pag-settings">
	<tr>
		<td class="td-pagina-settings">
		<h2>Account (current user: <span style="color:#0073aa"><?php echo get_option('robcore_nap_account')[0]; ?></span>)</h2>
			<?php include('form_account_request.php'); ?>
		</td>
		<?php if (isset($oggettoMeteo) && get_option('robcore_nap_account')[0] != '')  { ?>
		<td class="td-pagina-settings">
		<h2>External modules found:</h2>
			<ul class="elenco-moduli">
			<?php foreach ($oggettoMeteo->rnpfw_ottieniIDElementi("modulo", $client) as $accoppiata_modulo_staz) {
				echo "<li>";
				echo "ID: <span style='color: #0073aa'>$accoppiata_modulo_staz[0]</span> - Name: <span style='color: #0073aa'>$accoppiata_modulo_staz[1]</span> - Associated station: <span style='color: #0073aa'>$accoppiata_modulo_staz[2]</span>";
				echo "<br>";
				echo "Module Temperature: ";
				if (get_option('robcore_nap_options_press_temp')[0] == "fahrenheit") {
					echo "<span style='color: #0073aa'>" . $oggettoMeteo->rnpfw_ottieniFdaC($oggettoMeteo->rnpfw_tempExtNow($accoppiata_modulo_staz[0], $accoppiata_modulo_staz[2], $client)) . " °F</span>";
				} else {
					echo "<span style='color: #0073aa'>" . $oggettoMeteo->rnpfw_tempExtNow($accoppiata_modulo_staz[0], $accoppiata_modulo_staz[2], $client) . " °C</span>";
				}				
				echo " - ";
				echo "Module Humidity: ";
				echo "<span style='color: #0073aa'>" . $oggettoMeteo->rnpfw_humExtNow($accoppiata_modulo_staz[0], $accoppiata_modulo_staz[2], $client) . " %</span>";
				
				echo "<h4>Shortcodes available for this module:</h4>";
				echo "<pre>";
				echo "Temperature:<br>";
				echo "<strong>[robcore-netatmo data = temp-ext-now module_id ='$accoppiata_modulo_staz[0]' station_id = '$accoppiata_modulo_staz[2]']</strong><br>";
				
				echo "<br>";
				
				echo "Humidity:<br>";
				echo "<strong>[robcore-netatmo data = hum-ext-now module_id ='$accoppiata_modulo_staz[0]' station_id = '$accoppiata_modulo_staz[2]']</strong><br>";
				echo "</pre>";

				echo "</li><hr>";
			}
			?>
			</ul>

		<h2>Weather stations found:</h2>
			<ul class="elenco-stazioni"> 
			<?php foreach ($oggettoMeteo->rnpfw_ottieniIDElementi("stazione", $client) as $accoppiata_stazione_nome) {
				echo "<li>";
				echo "ID: <span style='color: #0073aa'>$accoppiata_stazione_nome[0]</span> - Name: <span style='color: #0073aa'>$accoppiata_stazione_nome[1]</span>";
				echo "<br>";
				echo "Pressure: ";

				if (get_option('robcore_nap_options_press_temp')[1] == "mmHg") {
					echo "<span style='color: #0073aa'>" . round($oggettoMeteo->rnpfw_ottieni_mmHg_da_hPa($oggettoMeteo->rnpfw_pressNow($accoppiata_stazione_nome[0],$client)),2) . " mmHg</span>";
				} else {
					echo "<span style='color: #0073aa'>" . $oggettoMeteo->rnpfw_pressNow($accoppiata_stazione_nome[0],$client) . " hPa</span>";
				}
				
				echo "<h4>Shortcodes available for this station:</h4>";
				echo "<pre>";

				echo "Pressure:<br>";
				echo "<strong>[robcore-netatmo data = press-now station_id = '$accoppiata_stazione_nome[0]']</strong><br>";

				echo "</pre>";

				echo "</li>";
			} ?>
			</ul>
		</td>
		<?php 
		} else {
			echo "You must login with your Netatmo account to retrieve weather data.";
		}
		?>
	</tr>
</table>
<hr>
<p>If you like this plugin and want to contribute, I'll be glad if you would make a donation.</p><br>
<div>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="8WTUYMM3T4K3E">
<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
</form>
</div>