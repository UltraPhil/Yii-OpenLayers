<?php

/**
 * Base class for classes which options should be serializable in json for rendering.
 */
class OpenLayersCustomisableClass
{
    
    /**
     *  This variable must be a valid JSON string or array of string that can 
     *  be JSONified
     * @var string or array of string 
     */
    public $options = "";

    /**
     * Basic class constructor. 
     * @param type $options The desired options. See comments in $options variable declaration of  OpenLayersCustomisableClass for variable formats.
     */
    public function __construct( $options = null )
    {
	if ( $options )
	    $this->setOptions( $options );
    }

    /**
     * Override current options for a new set of options
     * @param type $options The desired options. See comments in $options variable declaration of  OpenLayersCustomisableClass for variable formats.
     * @throws CException The $options are not in a valid JSON format or array that can be JSONified.
     */
    public function setOptions( $options )
    {
	if ( !is_string( $options ) && !is_array( $options ) )
	    throw new CException( Yii::t( 'OpenLayersWidget', 'Array should be a valid JSON string or array that can be JSONified.' ) );

	$this->options = $options;
    }
    /**
     * Encode the options to a JSONified format
     * @return type The encoded options in JSON
     */
    public function encodeOptionsToJSON()
    {
	return CJavaScript::encode( $this->options );
    }

}

?>
