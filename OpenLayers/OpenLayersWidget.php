<?php

/**
 * OpenLayers widget.
 *
 * @author Philippe Desmarais <UltraSatellite@gmail.com>
 * link none yet
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.1
 * @package application.extensions.OpenLayers
 */

/**
 * OpenLayersWidget encapsulates the {@link http://openlayers.org/ OpenLayers}
 * OpenLayers mapping objects.
 *
 * Here is a code example to use cached tiles (downloaded with GMapCatcher),
 * from Google or Yahoo:
 *
 * 1st: define one or more map layers:
 * <pre>
 * $layerGoogleMapBuddy = new OpenLayersLayer( "Google tiles", "TMS", array(
 * 	    "Google Terrain - 2011",
 * 	    'tiles/GoogleTerrain/',
 * 	    "params" => array(
 * 		'type' => 'png',
 * 		'getURL' => 'js:getUrlGMapCatcher',
 * 	    ),
 * 		) );
 * $layerYahooMaps = new OpenLayersLayer( "YahooMaps", "TMS", array(
 * 	    "Yahoo Terrain/Route - 2011",
 * 	    'tiles/YahooMaps/',
 * 	    "params" => array(
 * 		'type' => 'png',
 * 		'getURL' => 'js:getUrlGMapCatcher',
 * 	    ),
 * 		) );
 * </pre>
 *
 * 2nd: define a marker layer:
 * <pre>
 * $layerMarkers = new OpenLayersLayer( 'Sites', 'Markers', array('LayerName') );
 * </pre>
 *
 * 3rd: make as many markers as you want:
 * <pre>
 * $coords = new OpenLayersLonLat( "site_$site->nbSite", $site->longitude, $site->latitude );
 * $marker = new OpenLayersMarker( $coords, 'AutoSizeAnchoredBubbleMinSize', $popupInnerHTML, true );
 * $layerMarkers->addMarker( $marker );
 * </pre>
 *
 * 4th:make a map and set options.
 * <pre>
 * $map = new OpenLayersMap();
 * // Options to show Google tiles correctly.
 * $map->setOptions( array(
 *     'controls' => array(
 * 	'js:new OpenLayers.Control.Navigation()',
 * 	'js:new OpenLayers.Control.PanZoomBar()',
 * 	'js:new OpenLayers.Control.LayerSwitcher()',
 * 	'js:new OpenLayers.Control.Attribution()'
 *     ),
 *     'minExtent' => 'js:new OpenLayers.Bounds(-1, -1, 1, 1)',
 *     'maxExtent' => 'js:new OpenLayers.Bounds(-20037508.3427892, -20037508.3427892, 20037508.3427892, 20037508.3427892)',
 *     'numZoomLevels' => 13,
 *     'maxResolution' => 156543.0339,
 *     'units' => "m",
 *     'projection' => "EPSG:900913",
 *     'displayProjection' => 'js:new OpenLayers.Projection("EPSG:4326")'
 * ) );
 * </pre>
 *
 * 5th: Use the widget to show the map (requiredScript option is show right after):
 * <pre>
 * $this->Widget( 'ext.OpenLayers.OpenLayersWidget', array(
 *     'map' => $map,
 *     'layers' => array($layerGoogleMapBuddy, $layerYahooMaps, $layerMarkers),
 *     'requiredScript' => Yii::getPathOfAlias( 'application.extensions.javascript' ) . '/urlsFunctions.js',
 *     'htmlOptions' => array(
 * 	'style' => 'min-height:400px; min-width:800px; display: width:1024px; height:768px; border:1px solid;',
 *     ),
 * ) );
 * </pre>
 *
 * You can also make post-jobdone scripts (i.e. center the map after loading):
 * <pre>
 * Yii::app()->clientScript->registerScript( "i", "MapStartCenterLonLat.transform(map.displayProjection,map.getProjectionObject());map.setCenter(MapStartCenterLonLat, 5);map.updateSize()", CClientScript::POS_LOAD );
 * </pre>
 *
 * Here is my 'urlsFunctions.js' file, containing the required javascript for the map to load&show:
 * <pre>
 * var MapStartCenterLonLat = new OpenLayers.LonLat(-71.262538, 46.797775);
 *
 * AutoSizeAnchoredBubbleMinSize = OpenLayers.Class(OpenLayers.Popup.AnchoredBubble, {
 *     "autoSize": true,
 *     "minSize": new OpenLayers.Size(400,200)
 * });
 *
 * function getUrlGMapCatcher(bounds)
 * {
 *     var res = this.map.getResolution();
 *     var x = Math.round ((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
 *     var y = Math.round ((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
 *     var z = this.map.getZoom();
 *     var path = (17-z) + "/" + Math.round(x/1024) + "/" + Math.round(x%1024) + "/" + Math.round(y/1024) + "/" + Math.round(y%1024) + ".png";
 *
 *     var url = this.url;
 *     if (url instanceof Array)
 *         url = this.selectUrl(path, url);
 *
 *     return url + path;
 * }
 * </pre>
 *
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://www.highcharts.com/
 * Highcharts website} for possible options.
 */
class OpenLayersWidget extends CWidget
{

    public $options = array();

    /**
     * @var OpenLayersMap Map object.
     */
    public $map;

    /**
     * Missing tiles override URL
     * @var string the URL pointing to the image to show for missing tiles.
     */
    public $OverrideMissingTileURL;

    /**
     *
     * @var OpenLayersLayer User-defined layers.
     */
    public $layers;
    public $htmlOptions = array();

    /**
     * @var string Complete path of a JS file that will be required by the map or layers. (i.e. TSM getURL function or redefine onError default image url)
     */
    public $requiredScript;
    private $assetsBasePath;
    private $assetsBaseURL;
    private $baseScriptsID;

    public function __construct( $owner = null )
    {
	parent::__construct( $owner );

	$this->assetsBasePath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR;
	$this->assetsBaseURL = Yii::app()->getAssetManager()->publish( $this->assetsBasePath, false, -1, YII_DEBUG ) . '/';
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
	$id = $this->getId();
	$this->baseScriptsID = __CLASS__ . '#' . $id;
	$defaultMapOptions = array('theme' => "$this->assetsBaseURL/theme/default/style.css");

	$this->htmlOptions['id'] = $id;
	$this->map->id = $id;

	echo CHtml::openTag( 'div', $this->htmlOptions );
	echo CHtml::closeTag( 'div' );

	$this->registerScripts();

	$this->map->options = CMap::mergeArray( $defaultMapOptions, $this->map->options );
	Yii::app()->clientScript->registerScript( $this->baseScriptsID, $this->map->render(), CClientScript::POS_LOAD );

	$this->registerLayers();
    }

    /**
     * Registers the differents layers
     */
    protected function registerLayers()
    {
	foreach ( $this->layers as $layer )
	{
	    Yii::app()->clientScript->registerScript( $this->baseScriptsID . '_' . $layer->id, $layer->render(), CClientScript::POS_LOAD );

	    if ( count( $layer->markers ) )
		foreach ( $layer->markers as $marker )
		{
		    Yii::app()->clientScript->registerScript( $this->baseScriptsID . '_' . rand(), $marker->location->render(), CClientScript::POS_LOAD );
		    Yii::app()->clientScript->registerScript( $this->baseScriptsID . '_' . rand(), $marker->render(), CClientScript::POS_LOAD );
		}
	}
    }

    /**
     * Registers necessary scripts to render OpenLayers.
     */
    protected function registerScripts()
    {
	$openLayersFileName = "OpenLayers.js";
	$openLayersUncompressedFileName = "OpenLayers.src.js";
	$markerHelperFileName = "addMarker.js";
	$javascriptHeaderFileName = "javascriptHeader.js";

	Yii::app()->clientScript->registerScriptFile( $this->assetsBaseURL . ((YII_DEBUG) ? $openLayersUncompressedFileName : $openLayersFileName), CClientScript::POS_HEAD );
	Yii::app()->clientScript->registerScriptFile( $this->assetsBaseURL . $markerHelperFileName, CClientScript::POS_HEAD );
	Yii::app()->clientScript->registerScriptFile( $this->assetsBaseURL . $javascriptHeaderFileName, CClientScript::POS_HEAD );
	Yii::app()->clientScript->registerScript( 'OpenlayersImgPath', 'OpenLayers.ImgPath = "' . $this->assetsBaseURL . 'img/"', CClientScript::POS_HEAD );

	if ( isset( $this->OverrideMissingTileURL ) )
	{
	    Yii::app()->clientScript->registerScript(
		    'OverrideMissingTilesFunction', '
		    /**
		    * Function: onImageLoadError
		    */
		    OpenLayers.Util.onImageLoadError = function()
		    {
			this.src = "' . $this->OverrideMissingTileURL . '";
		    };', CClientScript::POS_BEGIN
	    );
	}

	if ( $this->requiredScript )
	    Yii::app()->clientScript->registerScriptFile( Yii::app()->getAssetManager()->publish( $this->requiredScript ), CClientScript::POS_HEAD );
    }

}

?>
