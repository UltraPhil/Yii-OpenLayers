var lonLat_CSPQ = new OpenLayers.LonLat(-71.262538, 46.797775);

AutoSizeAnchoredBubbleMinSize = OpenLayers.Class(OpenLayers.Popup.AnchoredBubble, {
    "autoSize": true,
    "minSize": new OpenLayers.Size(400,200)
});

function getUrlGoogleMapBuddy(bounds)
{
    var res = this.map.getResolution();
    var x = Math.round ((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
    var y = Math.round ((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
    var z = this.map.getZoom();
    var path = + z + "/Ter_x=" + x + "y=" + y + "zoom="+z+".png";// + this.type;

    var url = this.url;
    if (url instanceof Array)
        url = this.selectUrl(path, url);

    return url + path;
}

function getUrlGMapCatcher(bounds)
{
    var res = this.map.getResolution();
    var x = Math.round ((bounds.left - this.maxExtent.left) / (res * this.tileSize.w));
    var y = Math.round ((this.maxExtent.top - bounds.top) / (res * this.tileSize.h));
    var z = this.map.getZoom();
    var path = (17-z) + "/" + Math.round(x/1024) + "/" + Math.round(x%1024) + "/" + Math.round(y/1024) + "/" + Math.round(y%1024) + ".png";

    var url = this.url;
    if (url instanceof Array)
        url = this.selectUrl(path, url);

    return url + path;
}
