<?php

/**
 * Represents a OpenLayers map that can be js-rendered.
 *
 * TODO:
 *  -Allow the addControl method to pass arguments for controls with a constructor that receive parameters
 *  -Create a simpleControls method that receive an Array('Control', args('arg1','arg2', etc)) which would be a "bulk" addControl method
 *  -Create the other methods for the other properties.
 */
class OpenLayersMap extends OpenLayersCustomisableClass
{

    public $id = "";

    /**
     *  This is an array containing very basic options that should fit most needs
     * @var type array of options for OpenLayers
     */
    public static $defaultOptions = array(
	'controls' => array(
	    'js:new OpenLayers.Control.Navigation()',
	    'js:new OpenLayers.Control.PanZoomBar()',
	    'js:new OpenLayers.Control.LayerSwitcher()',
	    'js:new OpenLayers.Control.Attribution()'
	),
	'minExtent' => 'js:new OpenLayers.Bounds(-1, -1, 1, 1)',
	'maxExtent' => 'js:new OpenLayers.Bounds(-20037508.3427892, -20037508.3427892, 20037508.3427892, 20037508.3427892)',
	'numZoomLevels' => 13,
	'maxResolution' => 156543.0339,
	'units' => "m",
	'projection' => "EPSG:900913",
	'displayProjection' => 'js:new OpenLayers.Projection("EPSG:4326")'
    );

    public function __construct($options = null)
    {
	parent::__construct($options);
    }

    /**
     * Constructs the javascript statement to render the map.
     * @return string the snippet of javascript required to render the Map on screen.
     */
    public function render($usingDefaultOptions = false)
    {
	if ($usingDefaultOptions)
	    $this->setOptions(self::$defaultOptions);
	return "map = new OpenLayers.Map(\"$this->id\"," . $this->encodeOptionsToJSON() . ");";
    }

    /**
     * This function takes an array of string that represents the controls for the OpenLayers Map.
     * The List is very exhaustive and can be found on http://dev.openlayers.org/docs/files/OpenLayers/Control-js.html
     * The array must have string items of the form:
     * "js:new OpenLayers.Control.PanZoomBar()"
     * This allows you to pass controls which contructors need parameters
     *
     *
     * You can also add controls with the "AddControl" method, which adds only one control at a time.
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $controls an array of string. Check OpenLayersMap->defaultOptions for an example of controls
     * @return \OpenLayersMap the current object
     */
    public function controls($controls)
    {
	$this->options['controls'] = $controls;
	return $this;
    }

    /**
     * This function adds a control to the "controls" array of the options.
     * The List is very exhaustive and can be found on http://dev.openlayers.org/docs/files/OpenLayers/Control-js.html
     *
     * Note: You only need to pass the control name. For instance, if you want to add a PanZoomBar, pass "PanZoomBar"
     * This function will add into the "controls" array a new item with the value
     * "js:new OpenLayers.Control.PanZoomBar()"
     *
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $control
     * @return \OpenLayersMap
     */
    public function addControl($control)
    {
	if (!isset($this->options['controls']))
	    $this->options['controls'] = array();
	array_push($this->options['controls'], "js:new OpenLayers.Control.$control()");
	return $this;
    }

    /**
     * This function allows you to define the minimum extent of the map.
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $left left boundary
     * @param type $bottom bottom boundary
     * @param type $right right boundary
     * @param type $top top boundary
     * @return \OpenLayersMap
     */
    public function minExtent($left, $bottom, $right, $top)
    {
	$this->options['minExtent'] = "js:new OpenLayers.Bounds($left,$bottom,$right,$top)";
	return $this;
    }

    /**
     * This function allows you to define the maximum extent of the map.
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $left left boundary
     * @param type $bottom bottom boundary
     * @param type $right right boundary
     * @param type $top top boundary
     * @return \OpenLayersMap
     */
    public function maxExtent($left, $bottom, $right, $top)
    {
	$this->options['maxExtent'] = "js:new OpenLayers.Bounds($left,$bottom,$right,$top)";
	return $this;
    }

    /**
     * This function allows you to set the number of zoom levels for the map
     * @param type $value the number of zoom levels
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @return \OpenLayersMap
     */
    public function numZoomLevels($value)
    {
	$this->options['numZoomLevels'] = $value;
	return $this;
    }

    /**
     * Allows you to set the maximum resolution
     *
     * From OpenLayers' doc: Required if you are not displaying the whole world on a tile with the size specified in tileSize.
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param float $value the maximum resolution
     * @return \OpenLayersMap
     */
    public function maxResolution($value)
    {
	$this->options['maxResolution'] = $value;
	return $this;
    }

    /**
     * Allows you to set the desired unit for the map.
     *
     * From OpenLayers' doc:  Possible values are ‘degrees’ (or ‘dd’), ‘m’, ‘ft’, ‘km’, ‘mi’, ‘inches’.  Normally taken from the projection.
     * Only required if both map and layers do not define a projection, or if they define a projection which does not define units
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $value the unit
     * @return \OpenLayersMap
     */
    public function units($value)
    {
	$this->options['units'] = $value;
	return $this;
    }

    /**
     * Allow you to set the default projection for layers added to the map.
     *
     * From OpenLayers' doc: When using a projection other than EPSG:4326 (CRS:84, Geographic) or EPSG:3857 (EPSG:900913, Web Mercator),
     *  also set maxExtent, maxResolution or resolutions.  Default is “EPSG:4326”.
     *  Note that the projection of the map is usually determined by that of the current baseLayer (see baseLayer and getProjectionObject).
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $value the projection type
     * @return \OpenLayersMap
     */
    public function projection($value)
    {
	$this->options['projection'] = $value;
	return $this;
    }

    /**
     * Allows you to set the projection used by several controls to displaydata to users.
     *
     * From OpenLayers' doc: Requires proj4js support for projections other than EPSG:4326 or EPSG:900913/EPSG:3857.
     *
     * This function returns the currentOpenLayersMap object in order to pass multiple Options Yii-Style, just like when
     * passing DB Parameters (ex: Posts()->betweenYears(2009,2012)->withComments(true)->hasAuthor(array(1,2,3))->findAll();
     *
     * @param type $value the display Projection
     * @return \OpenLayersMap
     */
    public function displayProjection($value)
    {
	$this->options['displayProjection'] = "js:new OpenLayers.Projection('$value')";
	return $this;
    }

}

?>
