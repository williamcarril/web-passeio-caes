(function () {
    globals = {};
    
    $.ajaxSetup({
        "headers": {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        }
    });

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
})();
