<?php
class jn_easypixels_LinkedIn
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPLinkedIn','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
		$GEpLinkedInObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('easyPixelsHeaderScripts',function() use ($GEpLinkedInObject){$GEpLinkedInObject->putTrackingCode();});
		}
	}

	private function checkboxSetting()
	{
		return ($this->enabled)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '<table class="form-table"><tr>
				<th><img src="'.JN_EasyPixels_URL.'/img/linkedin.png" alt="linkedin" width="20px" style="float:left"><span style="margin-left:.5em"> LinkedIn</th><td style="width:2em"><input type="checkbox" id="jn_EPLinkedIn_Track_enable" name="jn_EPLinkedIn_Track_enable" '.$this->checkboxSetting().'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPLinkedIn_Track_code" name="jn_EPLinkedIn_Track_code"></td>
			</tr></table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo '<!-- Easy Pixels LinkedIn tracking code --><script type="text/javascript">_linkedin_partner_id = "'.$this->getCode().'";window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];window._linkedin_data_partner_ids.push(_linkedin_partner_id);</script><script type="text/javascript">(function(){var s = document.getElementsByTagName("script")[0];var b = document.createElement("script");b.type = "text/javascript";b.async = true;b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(b, s);})();</script><noscript><img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid='.$this->getCode().'&fmt=gif" /></noscript><!-- End LinkedIn tracking code -->';
		}else{return false;}
	}

	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPLinkedIn_Track_code"]))
		{
			$settings=get_option('jn_EPLinkedIn');
			$settings["code"]=$_POST["jn_EPLinkedIn_Track_code"];
			$settings["enabled"]=(isset($_POST["jn_EPLinkedIn_Track_enable"]));
			update_option('jn_EPLinkedIn', $settings);
		}
	}
}
