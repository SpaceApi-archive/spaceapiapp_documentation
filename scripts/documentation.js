
$(document).ready(function(){
    
    // show the latest version
    $('#documentation-specs-tab a:last').tab('show');
    
    // each tab needs to be activated individually
    $('#documentation-specs-tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    $(".collapse").collapse();

    // show a tooltip on hover and a dialog on click
    $(".list_filters a")
        .tooltip({html:true})
        .click(function(event){
            event.preventDefault();
            var title = $(this).text();
            var body = $(this).data("original-title");
            var dialog = $('<div style="opacity: 0.85" title="'+ title +'">'+ body +'</div>');
            // this is now another object which we need as the context to change the header text color
            dialog = dialog.dialog({dialogClass: "filter-dialog"});


            $("div:contains('Supported')", dialog).css("color", "yellow")
                .remove()
                /*.appendTo($(".ui-dialog-titlebar"))*/
                /*.css("font-size", "12pt")*/
            ;

            $(".filter-dialog .ui-dialog-title")
                .css("padding", "0")
                .css("font-size", "10pt");
        });
});