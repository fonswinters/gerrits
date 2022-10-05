/* ==========================================================================
   Scroll handler
   - Handler the objects which are bind on scroll events or visible in viewport
 ========================================================================== */

const ScrollHandler = {

    // Variables for debounce and throttle effects
    time: Date.now(),
    timeout: null,
    waitThrottle: 1000,
    waitDebounce: 300,

    // Variables for scroll direction
    lastScrollTopPosition: 0,
    scrollDirectionDown: true,
    scrollDirectionUp: false,

    //Initialisation
    init: function () {

        // Trigger start on start up
        ScrollHandler.triggerOnInit();

        // Throttle scroll
        window.addEventListener('scroll', function () {
            if ((ScrollHandler.time + ScrollHandler.waitThrottle - Date.now()) < 0) {
                ScrollHandler.triggerThrottle();
                ScrollHandler.time = Date.now();
            }
        });

        // Smooth scroll
        window.addEventListener('scroll', function () {
            ScrollHandler.triggerSmooth();
        });

        // Debounce scroll
        window.addEventListener('scroll', function () {
            if(isset(ScrollHandler.timeout)) clearTimeout(ScrollHandler.timeout);
            ScrollHandler.timeout = setTimeout(ScrollHandler.triggerDebounce, ScrollHandler.waitDebounce);
        });
    },

    // Trigger on start up
    triggerOnInit: function () {
        ScrollHandler.triggerElementInViewportAnimation();
    },

    // Trigger scroll functions with throttle (preferred)
    triggerThrottle: function () {
        // console.log('Throttled scroll');
        ScrollHandler.triggerElementInViewportAnimation();
    },

    // Trigger scroll on debounce
    triggerDebounce: function () {
        // console.log('Debounce scroll');
    },

    // Trigger scroll on the flight
    triggerSmooth: function () {
        // console.log('Smooth scroll');
        ScrollHandler.detectScrollDirection();
        ScrollHandler.toggleStickyHeader();
    },

    // Detect if part of a given element is visible in the viewport
    // El must be a node element
    detectIfElementIsPartlyInViewport: function(el)
    {
        if(isset(el)){

            const rect = el.getBoundingClientRect();
            // DOMRect { x: 8, y: 8, width: 100, height: 100, top: 8, right: 108, bottom: 108, left: 8 }
            const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
            const windowWidth = (window.innerWidth || document.documentElement.clientWidth);

            const verticalInView = (rect.top <= (windowHeight)) && ((rect.top + rect.height) >= 0);
            const horizontalInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= 0);

            return (verticalInView && horizontalInView);
        }
    },

    // Detect if a given element is fully visible in the viewport
    // El must be a node element
    detectIfElementIsFullyInViewport: function(el)
    {
        if(isset(el)){
            const rect = el.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.bottom <= window.innerHeight
            );
        }
    },

    detectScrollDirection: function () {
        const scrollTopPosition = window.pageYOffset || document.documentElement.scrollTop; // Credits: "https://github.com/qeremy/so/blob/master/so.dom.js#L426"
        if (scrollTopPosition >= ScrollHandler.lastScrollTopPosition){
            ScrollHandler.scrollDirectionDown = true;
            ScrollHandler.scrollDirectionUp = false;
        } else {
            ScrollHandler.scrollDirectionDown = false;
            ScrollHandler.scrollDirectionUp = true;
        }
        ScrollHandler.lastScrollTopPosition = scrollTopPosition;
    },

    // Trigger animation on elements that have 'element-in-viewport' and that are in the viewport
    // These animation can only be triggered once, if you want more then that you should write an specific function for this
    triggerElementInViewportAnimation: function () {
        const elements = document.querySelectorAll('.element-in-viewport');
        const elementsLength = elements.length;

        for(let e = 0; e < elementsLength; e++){

            const element = elements[e];
            if(ScrollHandler.detectIfElementIsPartlyInViewport(element)){
                element.classList.remove('element-in-viewport');
            }
        }

    },

    // ------------------------------ CUSTOM SCROLL HANDLERS ------------------------------------

    // Hide or show sticky navigation when header isn't visible
    toggleStickyHeader: function () {
        const stickyHeader = document.querySelector('.js-sticky-header');
        const visibleStickyHeaderClass = 'is-sticky-header-visible';
        const subnavList = document.querySelectorAll('.js-subnav');
        var subNavIsVisible = false;
        stickyHeader.hidden = false;

        for (let i = 0; i < subnavList.length; i++) {
            if (window.getComputedStyle(subnavList[i], null).visibility == "visible") {
                return subNavIsVisible = true;
            }
        }

        if(isset(stickyHeader) && !subNavIsVisible){
            // Hide when scrolling DOWN (OR within offset)
            if(ScrollHandler.scrollDirectionDown || ScrollHandler.lastScrollTopPosition < 110){
                document.body.classList.remove(visibleStickyHeaderClass);
            }
            // Show when scrolling UP and outside of offset
            else {
                document.body.classList.add(visibleStickyHeaderClass);
            }
        }
    },

};

ScrollHandler.init();