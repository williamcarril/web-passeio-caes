(function () {
    $(document).on("change", "input[type='file']", function () {
        var $this = $(this);
        var $fileinput = $this.parent("[data-action='image-uploader']");
        if ($fileinput.length) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $fileinput.find("[data-role='preview']").attr('src', e.target.result);
                    $fileinput.find("[data-role='placeholder']").text($this.val());
                };
                reader.readAsDataURL(this.files[0]);
            }
        }
    });

    $.fn.extend({
        "imageUploader": function (action, data) {
            if (this.attr("data-action") !== "image-uploader") {
                return;
            }
            var $input = this.find("[data-role='input']");
            var $preview = this.find("[data-role='preview']");
            var $placeholder = this.find("[data-role='placeholder']");
            switch (action) {
                case "placeholder":
                    $placeholder.text(data);
                    break;
                case "preview":
                    $preview.attr("src", data);
                    break;
                case "id":
                    $input.attr("id", data);
                    this.attr("for", data);
                    break;
            }
        }
    });
})();