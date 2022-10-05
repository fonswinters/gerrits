/* ==========================================================================
   Navigation handler
   - Handles the showing and hiding of the overlay-menu component which has the proper classes.
 ========================================================================== */

const bodyScrollLock = require('body-scroll-lock');

const OverlayMenuHandler = {

    // init: function () {
    //     const overlayMenuTrigger = document.querySelector('.js-overlay-menu-trigger');
    //     OverlayMenuHandler.initOverlayMenu(overlayMenuTrigger);
    // },

    init: function () {

        const overlayMenuTriggerList = document.querySelectorAll('.js-overlay-menu-trigger');
        const overlayMenuTriggerListCount = overlayMenuTriggerList.length;

        if(isset(overlayMenuTriggerList) && overlayMenuTriggerListCount !== 0){
            for(let i = 0; i < overlayMenuTriggerListCount; i++){
                const overlayMenuTrigger = overlayMenuTriggerList[i];
                OverlayMenuHandler.initOverlayMenu(overlayMenuTrigger);
            }
        }
    },

    initOverlayMenu: function (overlayMenuTrigger) {
        overlayMenuTrigger.addEventListener('click', OverlayMenuHandler.toggleOverlayMenu, false);
    },

    toggleOverlayMenu: function (event) {
        const overlayMenuNav = document.querySelector('.js-overlay-menu');
        // const overlayMenuBody = overlayMenu.querySelector('.js-overlay-menu-persist-scrolling');

        // Bail if overlayMenu doesn't exist
        if (!overlayMenuNav) return;

        // Clear previously locked scroll on the body
        bodyScrollLock.enableBodyScroll(overlayMenuNav);

        // If the overlayMenu is already active, collapse it and quit
        if (document.body.classList.contains('is-overlay-menu-active')) {
            overlayMenuNav.querySelectorAll('[tabindex="0"]').forEach(function(elem) {
                elem.tabIndex = -1;
            });

            document.body.classList.remove('is-overlay-menu-active');
            return;
        }

        // Lock scrolling on the body
        bodyScrollLock.disableBodyScroll(overlayMenuNav);

        overlayMenuNav.querySelectorAll('[tabindex="-1"]').forEach(function(elem) {
            elem.tabIndex = 0;
        });

        // Toggle active overlayMenu by setting a class on the body
        document.body.classList.toggle('is-overlay-menu-active');
        overlayMenuNav.hidden = false;
    }
};

OverlayMenuHandler.init();