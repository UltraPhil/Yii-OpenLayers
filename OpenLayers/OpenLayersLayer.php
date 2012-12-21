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

}

?>
