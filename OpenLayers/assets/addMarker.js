/**
 * Function: addMarker
 * Add a new marker to the markers layer given the following lonlat,
 *     popupClass, and popup contents HTML. Also allow specifying
 *     whether or not to give the popup a close box.
 *
 * Parameters:
 * ll - {<OpenLayers.LonLat>} Where to place the marker
 * layer - {<OpenLayers.Layer>} Which layers to place the marker
 * popupClass - {<OpenLayers.Class>} Which class of popup to bring up
 *     when the marker is clicked.
 * popupContentHTML - {String} What to put in the popup
 * closeBox - {Boolean} Should popup have a close box?
 * overflow - {Boolean} Let the popup overflow scrollbars?
 *
 * (Taken from somewhere in the Internets and then modified)
 */
function addMarker(ll, markerObj, layer, popupClass, popupContentHTML, closeBox, overflow, icon)
{
    var feature = new OpenLayers.Feature(layer, ll);
    feature.closeBox = closeBox;
    feature.popupClass = popupClass;
    feature.data.popupContentHTML = popupContentHTML;

    feature.data.overflow = (overflow) ? "auto" : "hidden";
    
    if(icon != ""){
        feature.data.icon = new OpenLayers.Icon(OpenLayers.Util.getImageLocation(icon) , {w:21,h:25},{x:-10.5,y:-25});
    }
    
    var marker = feature.createMarker();

    marker.events.register("mousedown", feature, function(evt)
    {
        if (this.popup == null)
        {
            this.popup = this.createPopup(this.closeBox);
            map.addPopup(this.popup);
            this.popup.show();
        } else {
            this.popup.toggle();
        }
        currentPopup = this.popup;
        OpenLayers.Event.stop(evt);
    });

    layer.addMarker(marker);
    markers[markerObj] = marker;
}
