(function () {
    $(document).on("click", "[data-action='editable-label']", function (ev) {
        var $this = $(this);
        var $input = $this.children("[data-role='input']");
        var $label = $this.find("[data-role='label']");
        changeStatus($this, $label, $input, "editing");
    });
    $(document).on("blur", "[data-action='editable-label'] [data-role='input']", function (ev) {
        var $this = $(this);
        var $parent = $this.parent("[data-action='editable-label']");
        var $label = $parent.find("[data-role='label']");
        changeStatus($parent, $label, $this, "done");
    });

//    $.fn.extend({
//        "editableLabel": function (action) {
//            if (this.attr("data-action") !== "editable-label") {
//                return;
//            }
//            switch (action) {
//                case "clear":
//                    var $input = this.find("[data-role='input']");
//                    var $label = this.find("[data-role='label']");
//                    $input.val("");
//                    $label.text("");
//                    break;
//            }
//        }
//    });

    function changeStatus($wrapper, $label, $input, status) {
        switch (status) {
            case "editing":
                if ($wrapper.attr("data-status") === "editing") {
                    return;
                }
                $wrapper.attr("data-status", "editing");
                $label.hide();
                $input.show();
                $wrapper.trigger("editable-label:editing");
                $input.focus();
                break;
            case "done":
            default:
                $wrapper.removeAttr("data-status");
                if ($input.is("select")) {
                    var $selected = $input.find(":selected");
                    $label.text($selected.text());
                } else {
                    $label.text($input.val());
                }
                $input.hide();
                $label.show();
                $wrapper.trigger("editable-label:done");
                break;
        }
    }
})();
