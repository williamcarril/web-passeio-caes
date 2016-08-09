(function () {
    //Setting an default reader on all ajax calls
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        }
    });

    //Setting up sidebar "hide" button 
    var $wrapper = $("#wrapper");

    $("#sidebar-toggle").click(function (ev) {
        ev.preventDefault();
        ev.stopPropagation();
        if ($wrapper.hasClass("toggled")) {
            $wrapper.removeClass("toggled");
        } else {
            $wrapper.addClass("toggled");
        }
    });

    //Bootstraping JQuery Input Mask plugin on data-inputmask inputs.
    $(":input").inputmask();
})();
