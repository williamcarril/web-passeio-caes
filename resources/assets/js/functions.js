(function () {
    //Adding method to string prototype
    String.prototype.ucfirst = function () {
        return this.charAt(0).toUpperCase() + this.slice(1);
    };
    String.prototype.slugify = function () {
        return this.toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start of text
                .replace(/-+$/, '');            // Trim - from end of text
    };

    //Defining a wrapper to application's global jscript variables
    globals = {};

    //HTML Templates Wrapper
    var $templates = $("#html-templates");
    globals.templates = $templates;

    //Translated months
    globals.months = [
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

    //Predefined input validation and comportment for jquery
    $.fn.extend({
        "validate": function (rule, $data, event) {
            var $this = $(this);
            if (!($this.is("input") || $this.is("textarea") || $this.is("select"))) {
                return $this;
            }
            if (event) {
                $this.on(event, function () {
                    $this.validate(rule, $data);
                });
                return $this;
            }
            var failed = false;
            switch (rule) {
                case "empty":
                    failed = validate.empty($this.val());
                    break;
                case "phone":
                    failed = !validate.phone($this.val());
                    break;
                case "cep":
                    failed = !validate.cep($this.val());
                    break;
                case "email":
                    failed = !validate.email($this.val());
                    break;
                case "nonEmptyEquals":
                    if (!($data.is("input") || $data.is("textarea") || $data.is("select"))) {
                        failed = true;
                    } else {
                        failed = validate.empty($this.val()) || !validate.equals($this.val(), $data.val());
                    }
                    if (failed) {
                        setInputStatus($data, "error");
                    } else {
                        setInputStatus($data, "success");
                    }
                    break;
                case "equals":
                    if (!($data.is("input") || $data.is("textarea") || $data.is("select"))) {
                        failed = true;
                    } else {
                        failed = !validate.equals($this.val(), $data.val());
                    }
                    if (failed) {
                        setInputStatus($data, "error");
                    } else {
                        setInputStatus($data, "success");
                    }
                    break;
            }
            if (failed) {
                setInputStatus($this, "error");
            } else {
                setInputStatus($this, "success");
            }
            return $this;
        }
    });

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

    //Default ajax request to a simple form element.
    $.fn.extend({
        "defaultAjaxSubmit": function (redirectUrl, validation, redirectTimer) {
            var $this = $(this);
            if (!$this.is("form")) {
                return $this;
            }
            redirectTimer = redirectTimer || 3000;
            validation = validation || function () {
                return true;
            };
            var $submitButton = $this.find("button[type='submit']");
            $this.submit(function (ev) {
                ev.stopPropagation();
                ev.preventDefault();
                if (!validation($this, $submitButton)) {
                    return false;
                }
                $.ajax({
                    "url": $this.attr("action"),
                    "type": $this.attr("method"),
                    "data": new FormData($this[0]),
                    "processData": false,
                    "contentType": false,
                    "beforeSend": function () {
                        if ($submitButton) {
                            $submitButton.addClass("disabled").addClass("loading");
                        }
                    },
                    "success": function (response) {
                        if (!response.status) {
                            showAlert(response.messages, "error");
                            $submitButton.removeClass("disabled");
                        } else {
                            showAlert('Operação realizada com sucesso!', "success");
                            if (redirectUrl) {
                                setInterval(function () {
                                    window.location.replace(redirectUrl);
                                }, redirectTimer);
                            }
                        }
                    },
                    "error": function (request) {
                        if (request.statusText !== 'abort') {
                            showAlert("Ocorreu um problema ao enviar a requisição. Por favor, atualize a página ou tente novamente mais tarde.", "error");
                        }
                        $submitButton.removeClass("disabled");
                    },
                    "complete": function () {
                        $submitButton.removeClass("loading");
                    }
                });
            });
            return $this;
        }
    });

    //Default ajax validation function
    $.fn.extend({
        "ajaxValidation": function (url, type, data, errorMessage, success, beforeSend, complete, error) {
            var $this = $(this);
            errorMessage = errorMessage || "O valor informado é inválido.";
            success = success || function (response) {
                if (response.status) {
                    setInputStatus($this, "error");
                    if (errorMessage) {
                        showAlert(errorMessage, "error");
                    }
                } else {
                    setInputStatus($this, "success");
                }
            };
            beforeSend = beforeSend || function () {
                $this.addClass("loading");
            };
            complete = complete || function () {
                $this.removeClass("loading");
            };
            error = error || function (request) {
                setInputStatus($this, "success");
            };
            $.ajax({
                "url": url,
                "type": type,
                "data": data,
                "success": success,
                "beforeSend": beforeSend,
                "error": error,
                "complete": complete
            });
            return $this;
        }
    });

    //Creates an unique number (IDs, for example)
    window.uniqid = function (prefix) {
        prefix = prefix || "";
        var date = new Date();
        return  prefix
                + date.getFullYear()
                + "-"
                + date.getMonth()
                + "-"
                + date.getSeconds()
                + "_"
                + date.getHours()
                + "-"
                + date.getMinutes()
                + "-"
                + date.getSeconds()
                + "_"
                + Math.random().toFixed(6) * 1000000;
    };

    //Defining scrollTo function
    $.fn.scrollView = function () {
        return this.each(function () {
            $('html, body').animate({
                scrollTop: $(this).offset().top
            }, 1000);
        });
    };

    //Basic date formatation function
    window.simpleDateFormatter = function (day, month, year, format) {
        format = format || "d/m/Y";
        if (parseInt(day) < 10) {
            day = "0" + day;
        }
        if (parseInt(month) < 10) {
            month = "0" + month;
        }
        return format.replace("d", day).replace("m", month).replace("Y", year);
    };

    //Basic monetary formatation function
    window.formatMoney = function (value, currency, decimalSeparator) {
        currency = currency || "R$";
        decimalSeparator = decimalSeparator || ",";
        return currency + " " + parseFloat(value).toFixed(2).replace(".", decimalSeparator);
    }

    //Simple time difference calculator
    window.diffTime = function (time1, time2, precision) {
        precision = precision ? precision.toLowerCase() : "h";
        var diff = new Date("2000-01-01 " + time1) - new Date("2000-01-01 " + time2)

        switch (precision) {
            case "h":
                return diff / 3600000;
            case "m":
                return diff / 60000;
            case "s":
                return diff / 1000;
            case "ms":
                return diff;
        }
    }
})();
