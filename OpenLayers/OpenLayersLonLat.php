<?php

/**
 * Represents a Latitude/Longitude paired with an ID that can be rendered in javascript for OpenLayers.
 */
class OpenLayersLonLat
{

    public $id;
    public $lon = 0.0;
    public $lat = 0.0;

    /**
     * 
     * @param type $id  ID of the object to be rendered on the map
     * @param type $lon Longitude of the object
     * @param type $lat Latitude of the object
     */
    public function __construct( $id, $lon, $lat )
    {
	$this->id = $id;
	$this->lon = $lon;
	$this->lat = $lat;
    }

    /**
     *  Renders the required javascript code to set the LonLat position of a marker.
     * @return string A javascript code snippet that will be included in the final render (see OpenLayersWidget->run())
     */
    public function render()
    {
	return "var $this->id = new OpenLayers.LonLat($this->lon, $this->lat); $this->id.transform(map.displayProjection,map.getProjectionObject());";
    }

}

?>
