(function () {
    //Defining a wrapper to application's global jscript variables
    globals = {};

    //HTML Templates Wrapper
    var $templates = $("#htmlTemplates");

    //Default alert function
    var $alerts = $("#alerts");
    window.showAlert = function (message, type, $wrapper) {
        $wrapper = $wrapper ? $wrapper : $alerts;
        var html = {
            "success": $templates.find("[data-name='success-alert']").clone(),
            "error": $templates.find("[data-name='error-alert']").clone(),
            "info": $templates.find("[data-name='info-alert']").clone(),
            "warning": $templates.find("[data-name='warning-alert']").clone()
        };
        var $alert;
        switch (type) {
            case "success":
                $alert = html.success;
                break;
            case "error":
                $alert = html.error;
                break;
            case "warning":
                $alert = html.warning;
                break;
            case "info":
                $alert = html.info;
                break;
            default:
                $alert = html.error;
                break;
        }
        $alert.html(function () {
            return $(this).html().replace("!{message}", message);
        });
        $wrapper.append($alert).fadeIn(500);
    };
    window.clearAlerts = function () {
        $alerts.html("");
    };
    
    //Add classes refer input status
    window.setInputStatus = function($input, status) {
        var classes = [
            "form-control -success", 
            "form-control -error", 
            "form-control -warning"
        ];
        status = status.toLowerCase();
        $input.removeClass(classes.join(" "));
        switch(status) {
            case "success":
                $input.addClass(classes[0]); break;
            case "error":
                $input.addClass(classes[1]); break;
            case "warning":
                $input.addClass(classes[2]); break;
            default:
                break;
        }
    };
    
    //Common validation functions
    var validate = {};
    window.validate = validate;
    validate.cpf = function(cpf) {
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
    validate.cep = function(cep) {
        var pattern = /^[0-9]{8}$/;
        cep = cep.replace(/[^0-9]/g, "");
        return pattern.test(cep);
    };
    validate.phone = function(phone) {
        var pattern = /^[0-9]{6}[0-9]{4}[0-9]?$/;
        phone = phone.replace(/[^0-9]/g, "");
        return pattern.test(phone);
    };
    validate.equals = function(field1, field2) {
        return field1 === field2;
    };
    validate.empty = function(value) {
        if(value instanceof String) {
            return (!value.trim() || 0 === value.length);
        }
        return !value;
    };
    validate.email = function(email) {
        if(!email) {
            return false;
        }
        var $DOMEmailInput = $("<input type='email' value='" + email + "' />");
        return $DOMEmailInput[0].checkValidity();
    };
})();
