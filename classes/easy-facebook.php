<?php
class jn_Facebook
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{

		$socialSettings=get_option('jn_EP_Social');
		if($socialSettings)
		{
			$this->code=$socialSettings["fb_code"];
			$this->enabled=$socialSettings["fb_enabled"];
		}
		$facebookObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('easyPixelsHeaderScripts',function() use ($facebookObject){$facebookObject->putTrackingCode();});
		}
//		add_action('easyPixelsFacebookAdminSection',function() use ($facebookObject){$facebookObject->putAdminOptions();});
	}

	private function checkboxSetting()
	{
		return ($this->enabled)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '
          <table class="form-table">
          <tr>
				<th>Facebook</th><td style="width:2em"><input type="checkbox" id="jn_EPFBtrack_enable" name="jn_EPFBtrack_enable" '.$this->checkboxSetting().'></td>
				<td><input value="'.$this->code.'" type="text" id="jn_EPFBtrack_code" name="jn_EPFBtrack_code"></td>
          </tr>
          </table>';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo '<script type="text/javascript">!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,\'script\',\'https://connect.facebook.net/en_US/fbevents.js\'); fbq(\'init\', \''.$this->code.'\');fbq(\'track\', \'PageView\');</script><noscript><img height="1" width="1" src="https://www.facebook.com/tr?id='.$this->code.'&ev=PageView&noscript=1"/></noscript>';
		}else{return false;}
	}

	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPFBtrack_code"]))
		{
			$socialSettings=get_option('jn_EP_Social');

			$socialSettings["fb_enabled"]=(isset($_POST["jn_EPFBtrack_enable"]));
			$socialSettings["fb_code"]=$_POST["jn_EPFBtrack_code"];
			update_option('jn_EP_Social', $socialSettings);
		}
	}
}