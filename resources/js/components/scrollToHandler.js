/* ==========================================================================
    Scroll To Click handler
 ========================================================================== */

const ScrollToHandler = {

    // Animation settings
    offset : 60, //pixel
    duration : 1400, //ms

    // Animation variables
    body : null,
    start : 0,
    change : 0,
    currentTime : 0,
    allowAnimation : false,
    scrollToAnimation : null,

    // Watch the EasingFunction helper for the available methods
    easing: 'easeInOutQuad',

    init : function(){

        const anchorLinks = document.querySelectorAll('.js-scroll-to-target');
        const anchorLinksAmount = anchorLinks.length;

        for(let i = 0; i < anchorLinksAmount; i++) {

            const anchorLink = anchorLinks[i];

            anchorLink.addEventListener('click', function (event) {
                ScrollToHandler.prepareScrollTo(this.getAttribute('href'));
                event.preventDefault();
            });
        }
    },

    /**
     * Prepare the Handler for the animation
     */
    prepareScrollTo : function(elementId){

        // Get the scroll to element
        elementId = elementId.substr(elementId.indexOf('#')+1);
        const scrollToElement = document.getElementById(elementId);
        if(scrollToElement == null) return;
        const scrollToElementPosition = scrollToElement.getBoundingClientRect();

        // Reset or define the Handler variables
        ScrollToHandler.body = document.documentElement;
        ScrollToHandler.start = Math.max(ScrollToHandler.body.scrollTop, document.body.scrollTop, window.pageYOffset); //Use Math.max because safari doesn't support document.documentElement.scrollTop
        ScrollToHandler.change = ((scrollToElementPosition.top + ScrollToHandler.start) - ScrollToHandler.start) - ScrollToHandler.offset;
        ScrollToHandler.startTime = 'now' in window.performance ? performance.now() : new Date().getTime();
        ScrollToHandler.allowAnimation = true;

        // Trigger animation
        scrollToAnimation = requestAnimationFrame(ScrollToHandler.animateScroll);

        // Stop on scroll
        window.addEventListener('mousedown', ScrollToHandler.abortScrollAnimation);
        window.addEventListener('wheel', ScrollToHandler.abortScrollAnimation);
        window.addEventListener('DOMMouseScroll', ScrollToHandler.abortScrollAnimation);
        window.addEventListener('mousewheel', ScrollToHandler.abortScrollAnimation);
        window.addEventListener('keyup', ScrollToHandler.abortScrollAnimation);
        window.addEventListener('touchmove', ScrollToHandler.abortScrollAnimation);

    },

    /*
     * Animate the scroll position
     */
    animateScroll : function (timestamp) {

        // Calculate progress from 0 - 1
        let progress = Math.min(1, (timestamp -  ScrollToHandler.startTime) / ScrollToHandler.duration);
        if(progress < 0) progress = 0;

        // Convert progress with easing function
        progress = EasingFunctions[ScrollToHandler.easing](progress);

        const newScrollTop = ScrollToHandler.start + ( ScrollToHandler.change * progress );

        ScrollToHandler.body.scrollTop = newScrollTop;
        if( ScrollToHandler.body.scrollTop === 0 ) document.body.scrollTop = newScrollTop; // Safari doesn't support so if ScrollToHandler.body.scrollTop is 0 force the scroll position through document.body.scrollTop

        if(progress < 1 && ScrollToHandler.allowAnimation){
            scrollToAnimation = requestAnimationFrame(ScrollToHandler.animateScroll);
        }

    },

    /*
     * Abort the scroll animation
     */
    abortScrollAnimation : function (event) {
        ScrollToHandler.allowAnimation = false;
        cancelAnimationFrame(ScrollToHandler.scrollToAnimation);
    }

};

ScrollToHandler.init();