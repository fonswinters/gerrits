/* ==========================================================================
   Cookie handler
   - Primary usage for toggling the cookie message and/or switch
 ========================================================================== */

const CookieHandler = {

    cookieBar : null,
    cookieMessage : null,

    cookieFadeOutAnimationDuration : 400,
    acceptTracking : false,

    // Initialize cookie handler
    init : function()
    {
        // Bind cookie bar to cookieMessage
        CookieHandler.cookieMessage = document.querySelector('.js-cookie-bar');

        // Bind cookie bar to cookieBar
        CookieHandler.cookieBar = document.querySelector('.js-cookie-bar');

        // If isset init the functions for cookie bar
        if(isset(CookieHandler.cookieBar)){
            CookieHandler.initCookieBar();
        }

        // If either type of cookie has been found check if settings are defined
        if(isset(CookieHandler.cookieMessage)){
            CookieHandler.checkForCookieSettings();
        }

    },

    // Init the cookie bar actions
    initCookieBar: function(){
        const closeButton = CookieHandler.cookieBar.querySelector('.js-disable-cookie-bar');
        if(isset(closeButton)){
            closeButton.addEventListener('click', CookieHandler.closeCookieMessage);
        }
    },

    checkForCookieSettings: function(){
        if (Cookie.get('cookieMessage')) {
            CookieHandler.cookieMessage.classList.add('is-accepted');
            document.body.classList.add('is-cookies-accepted');
        }
        else {
            CookieHandler.cookieMessage.classList.remove('is-accepted');
            document.body.classList.remove('is-cookies-accepted');
        }
    },

    closeCookieMessage: function (){
        Cookie.set('cookieMessage', true, 90);
        CookieHandler.cookieMessage.classList.add('is-transitioning-out');
        document.body.classList.add('is-cookies-accepted');
    },

    setCookieSettings: function () {

        // Set tracking cookie or delete it if isset according to the desired settings
        if(CookieHandler.acceptTracking){
            Cookie.set('trackingCookieAccepted', 'true', 90);
        }
        else{
            if(Cookie.get('trackingCookieAccepted')){
                Cookie.erase('trackingCookieAccepted');
            }
        }

        CookieHandler.closeCookieMessage();

        // Reload after animation to automatically trigger the tracking after accepting it
        setTimeout(function(){
            location.reload()
        }, CookieHandler.cookieFadeOutAnimationDuration);
    },

};

CookieHandler.init();