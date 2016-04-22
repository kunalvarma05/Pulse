jQuery(document).ready(function($) {
        //Sidemenu Tooltips
        $("body").tooltip({
            selector: "[data-toggle-tooltip=sidebar]",
            placement: 'right'
        });

        //Sidemenu Tooltips
        $("html").tooltip({
            selector: "[data-toggle-tooltip=explorer]",
            placement: 'bottom'
        });

        $("[data-scrollbar=true]").each(function(index, el) {
            Ps.initialize(el);
        });

});