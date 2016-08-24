(function () {
    $(document).on("click", "[data-action='editable-label']", function (ev) {
        var $this = $(this);
        if ($this.attr("data-status") === "editing") {
            return;
        }
        var $input = $this.children("[data-role='input']");
        var $label = $this.find("[data-role='label']");
        $this.attr("data-status", "editing");
        $label.hide();
        $input.show();
    });
    $(document).on("blur", "[data-action='editable-label'] [data-role='input']", function (ev) {
        var $this = $(this);
        var $parent = $this.parent("[data-action='editable-label']");
        var $label = $parent.find("[data-role='label']");
        $parent.removeAttr("data-status");
        $label.text($this.val());
        $this.hide();
        $label.show();
    });
})();
