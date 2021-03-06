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

    //Prevent default 'Hit enter' submit on forms for inputs
    $("form").on("keypress", function (ev) {
        if (ev.keyCode === 13 && !(ev.target.type === "submit" || ev.target.type === "password")) {
            return false;
        }
    });
})();
