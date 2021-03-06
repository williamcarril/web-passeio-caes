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

    //Removing input number scroll
    $("input[type='number'].-no-spin").on("mousewheel", function (ev) {
        ev.preventDefault();
    });

    //Prevent default 'Hit enter' submit on forms for inputs
    $("form").on("keypress", function (ev) {
        if (ev.keyCode === 13 && !(ev.target.type === "submit" || ev.target.type === "password")) {
            return false;
        }
    });

    //Initializing timepickers
    $(".timepicker").timepicker({
        "showMeridian": false,
        "defaultTime": false,
        "snapToStep": true,
        "minuteStep": 15
    });
    
    //Initializing drag and drop
    $("ol[data-action='drag-and-drop']").sortable({
        "cancel": '.non-draggable',
        "placeholder": "placeholder",
         "items": "li:not(:last-child)"
    });
    
    //Adding active class on tabbed image controls
    $("body").on("click", ".tab-toggler", function() {
        var $this = $(this);
        var $parent = $this.parent(".tab-controls");
        $parent.find(".tab-toggler").removeClass("active");
        $this.addClass("active");
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
