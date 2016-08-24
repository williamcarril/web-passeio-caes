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
})();