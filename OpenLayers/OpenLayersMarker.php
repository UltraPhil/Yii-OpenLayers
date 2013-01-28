<?php

/**
 * Represented an OpenLayers marker that can be js-rendered.
 * Currently supports: class, content, closeButton, location, and layer (parent)
 */
class OpenLayersMarker
{

    public $content;
    public $class;
    public $closeButton = true;
    public $location;
    public $layer;
    public $icon;

    /**
     * Default Marker constructor
     *
     * @param OpenLayersLatLong $location marker's location
     * @param string $class marker's class (CSS)
     * @param string $content marker's content
     * @param bool $closeButton True: display close button.
     */
    public function __construct($location, $class, $content, $closeButton = true, $icon = "")
    {
	$this->location = $location;
	$this->class = $class;
	$this->content = $content;
	$this->closeButton = $closeButton;
	$this->icon = $icon;
    }

    /**
     * Constructs a string that is need to render a marker.
     * @return string the code required to render the marker on screen.
     */
    public function render()
    {
	$layer = $this->layer->id;
	$location = $this->location->id;
	$siteNumber = substr($location, strpos($location, "_") + 1);

	return "addMarker($location, \"$siteNumber\", $layer, $this->class, '$this->content', " . (($this->closeButton) ? "true" : "false") . ", true, '$this->icon');";
    }

}

?>
