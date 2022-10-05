/* ==========================================================================
    Helper functions
 ========================================================================== */

/*
 * Simple isset method for this does not exist in javascript
 */
window.isset = function (obj) {
    return typeof obj !== 'undefined' && obj !== null;
};

/*
 * Easing Functions - inspired from http://gizma.com/easing/
 * only considering the t value for the range [0, 1] => [0, 1]
 */
window.EasingFunctions = {
    // no easing, no acceleration
    linear: function (t) {
        return t
    },
    // accelerating from zero velocity
    easeInQuad: function (t) {
        return t * t
    },
    // decelerating to zero velocity
    easeOutQuad: function (t) {
        return t * (2 - t)
    },
    // acceleration until halfway, then deceleration
    easeInOutQuad: function (t) {
        return t < .5 ? 2 * t * t : -1 + (4 - 2 * t) * t
    },
    // accelerating from zero velocity
    easeInCubic: function (t) {
        return t * t * t
    },
    // decelerating to zero velocity
    easeOutCubic: function (t) {
        return (--t) * t * t + 1
    },
    // acceleration until halfway, then deceleration
    easeInOutCubic: function (t) {
        return t < .5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1
    },
    // accelerating from zero velocity
    easeInQuart: function (t) {
        return t * t * t * t
    },
    // decelerating to zero velocity
    easeOutQuart: function (t) {
        return 1 - (--t) * t * t * t
    },
    // acceleration until halfway, then deceleration
    easeInOutQuart: function (t) {
        return t < .5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t
    },
    // accelerating from zero velocity
    easeInQuint: function (t) {
        return t * t * t * t * t
    },
    // decelerating to zero velocity
    easeOutQuint: function (t) {
        return 1 + (--t) * t * t * t * t
    },
    // acceleration until halfway, then deceleration
    easeInOutQuint: function (t) {
        return t < .5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t
    }
};

/*
 * Vanilla version of the $.getScript
 */
window.getScript = function (source, callback) {
    let script = document.createElement('script');
    script.async = 1;

    window.scripts = document.getElementsByTagName('script');
    let prior = scripts[scripts.length - 1];

    script.onload = script.onreadystatechange = function (_, isAbort) {
        if (isAbort || !script.readyState || /loaded|complete/.test(script.readyState)) {
            script.onload = script.onreadystatechange = null;
            script = undefined;

            if (!isAbort) {
                if (callback) callback();
            }
        }
    };

    script.src = source;
    prior.parentNode.insertBefore(script, prior);
};

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing.
 * @param func
 * @param wait
 * @param immediate
 * @returns {Function}
 */
window.debounce = function(func, wait, immediate) {
    let timeout;
    return function () {
        let context = this, args = arguments;
        let later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

/*
 * Cookie helper class
 */
window.Cookie = {
    set: function (name, value, days) {
        let domain, domainParts, date, expires, host;

        if (days) {
            date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }

        host = location.host;
        if (isIPOrLocalHost(location.hostname)) {
            document.cookie = name + "=" + value + expires + "; path=/";
        }
        else {
            // Remember the cookie on all sub domains.
            //
            // Start with trying to set cookie to the top domain.
            // (example: if user is on foo.com, try to set
            //  cookie to domain ".com")
            //
            // If the cookie will not be set, it means ".com"
            // is a top level domain and we need to
            // set the cookie to ".foo.com"
            domainParts = host.split('.');
            domainParts.shift();
            domain = '.' + domainParts.join('.');

            // For development purpose, remove this when moving to production
            // domain = '.komma.nl';

            document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;

            // check if cookie was successfuly set to the given domain
            // (otherwise it was a Top-Level Domain)
            if (Cookie.get(name) == null || Cookie.get(name) != value) {
                // append "." to current domain
                domain = '.' + host;
                document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;
            }
        }
    },

    get: function (name) {
        window.nameEQ = name + "=";
        window.ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }

            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    erase: function (name) {
        Cookie.set(name, '', -1);
    },


};

window.isIPOrLocalHost = function(value) {
    return (
        isIpAddress(value) ||
        (value === 'localhost')
    )
};

window.isIpAddress = function (ipaddress) {
    return (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress))
};

/*
 * Javascript version of the number_format method of PHP
 */
window.number_format = function (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    let n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
};


/**
 * Element.closest() polyfill
 * https://developer.mozilla.org/en-US/docs/Web/API/Element/closest#Polyfill
 */
if (!Element.prototype.closest) {
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
    }
    Element.prototype.closest = function (s) {
        var el = this;
        var ancestor = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (ancestor.matches(s)) return ancestor;
            ancestor = ancestor.parentElement;
        } while (ancestor !== null);
        return null;
    };
}

window.Ajax = {

    //TODO: Can we create a prepare request function ??

    get: function(url, callback) {
        let xhr = new XMLHttpRequest();
        let token = document.querySelector('meta[name="csrf-token"]').content;

        xhr.open('get', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                callback(xhr);
            }
        };
        xhr.send();
    },

    post: function(url, data, callback) {
        let xhr = new XMLHttpRequest();
        let token = document.querySelector('meta[name="csrf-token"]').content;

        xhr.open('post', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', token);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                callback(xhr);
            }
        };
        xhr.send( JSON.stringify(data));
    }
};

/**
 * Capitalize the first letter of the string
 * @param string
 * @returns {string}
 */
window.capitalizeFirstLetter = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

/**
 * Convert snake case into camelcase
 *
 * @param string
 * @returns {*}
 */
function snakeToCamel(string){
    return string.replace(/(\-\w)/g, function(m){return m[1].toUpperCase();});
}

/**
 * Get the index of an element inside its parent
 */
function indexInParent(node) {
    window.children = node.parentNode.childNodes;
    let num = 0;
    for (let i = 0; i < children.length; i++) {
        if (children[i] == node) return num;
        if (children[i].nodeType == 1) num++;
    }
    return -1;
}

/**
 * For security reasons iOS Safari only allows document.execCommand('copy') for text within a contentEditable container.
 * The workaround is to detect iOS Safari and quickly toggle contentEditable before executing document.execCommand('copy').
 * Wheb input or textarea is READONLY you can prevent the unwanted popup with copy/paste functions and the input keyboard
 * Code from: https://stackoverflow.com/a/46981847
 */
function copyToClipboard(el) {

    // resolve the element
    el = (typeof el === 'string') ? document.querySelector(el) : el;

    // handle iOS as a special case
    if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {

        // save current contentEditable/readOnly status
        window.editable = el.contentEditable;
        window.readOnly = el.readOnly;

        // convert to editable with readonly to stop iOS keyboard opening
        el.contentEditable = true;
        el.readOnly = true;

        // create a selectable range
        var range = document.createRange();
        range.selectNodeContents(el);

        // select the range
        window.selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
        el.setSelectionRange(0, 999999);

        // restore contentEditable/readOnly to original state
        el.contentEditable = editable;
        el.readOnly = readOnly;
    }
    else {
        el.select();
    }

    // execute copy command
    document.execCommand('copy');
}