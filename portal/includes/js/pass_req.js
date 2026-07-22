/*
 * Copyright Victor Witkamp (c) 2020.
 */

function initPassRequirements(input, options) {
    options = options || {};
    var rules = {
        minlength: {
            text: 'be at least minLength characters long',
            minLength: 8
        },
        containSpecialChars: {
            text: 'Your input should contain at least minLength special character',
            minLength: 1,
            regex: /([^!%&@#$^*?_~])/g
        },
        containLowercase: {
            text: 'Your input should contain at least minLength lower case character',
            minLength: 1,
            regex: /[^a-z]/g
        },
        containUppercase: {
            text: 'Your input should contain at least minLength upper case character',
            minLength: 1,
            regex: /[^A-Z]/g
        },
        containNumbers: {
            text: 'Your input should contain at least minLength number',
            minLength: 1,
            regex: /[^0-9]/g
        }
    };

    var requirementList = '';
    Object.keys(rules).forEach(function (key) {
        requirementList += '<li id="' + key + '">' + rules[key].text.replace('minLength', String(rules[key].minLength)) + '</li>';
    });

    var popover = new bootstrap.Popover(input, {
        title: 'Password Requirements',
        trigger: options.trigger || 'focus',
        html: true,
        sanitize: false,
        placement: options.popoverPlacement || 'bottom',
        content: 'Your password should:<ul>' + requirementList + '</ul>'
    });

    function updateRequirementState() {
        Object.keys(rules).forEach(function (key) {
            var tip = popover.tip;
            if (!tip) {
                return;
            }
            var item = tip.querySelector('#' + key);
            if (!item) {
                return;
            }
            var rule = rules[key];
            var stripped = input.value.replace(rule.regex, '');
            item.style.textDecoration = stripped.length > rule.minLength - 1 ? 'line-through' : 'none';
        });
    }

    input.addEventListener('keyup', updateRequirementState);
    input.addEventListener('shown.bs.popover', updateRequirementState);
}

document.addEventListener('DOMContentLoaded', function () {
    var newPassword = document.getElementById('newPassword');
    if (newPassword) {
        initPassRequirements(newPassword, {
            popoverPlacement: 'bottom',
            trigger: 'click'
        });
    }
});
