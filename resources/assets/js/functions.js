(function () {
    //Defining a wrapper to application's global jscript variables
    globals = {};

    //HTML Templates Wrapper
    var $templates = $("#html-templates");
    globals.templates = $templates;

    //Default alert function
    var $alerts = $("#alerts");
    window.showAlert = function (messages, type, $wrapper) {
        $wrapper = $wrapper ? $wrapper : $alerts;
        var html = {
            "success": $templates.find("[data-name='success-alert']"),
            "error": $templates.find("[data-name='error-alert']"),
            "info": $templates.find("[data-name='info-alert']"),
            "warning": $templates.find("[data-name='warning-alert']")
        };
        var $alert;
        if (typeof messages === "string" || messages instanceof String) {
            messages = [messages];
        }
        for (var i in messages) {
            switch (type) {
                case "success":
                    $alert = html.success.clone();
                    break;
                case "error":
                    $alert = html.error.clone();
                    break;
                case "warning":
                    $alert = html.warning.clone();
                    break;
                case "info":
                    $alert = html.info.clone();
                    break;
                default:
                    $alert = html.error.clone();
                    break;
            }
            $alert.html(function () {
                return $(this).html().replace("!{message}", messages[i]);
            });
            $wrapper.append($alert).fadeIn(500);
        }
    };
    window.clearAlerts = function () {
        $alerts.html("");
    };

    //Add classes refer input status
    window.setInputStatus = function ($input, status) {
        var classes = {
            "success": "-success",
            "error": "-error",
            "warning": "-warning"
        };
        setStatusClass(classes, $input, status);
    };

    //Add classes refer color status
    window.setColorStatus = function ($component, status) {
        var classes = {
            "success": "_success-color",
            "error": "_error-color",
            "warning": "_warning-color"
        };
        setStatusClass(classes, $component, status);
    };

    function setStatusClass(classes, $element, status) {
        status = status.toLowerCase();
        for (var key in classes) {
            $element.removeClass(classes[key]);
        }
        switch (status) {
            case "success":
                $element.addClass(classes.success);
                break;
            case "error":
                $element.addClass(classes.error);
                break;
            case "warning":
                $element.addClass(classes.warning);
                break;
            default:
                break;
        }
    }

    //Common validation functions
    var validate = {};
    window.validate = validate;
    validate.cpf = function (cpf) {
        cpf = cpf.replace(/[^0-9]/g, "");
        if (cpf.length !== 11) {
            return false;
        }
        if (cpf == '00000000000' ||
                cpf == '11111111111' ||
                cpf == '22222222222' ||
                cpf == '33333333333' ||
                cpf == '44444444444' ||
                cpf == '55555555555' ||
                cpf == '66666666666' ||
                cpf == '77777777777' ||
                cpf == '88888888888' ||
                cpf == '99999999999') {
            return false;
        }
        for (var t = 9; t < 11; t++) {
            for (var d = 0, c = 0; c < t; c++) {
                d += cpf[c] * ((t + 1) - c);
            }
            d = ((10 * d) % 11) % 10;
            if (cpf[c] != d) {
                return false;
            }
        }
        return true;
    };
    validate.cep = function (cep) {
        var pattern = /^[0-9]{8}$/;
        cep = cep.replace(/[^0-9]/g, "");
        return pattern.test(cep);
    };
    validate.phone = function (phone) {
        var pattern = /^[0-9]{6}[0-9]{4}[0-9]?$/;
        phone = phone.replace(/[^0-9]/g, "");
        return pattern.test(phone);
    };
    validate.equals = function (field1, field2) {
        return field1 === field2;
    };
    validate.empty = function (value) {
        if (value instanceof String) {
            return (!value.trim() || 0 === value.length);
        }
        return !value;
    };
    validate.email = function (email) {
        if (!email) {
            return false;
        }
        var $DOMEmailInput = $("<input type='email' value='" + email + "' />");
        return $DOMEmailInput[0].checkValidity();
    };

    //Predefined input validation and comportment
    validate.inputs = {};
    validate.inputs.empty = function ($input) {
        var isEmpty = validate.empty($input.val());
        if (!isEmpty) {
            setInputStatus($input, "success");
        } else {
            setInputStatus($input, "error");
        }
        return isEmpty;
    };
    validate.inputs.phone = function ($input) {
        var isPhone = validate.phone($input.val());
        if (isPhone) {
            setInputStatus($input, "success");
        } else {
            setInputStatus($input, "error");
        }
        return isPhone;
    };
    validate.inputs.cep = function ($input) {
        var isCep = validate.cep($input.val());
        if (isCep) {
            setInputStatus($input, "success");
        } else {
            setInputStatus($input, "error");
        }
        return isCep;
    };

    //Default confirm modal function
    var $confirm = $("#confirm-modal");
    window.askConfirmation = function (title, text, confirmCallback, cancelCallback) {
        title = title || "Confirmação";
        text = text || "Deseja mesmo realizar esta operação?";
        confirmCallback = $.isFunction(confirmCallback) ? confirmCallback : function () {};
        cancelCallback = $.isFunction(cancelCallback) ? cancelCallback : function () {};

        if (($confirm.data("bs.modal") || {}).isShown) {
            return false;
        }
        $confirm.find("[data-role='title']").text(title);
        $confirm.find("[data-role='text']").text(text);
        $confirm.find("[data-role='confirm-button']").unbind("click").bind("click", confirmCallback);
        $confirm.find("[data-role='cancel-button']").unbind("click").bind("click", cancelCallback);
        $confirm.modal("show");
        return true;
    };

    //Noun article fixer
    window.fixArticle = function (text, gender, upperCase, articleMark) {
        upperCase = upperCase || false;
        articleMark = articleMark || "!{a}";
        var article = "o(a)";
        switch (gender) {
            case 0:
            case "male":
            case "macho":
                article = "o";
                break;
            case 1:
            case "femea":
            case "fêmea":
            case "female":
                article = "a";
                break;
            default:
                break;
        }
        if (upperCase) {
            article = article.toUpperCase();
        }
        return text.replace(new RegExp(articleMark, "gm"), article);
    };
})();
