/**
 * Fix l'autoCompleter pour ajouter les binds necessaires et les events pour flusher les
 * items qui ont etes ajoutes.
 */
$(document).ready(function()
{
    // Formatte la liste de completion automatique
    $( "#siteChooser" ).autocomplete().data( "autocomplete" )._renderItem = function( ul, item ) {
        var format = "<a><b>" + item.Name + "</b></a>";
        
        return $( "<li></li>" )
        .data( "item.autocomplete", item )
        .append( format )
        .appendTo( ul );
    };

    // Montre la liste complete sur DownKey et bind le Tab a selection du choix en surbrillance
    $( "#siteChooser" ).bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.DOWN && !($(this).data("autocomplete").menu.active) )
            $("#siteChooser").autocomplete("search", "%");

        if ( event.keyCode === $.ui.keyCode.TAB && $(this).data( "autocomplete" ).menu.active )
            event.preventDefault();
    });

});
