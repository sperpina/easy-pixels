<?php
class jn_easypixels_Yandex
{
	private $code='';
	private $enabled=false;

	function is_enabled(){return ($this->enabled=='on');}
	function getCode(){return $this->code;}

	public function __construct()
	{
		$settings=get_option('jn_EPYandex','');
		if($settings)
		{
			$this->code=$settings["code"];
			$this->enabled=$settings["enabled"];
		}
		$GEpYxObject=$this;
		if(($this->is_enabled())&&($this->code!=''))
		{
			add_action('easyPixelsHeaderScripts',function() use ($GEpYxObject){$GEpYxObject->putTrackingCode();});
			add_action('easyPixelsAsyncHeaderScripts',function() use ($GEpYxObject){$GEpYxObject->putAsyncTrackingCode();});
		}
	}

	private function checkboxSetting()
	{
		return ($this->enabled)?' checked="checked"':'';
	}

	public function putAdminOptions()
	{
		echo '<table class="form-table"><tr>
		<th>'.__('Enable Yandex tracking','easy-pixels-by-jevnet').'</th>
		<td style="width:3em"><input type="checkbox" id="jn_EPYandex_enable" name="jn_EPYandex_enable" '.$this->checkboxSetting().'></td>
		<td><input value="'.$this->code.'" type="text" id="jn_EPYandex_code" name="jn_EPYandex_code" placeholder="012345678"></td>
		</tr></table>';
	}


	private function theJSTrackingCode()
	{
		return '(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");ym('.$this->getCode().', "init", {clickmap:true,trackLinks:true,accurateTrackBounce:true,webvisor:true});';
	}

	public function putTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{
			echo '<!-- Easy Pixels Yandex.Metrika counter --><script type="text/javascript" >'.$this->theJSTrackingCode().'</script>';
			echo '<noscript><div><img src="https://mc.yandex.ru/watch/'.$this->getCode().'" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->';
		}else{return false;}
	}

	public function putAsyncTrackingCode()
	{
		if(($this->enabled)&&($this->code!=''))
		{

			echo '<!-- Easy Pixels Yandex.Metrika counter --> <script> document.addEventListener(\'easypixels_LoadHeaderCodes\',function(){
				'.$this->theJSTrackingCode().'console.log(\'async: Yandex\');}); </script> <!-- /Easy Pixels Yandex.Metrika counter -->';

		}else{return false;}
	}

	static public function save($WP_settings_group='jnEasyPixelsSettings-group')
	{
		if(isset($_POST["jn_EPYandex_code"]))
		{
			$settings=get_option('jn_EPYandex');
			$settings["code"]=$_POST["jn_EPYandex_code"];
			$settings["enabled"]=(isset($_POST["jn_EPYandex_enable"]));
			update_option('jn_EPYandex', $settings);
		}
	}
}