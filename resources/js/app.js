/** ==========================================================================
 *  Dependencies
 *  =========================================================================*/

// We first import Sentry and trigger it if the sentry dsn is defined
// Because then all errors are logged
import * as Sentry from '@sentry/browser';
if(window.sentry_dsn !== undefined) Sentry.init({ dsn: window.sentry_dsn });

/**
 * External
 */
require('hammerjs');
require('objectFitPolyfill');


/** ==========================================================================
 *  Global functionalities
 *  =========================================================================*/

require('./global/helpers'); // Should be first
require('./global/browserHandler');
require('./global/scrollHandler');


/** ==========================================================================
 *  Component functionalities
 *  =========================================================================*/


require('./components/chocolateFactory');
require('./components/fileUploadHandler');
require('./components/cookieHandler');
// require('./components/InputHandler');
require('./components/mapsHandler');
require('./components/overlayMenuHandler');
require('./components/sliderHandler');
require('./components/scrollToHandler');
require('./components/tabsliderHandler');
require('./components/toggleHandler');
require('./components/youtubeHandler');


/** ==========================================================================
 *  Progressive Web App | Service Worker
 *  =========================================================================*/

window.addEventListener("load", () => {
    if ("serviceWorker" in navigator) {
        navigator.serviceWorker.register("/sw.js");
    }
});