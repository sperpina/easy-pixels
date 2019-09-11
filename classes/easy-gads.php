<?php
class jn_easyGAds
{
	private $code='';
	private $enabled=false;
	private $phoneNumber='';
	private $GFC_conversionLabel='';
	private $remarketing=true;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPADW','');
		if($settings)
		{
			$this->code=isset($settings["code"])?$settings["code"]:'';
			$this->enabled=isset($settings["enabled"])?$settings["enabled"]:false;
			$this->phoneNumber=isset($settings["phoneNumber"])?$settings["phoneNumber"]:'';
			$this->GFC_conversionLabel=isset($settings["GFC_conversionLabel"])?$settings["GFC_conversionLabel"]:'';
			$this->remarketing=isset($settings["remarketing"])?$settings["remarketing"]:true;
		}
		$GAdsObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('put_jn_google_tracking',function() use ($GAdsObject){$GAdsObject->putTrackingCode();$GAdsObject->putForwardingCall();});
		}
//		add_action('easyPixelsGAdsAdminSection',function() use ($GAdsObject){$GAdsObject->putAdminOptions();});
	}

	private function checkboxSetting($setting=false)
	{
		return ($setting)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '
		<table class="form-table">
			<tr>
				<th>'.__('Enable Google Ads tracking','easy-pixels-by-jevnet').'</th>
				<td style="width:3em"><input type="checkbox" onclick="jn_EPADW_ADWoptionsToggle();" id="jn_EPGADW_CF7_enable" name="jn_EPGADW_CF7_enable" '.$this->checkboxSetting($this->enabled).'></td>
				<td></td>
			</tr>
		</table>';
		echo '
		<div id="ADWoptionsDropdown" style="margin-left:1em;background:#fefefe;padding:1em;box-shadow:0 0 2px #666">
		<h3>Google Ads Options</h3>
		<table class="form-table">
			<th>'.__('Google Ads tracking ID','easy-pixels-by-jevnet').'</th>
			<td><input value="'.$this->code.'" type="text" id="jn_EPGADW_cid" name="jn_EPGADW_cid" placeholder="AW-012345678"></td>
		</table>
		<table class="form-table">
			<th>'.__('Enable Remarketing','easy-pixels-by-jevnet').'</th>
			<td><input type="checkbox" id="jn_EPGADW_remarketing" name="jn_EPGADW_remarketing" '.$this->checkboxSetting($this->remarketing).'></td>
		</table>
		<h4>'.__('Google Forwarding Call','easy-pixels-by-jevnet').'</h4>
		<table class="form-table">
			<tr>
				<th>'.__('Phone Number','easy-pixels-by-jevnet').'</th>
				<td><input value="'.$this->phoneNumber.'" type="text" id="jn_EPGADW_phoneNumber" name="jn_EPGADW_phoneNumber" placeholder="555 555 555"></td>
				<th>'.__('Conversion label','easy-pixels-by-jevnet').'</th>
				<td><input value="'.$this->GFC_conversionLabel.'" type="text" id="jn_EPGADW_GFC_conversionLabel" name="jn_EPGADW_GFC_conversionLabel" placeholder="XXXXXXXXXXXX"></td>
			</tr>
		</table></div>';
		echo '<script>function jn_EPADW_ADWoptionsToggle(){document.getElementById("ADWoptionsDropdown").style.display=(document.getElementById("jn_EPGADW_CF7_enable").checked)?"block":"none"}jn_EPADW_ADWoptionsToggle();</script>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			if(!$this->remarketing)
			{
				echo "gtag('set', 'allow_ad_personalization_signals', false);";
			}
			echo "gtag('config', '".$this->code."');";

		}else{return false;}
	}

	public function putForwardingCall()
	{
		if(($this->enabled)&&($this->code!='')&&($this->GFC_conversionLabel!='')&&($this->phoneNumber!=''))
		{
			echo "gtag('config', '".$this->code."/".$this->GFC_conversionLabel."', {'phone_conversion_number': '".$this->phoneNumber."'});";
		}else{return false;}
	}
	
	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPGADW_cid"]))
		{
			$settings=get_option('jn_EPADW');
			$code=$_POST["jn_EPGADW_cid"];
			if(is_numeric($code)){$code='AW-'.$code;}
			$settings["code"]=sanitize_text_field($_POST["jn_EPGADW_cid"]);
			$settings["enabled"]=(isset($_POST["jn_EPGADW_CF7_enable"]));
			$settings["remarketing"]=(isset($_POST["jn_EPGADW_remarketing"]));
			$settings["phoneNumber"]=sanitize_text_field($_POST["jn_EPGADW_phoneNumber"]);

			$theLabel=sanitize_text_field($_POST["jn_EPGADW_GFC_conversionLabel"]);
			if((strpos($theLabel,'/')>0)&&(strpos(strtoupper($theLabel),'AW-')==0)){$theLabel=substr($theLabel, strpos($theLabel,'/')+1);}
			$settings["GFC_conversionLabel"]= preg_replace('/[^\w]/', '', $theLabel);

			update_option('jn_EPADW', $settings);
		}
	}
}