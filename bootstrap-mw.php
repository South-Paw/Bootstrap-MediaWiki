<?php
/**
 * Bootstrap MW: A Bootstrap theme for MediaWiki
 *
 * @file
 * @ingroup Skins
 * @author South Paw (http://southpaw.co.nz/)
 * @license MIT License, see LICENSE file
 */

if( !defined( 'MEDIAWIKI' ) ) die( "This is an extension to the MediaWiki package and cannot be run standalone." );
 
$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Bootstrap MW', // name as shown under [[Special:Version]]
	'version' => '1.0',
	'date' => '20141005',
	'url' => "https://github.com/South-Paw/Bootstrap-MW",
	'author' => 'South Paw // http://southpaw.co.nz/',
	'descriptionmsg' => 'bootstrap-mw-desc',
);

$wgValidSkinNames['bootstrapmw'] = 'BootstrapMW';
$wgAutoloadClasses['SkinBootstrapMW'] = __DIR__ . '/Bootstrap-MW.skin.php';
$wgExtensionMessagesFiles['BootstrapMW'] = __DIR__ .'/Bootstrap-MW.i18n.php';
 
$wgResourceModules['skins.bootstrapmw'] = array(
	'styles' => array(
		'Bootstrap-MW/css/bootstrap.min.css' => array( 'media' => 'screen' ),
		'Bootstrap-MW/css/font-awesome.min.css' => array( 'media' => 'screen' ),
		'Bootstrap-MW/css/style.css' => array( 'media' => 'screen' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
?>