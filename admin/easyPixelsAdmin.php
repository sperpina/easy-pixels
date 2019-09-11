<?php

function jn_easypixels_createMenuOption()
{
	add_menu_page('Easy Pixel Settings','Easy Pixels','administrator','easypixels','jn_easypixels_initTrackingOptions',JN_EasyPixels_URL.'/img/icon20x20.png');
}

function jn_easypixels_admintabs_basic()
{
     ?>
     <a href="<?php echo admin_url( 'admin.php?page=easypixels' ); ?>" class="nav-tab<?php if ('easypixels' == $_GET['page'] ) echo ' nav-tab-active'; ?>"><?php echo __( 'Basic tracking','easy-pixels-by-jevnet'); ?></a>
 <?php
}

function jn_easypixels_initTrackingOptions()
{
	if ( class_exists( 'jn_Analytics' ) ){$jnEPGA=new jn_Analytics();}
	if ( class_exists( 'jn_Facebook' ) ){$jnEPFB=new jn_Facebook();}
	if ( class_exists( 'jn_easyGAds' ) ){$jnEPGAds=new jn_easyGAds();}
	if ( class_exists( 'jn_easyBingAds' ) ){$jnEPBingAds=new jn_easyBingAds();}
	if ( class_exists( 'jn_easyGTagManager' ) ){$jnEP_GTM=new jn_easyGTagManager();}
	if ( class_exists( 'jn_easypixels_Twitter' ) ){$jn_easypixels_tw=new jn_easypixels_Twitter();}
	if ( class_exists( 'jn_easypixels_Yandex' ) ){$jn_easypixels_yandex=new jn_easypixels_Yandex();}
	if ( class_exists( 'jn_easypixels_LinkedIn' ) ){$jn_easypixels_LinkedIn=new jn_easypixels_LinkedIn();}

	require(JN_EasyPixels_PATH . '/admin/page-easyPixelsAdmin.php');
}

function jn_easypixels_saveSettings()
{

	if ( false == get_option( 'jnEasyPixelsSettings-group' ) ) {add_option( 'jnEasyPixelsSettings-group' );}
	if ( class_exists( 'jn_Analytics' ) ){jn_Analytics::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easyGAds' ) ){jn_easyGAds::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easyGTagManager' ) ){jn_easyGTagManager::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_Facebook' ) ){jn_Facebook::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easyBingAds' ) ){jn_easyBingAds::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easypixels_Twitter' ) ){jn_easypixels_Twitter::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easypixels_Yandex' ) ){jn_easypixels_Yandex::save('jnEasyPixelsSettings-group');}
	if ( class_exists( 'jn_easypixels_LinkedIn' ) ){jn_easypixels_LinkedIn::save('jnEasyPixelsSettings-group');}
}

/* Sanitize Callback Function */
function my_settings_sanitize( $input ){return isset( $input ) ? true : false;}