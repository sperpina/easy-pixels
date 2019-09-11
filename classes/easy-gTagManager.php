<?php
class jn_easyGTagManager
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled);}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jnep_GoogleEPconfig','');
		if($settings)
		{
			$this->enabled=isset($settings["googleGTMenabled"])?$settings["googleGTMenabled"]:false;
			$this->code=isset($settings["googleGTMcode"])?$settings["googleGTMcode"]:'';
		}
		$GTMObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('easyPixelsHeaderScripts',function() use ($GTMObject){$GTMObject->putTrackingCode();},1);
		}
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
               <th>'.__('Enable Google Tag Manager','easy-pixels-by-jevnet').'</th>
               <td style="width:3em"><input type="checkbox" id="jn_EPGTM_enable" name="jn_EPGTM_enable"'.$this->checkboxSetting($this->enabled).'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPGTM_code" name="jn_EPGTM_code" placeholder="GTM-XXXXXXX"></td>
          </tr>
     </table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo "<!-- Easy Pixels: Google Tag Manager --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','".$this->code."');</script><!-- Easy Pixels: End Google Tag Manager -->";
		}else{return false;}
	}
	
	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPGTM_code"]))
		{
			$settings=get_option('jnep_GoogleEPconfig');
			$code=$_POST["jn_EPGTM_code"];
			if(is_numeric($code)){$code='GTM-'.$code;}
			$settings["googleGTMenabled"]=(isset($_POST["jn_EPGTM_enable"]));
			$settings["googleGTMcode"]=sanitize_text_field($_POST["jn_EPGTM_code"]);

			update_option('jnep_GoogleEPconfig', $settings);
		}
	}
}