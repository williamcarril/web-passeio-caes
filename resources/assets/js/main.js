(function () {
    //Defining a wrapper to application's global jscript variables
    globals = {};

    //HTML Templates Wrapper
    var $templates = $("#htmlTemplates");

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


    //Default alert function
    var $alerts = $("#alerts");
    window.showAlert = function (message, type, $wrapper) {
        $wrapper = $wrapper ? $wrapper : $alerts;
        var html = {
            "success": $templates.find("[data-name='success-alert']").clone(),
            "error": $templates.find("[data-name='error-alert']").clone(),
            "info": $templates.find("[data-name='info-alert']").clone(),
            "warning": $templates.find("[data-name='warning-alert']").clone()
        };
        var $alert;
        switch (type) {
            case "success":
                $alert = html.success;
                break;
            case "error":
                $alert = html.error;
                break;
            case "warning":
                $alert = html.warning;
                break;
            case "info":
                $alert = html.info;
                break;
            default:
                $alert = html.error;
                break;
        }
        $alert.html(function () {
            return $(this).html().replace("!{message}", message);
        });
        $wrapper.append($alert).fadeIn(500);
    };
    window.clearAlerts = function () {
        $alerts.html("");
    };
})();
