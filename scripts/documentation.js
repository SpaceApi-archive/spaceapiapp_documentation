
$(document).ready(function(){
    
    // show the latest version
    $('#documentation-specs-tab a:last').tab('show');
    
    // each tab needs to be activated individually
    $('#documentation-specs-tab a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })
    
    $(".collapse").collapse();
});