/* ==========================================================================
   Accordion handler
   - Handles the accordion component which has the proper classes.
 ========================================================================== */

const ToggleHandler = {

    toggleClass : 'js-toggle',
    toggleSwitchClass : 'js-toggle-switch',
    toggleContentClass : 'js-toggle-content',


    init: function () {

        const toggleList = document.querySelectorAll('.'+ToggleHandler.toggleClass);
        const toggleListCount = toggleList.length;

        if(isset(toggleList) && toggleListCount !== 0){
            for(let i = 0; i < toggleListCount; i++){
                const toggle = toggleList[i];
                ToggleHandler.initToggle(toggle);
            }
        }
    },

    initToggle: function (toggle) {

        /*
        * Set "pointer events: none" on all direct children of the toggle
        * Because we don't want clicks on them to register, only on the parent toggle element
        */
        const toggleList = toggle.querySelectorAll('.'+ToggleHandler.toggleSwitchClass);
        for (let i = 0; i < toggleList.length; i++) {
            const toggleItem = toggleList[i];

            for (let j = 0; j < toggleItem.children.length; j++) {
                const toggleChild = toggleItem.children[j];

                toggleChild.style.pointerEvents = "none";
            }

        }
        toggle.addEventListener('click', ToggleHandler.activateToggle, false);
    },

    activateToggle: function (event) {
        const item = event.target.parentNode;
        const itemList = item.parentNode.children;

        // Bail if we didn't click on the toggle element
        if (!event.target.classList.contains(ToggleHandler.toggleSwitchClass)) return;

        // Check if content element exists
        if (!item.querySelector('.'+ToggleHandler.toggleContentClass)) return;

        // Prevent default link behavior
        event.preventDefault();

        // Toggle the active class
        item.classList.toggle('is-active');
    }
};

ToggleHandler.init();