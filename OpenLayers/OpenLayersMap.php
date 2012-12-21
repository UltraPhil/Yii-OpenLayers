<?php

/**
 * Represents a OpenLayers map that can be js-rendered.
 */
class OpenLayersMap extends OpenLayersCustomisableClass
{
    public $id = "";

    /**
     * Constructs the javascript statement to render the map.
     * @return string the snippet of javascript required to render the Map on screen.
     */
    public function render()
    {
	$jsOptions = $this->encodeOptionsToJSON();
	return "map = new OpenLayers.Map(\"$this->id\", $jsOptions);";
    }

}

?>
