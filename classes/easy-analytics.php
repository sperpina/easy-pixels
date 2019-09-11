<?php
class jn_Analytics
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$this->enabled=get_option('jn_EPGA_enable','');
		$this->code=get_option('jn_EPGA_code',false);
		$analyticsObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('put_jn_google_tracking',function() use ($analyticsObject){$analyticsObject->putTrackingCode();});
		}
//		add_action('easyPixelsAnalyticsAdminSection',function() use ($analyticsObject){$analyticsObject->putAdminOptions();});
	}

	private function checkboxSetting()
	{
		return ($this->enabled=='on')?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '
          <table class="form-table">
          <tr>
               <th>'.__('Enable Analytics','easy-pixels-by-jevnet').'</th>
               <td style="width:3em"><input type="checkbox" id="jn_EPGA_enable" name="jn_EPGA_enable"'.$this->checkboxSetting().'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPGA_code" name="jn_EPGA_code" placeholder="UA-XXXXXXXX-X"></td>
          </tr>
     </table>';
	}

	public function putTrackingCode()
	{
		echo "gtag('config', '".$this->code."');";
	}

	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		register_setting($WP_settings_group,'jn_EPGA_enable');
		if(isset($_POST["jn_EPGA_code"])){register_setting($WP_settings_group,'jn_EPGA_code');}
	}
}