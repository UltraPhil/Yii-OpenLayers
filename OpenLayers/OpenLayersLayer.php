<?php

/**
 * Reprensents an OpenLayer Layer.
 */
class OpenLayersLayer extends OpenLayersCustomisableClass
{

    public $type = "";
    public $id = "";
    public $markers = array();

    /**
     *
     * @param type $id ID of the layer
     * @param type $type Layer class
     * @param type $options Options to form the layer constructor
     */
    public function __construct( $id, $type, $options = null )
    {
	parent::__construct( $options );
	$this->id = $id;
	$this->type = $type;
    }

    /**
     *  Adds a marker to the array of markers for the Layer
     * @param type $marker  the said marker
     */
    public function addMarker( $marker )
    {
	$marker->layer = $this;
	$this->markers[] = $marker;
    }

    /**
     * This function creates a string which is the javascript code to create an OpenLayers Layer
     * 
     * 
     * @return the javascript code snippet from which the OpenLayers Layer is created
     */
    public function render()
    {
	$constructString = "var $this->id = new OpenLayers.Layer.$this->type(";

	foreach ( $this->options as $option )
	{
	    if ( !is_array( $option ) )
		$constructString .= "\"$option\", ";
	    else
		$constructString .= CJavaScript::encode( $option );
	}
	$constructString = trim( $constructString, ", " ) . "); map.addLayer($this->id);";

	return $constructString;
    }
    
    /**
     * This function's only purpose is to get rid of some useless code in the view.
     * This way, it is easier to "quick start".
     * 
     * (When defining your widget in the view, on the "layers" attribute you can just write
     * 
     * 'layers' => array(OpenLayersLayer::createGoogleMapsBuddyLayer('My Name', 'MyUrlForTiles'), OpenLayersLayer::createYahooMapsBuddyLayer(), $layerMarkers),
     * 
     * Instead of doing
     * 
     * 'layers' => array(New OpenLayersLayer(
     *      'GMBGoogleTerrain',
     *      'TMS',
     *      array(
     *      'Google Maps map',
     *      CHtml::normalizeUrl( array('/)). '/tiles/GMB_GoogleTerrain/'),
     *      params => array(
     *          'type' => 'png'
     *          'getURL' => 'js:getUrlGoogleMapBuddy',
     *      ),)
     * , OpenLayersLayer::createYahooMapsBuddyLayer(), $layerMarkers),
     * 
     * @param type $label the Label in the Layer selector
     * @param type $url the URL for retrieving tiles
     * @return \self a new instance of the class
     */
    public static function createGoogleMapsBuddyLayer($label = "", $url = ""){
        return new self("GMBGoogleTerrain", "TMS", array(
	    ($label != "" ? $label : "Google Terrain map"),
	    ($url != "" ? $url : CHtml::normalizeUrl( array('/') ) . '/tiles/GMB_GoogleTerrain/'),
	    "params" => array(
		'type' => 'png',
		'getURL' => 'js:getUrlGoogleMapBuddy',
	    ),
		) );
    }
    
    /**
     * This function's only purpose is the same as OpenLayersLayer::createGoogleMapsBuddyLayer()
     * @param type $label
     * @param type $url
     * @return \self
     */
    public static function createYahooMapsLayer($label = "", $url = ""){
        return new self( "YahooMaps", "TMS", array(
            ($label != "" ? $label : "Yahoo Relief/Routes Map"),
            ($url != "" ? $url : CHtml::normalizeUrl( array('/') ) . '/tiles/YahooMaps/'),
	    "params" => array(
		'type' => 'png',
		'getURL' => 'js:getUrlGMapCatcher',
	    ),
		) );
    }
}

?>
