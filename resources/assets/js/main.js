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

    //Bootstrapping JQuery Input Mask plugin on data-inputmask inputs.
    $(":input").inputmask();

    //Customizing and Bootstrapping calendars
    var months = [
        "Janeiro",
        "Fevereiro",
        "Março",
        "Abril",
        "Maio",
        "Junho",
        "Julho",
        "Agosto",
        "Setembro",
        "Outubro",
        "Novembro",
        "Dezembro"
    ];
    $('.responsive-calendar').responsiveCalendar({
        "translateMonths": months
    });

    //Adding method to string prototype
    String.prototype.ucfirst = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    };
})();
