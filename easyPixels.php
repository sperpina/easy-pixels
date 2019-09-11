<?php
/*
Plugin Name: Easy Pixels by JEVNET
Plugin URI: https://es.wordpress.org/plugins/easy-pixels-by-jevnet/
Description: Put Analytics, Adwords, Facebook, Yandex, Bing, LinkedIn, Twitter, tracking codes
Version: 1.7.1
Author: JEVNET
Author URI: https://www.jevnet.es
License: GPLv2 or later
Text Domain: easy-pixels-by-jevnet
Domain Path:       /lang

Tracking: 
Analytics: Pageview
Faceook: Pageview

*/

if ( !function_exists( 'add_action' ) ) {
	echo '¿Qué quieres hacer?';
	exit;
}

/* Translations */
add_action('plugins_loaded', 'jn_ep_load_textdomain');
function jn_ep_load_textdomain() {
	load_plugin_textdomain( 'easy-pixels-by-jevnet', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

define('JN_EasyPixels_PATH', dirname(__FILE__));
define('JN_EasyPixels_URL', plugins_url('', __FILE__));

include(JN_EasyPixels_PATH."/classes/easy-analytics.php");
include(JN_EasyPixels_PATH."/classes/easy-facebook.php");
include(JN_EasyPixels_PATH."/classes/easy-gads.php");
include(JN_EasyPixels_PATH."/classes/easy-bing.php");
include(JN_EasyPixels_PATH."/classes/easy-yandex.php");
include(JN_EasyPixels_PATH."/classes/easy-tw.php");
include(JN_EasyPixels_PATH."/classes/easy-linkedin.php");


if(is_admin())
{
//	add_action('admin_init','createMenuOption');
  //wp_enqueue_script( 'script-name', JNGTOOLS_URL . '/js/admin.js', array(), '1.0.0', true );
//	include(JN_EasyPixels_PATH."/admin/tabs.php");
add_action('plugins_loaded', 'jn_easypixels_load_textdomain');
	add_action('easypixels_admintabs','jn_easypixels_admintabs_basic',10);
	require(JN_EasyPixels_PATH . '/admin/easyPixelsAdmin.php');
	add_action('admin_init','jn_easypixels_saveSettings');
	add_action('admin_menu','jn_easypixels_createMenuOption');

}
else
{
	add_action('wp_head','jn_easypixels_headerTracking');
	add_action('wp_footer','jn_easypixels_footerTracking');
	add_action('easyPixelsHeaderScripts','jn_easypixels_put_gtag_code');
}

function jn_easypixels_headerTracking()
{
	if ( class_exists( 'jn_easyGAds' ) ){$jnGAds=new jn_easyGAds();}
	if ( class_exists( 'jn_Facebook' ) ){$jnFB=new jn_Facebook();}
	if ( class_exists( 'jn_easyBingAds' ) ){$jnBing=new jn_easyBingAds();}
	if ( class_exists( 'jn_easypixels_Twitter' ) ){$jn_easypixels_tw=new jn_easypixels_Twitter();}
	if ( class_exists( 'jn_easypixels_Yandex' ) ){$jn_easypixels_yandex=new jn_easypixels_Yandex();}
	if ( class_exists( 'jn_easypixels_LinkedIn' ) ){$jn_easypixels_LinkedIn=new jn_easypixels_LinkedIn();}
	do_action('easyPixelsHeaderScripts');
}

function jn_easypixels_put_gtag_code()
{
	$gtagCode='';
	if ( class_exists( 'jn_Analytics' ) )
	{
		$jnEPGA=new jn_Analytics();
		if($jnEPGA->is_enabled()){$gtagCode=$jnEPGA->getCode();}
	}
	if($gtagCode=='')
	{
		if ( class_exists( 'jn_Adwords' ) )
		{
			$jnEPGADW=new jn_Adwords();
			if($jnEPGADW->is_enabled()){$gtagCode=$jnEPGADW->getCode();}
		}
	}
	if($gtagCode!='')
	{
		echo "<!-- Global site tag (gtag.js) - Google Analytics --><script async src='https://www.googletagmanager.com/gtag/js?id=".$gtagCode."'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());";
			do_action('put_jn_google_tracking');
		echo "</script>";
	}
}

function jn_easypixels_footerTracking()
{
	do_action('jn_easyPixels_footer');
}


/* Translations */
function jn_easypixels_load_textdomain() {
	load_plugin_textdomain( 'easy-pixels-by-jevnet', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}