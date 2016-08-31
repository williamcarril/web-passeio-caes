(function () {
    $(document).on("click", "[data-action='editable-label'] [data-role='label']", function (ev) {
        var $parent = $(this).parent("[data-action='editable-label']");
        $parent.trigger("editable-label:editing");
    });

    $(document).on("blur", "[data-action='editable-label'] [data-role='input']", function (ev) {
        var $parent = $(this).parent("[data-action='editable-label']");
        $parent.trigger("editable-label:done");
    });

    $(document).on("editable-label:editing", "[data-action='editable-label']", function () {
        var $this = $(this);
        var $label = $this.children("[data-role='label']");
        var $input = $this.children("[data-role='input']");
        changeStatus($this, $label, $input, "editing");
    });

    $(document).on("editable-label:done", "[data-action='editable-label']", function () {
        var $this = $(this);
        var $label = $this.children("[data-role='label']");
        var $input = $this.children("[data-role='input']");
        changeStatus($this, $label, $input, "done");
    });

    $.fn.extend({
        "editableLabel": function (action, data) {
            if (this.attr("data-action") !== "editable-label") {
                return;
            }
            var $input = this.find("[data-role='input']");
            var $label = this.find("[data-role='label']");
            switch (action) {
                case "fill":
                    var inputValue;
                    var labelText;
                    if ($.isPlainObject(data)) {
                        inputValue = data.input;
                        labelText = data.label;
                    }
                    if (data instanceof String || typeof data === "string") {
                        inputValue = data;
                        labelText = data;
                    }
                    if ($input.is("select")) {
                        var $option = $input.find("option[value='" + inputValue + "']");
                        $option.prop("selected", true);
                        $label.text($option.text());
                    } else {
                        $input.val(inputValue);
                        $label.text(labelText);
                    }
                    break;
                case "clear":
                    if ($input.is("select")) {
                        $input.find("option:selected").prop("selected", false);
                    } else {
                        $input.val("");
                    }
                    $label.text("");
                    break;
            }
        }
    });

    function changeStatus($wrapper, $label, $input, status) {
        switch (status) {
            case "editing":
                if ($wrapper.attr("data-status") === "editing") {
                    return;
                }
                $wrapper.attr("data-status", "editing");
                $label.hide();
                $input.show();
                $input.focus();
                break;
            case "done":
            default:
                if ($wrapper.attr("data-status") !== "editing") {
                    return;
                }
                $wrapper.removeAttr("data-status");
                if ($input.is("select")) {
                    var $selected = $input.find(":selected");
                    $label.text($selected.text());
                } else {
                    $label.text($input.val());
                }
                $input.hide();
                $label.show();
                break;
        }
    }
})();
