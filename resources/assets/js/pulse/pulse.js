jQuery(document).ready(function($) {

    //Sidemenu Tooltips
    $("#sidemenu").find("[data-toggle-tooltip='tooltip']").tooltip({
        placement: 'right'
    });

    //Explorer Tooltips
    $("#explorer").find("[data-toggle-tooltip='tooltip']").tooltip({
        placement: 'bottom'
    });

});