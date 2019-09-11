<?php
class jn_easypixels_Twitter
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPTW','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
		$GEpTwObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('easyPixelsHeaderScripts',function() use ($GEpTwObject){$GEpTwObject->putTrackingCode();});
		}
	}

	private function checkboxSetting()
	{
		return ($this->enabled)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '<table class="form-table"><tr>
				<th><img src="'.JN_EasyPixels_URL.'/img/twitter.png" alt="twitter" width="20px" style="float:left"><span style="margin-left:.5em"> Twitter</th><td style="width:2em"><input type="checkbox" id="jn_EPTwTrack_enable" name="jn_EPTwTrack_enable" '.$this->checkboxSetting().'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPTwTrack_code" name="jn_EPTwTrack_code"></td>
			</tr></table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo '<!-- Easy Pixels Twitter universal website tag code --> <script> !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments); },s.version=\'1.1\',s.queue=[],u=t.createElement(n),u.async=!0,u.src=\'//static.ads-twitter.com/uwt.js\', a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,\'script\'); 
			twq(\'init\',"'.$this->getCode().'");  twq(\'track\',\'PageView\'); </script> <!-- End Twitter universal website tag code -->';
		}else{return false;}
	}

	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPTwTrack_code"]))
		{
			$settings=get_option('jn_EPTW');
			$settings["code"]=$_POST["jn_EPTwTrack_code"];
			$settings["enabled"]=(isset($_POST["jn_EPTwTrack_enable"]));
			update_option('jn_EPTW', $settings);
		}
	}
}