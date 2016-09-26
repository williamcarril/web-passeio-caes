(function () {
    //Setting an default reader on all ajax calls
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        }
    });

    //Setting up sidebar "hide" button 
    var $sidebar = $("#sidebar");
    $("[data-action='sidebar-toggler']").click(function (ev) {
        if ($sidebar.hasClass("toggled")) {
            $sidebar.removeClass("toggled");
        } else {
            $sidebar.addClass("toggled");
        }
    });

    //Bootstrapping JQuery Input Mask plugin on data-inputmask inputs.
    $(":input").inputmask();

    //Initializing Carousels
    $('.carousel').carousel();

    //Initializing timepickers
    $(".timepicker").timepicker({
        "showMeridian": false,
        "defaultTime": false,
        "snapToStep": true,
        "minuteStep": 15
    });

    //Prevent default 'Hit enter' submit on forms for inputs
    $("form").on("keypress", function (ev) {
        if (ev.keyCode === 13 && !(ev.target.type === "submit" || ev.target.type === "password")) {
            return false;
        }
    });

    //Toggling chevron arrow on fieldset collapses
    function toggleChevron(e) {
        $(e.target)
                .prev("legend")
                .find('i.indicator')
                .toggleClass('glyphicon-chevron-down glyphicon-chevron-right');
    }
    $('fieldset .collapse').on('hidden.bs.collapse', toggleChevron);
    $('fieldset .collapse').on('shown.bs.collapse', toggleChevron);
})();
