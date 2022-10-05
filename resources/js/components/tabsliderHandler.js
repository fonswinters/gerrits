/* ==========================================================================
   Tabslider handler
   - Handles the tabslider component which has the proper classes.
 ========================================================================== */

const TabsliderHandler = {
    tabsliderList : null,
    tabslider : null,

    init: function () {
        TabsliderHandler.tabsliderList = document.querySelectorAll('.js-tabslider');
        const tabsliderListCount = TabsliderHandler.tabsliderList.length;

        if(isset(TabsliderHandler.tabsliderList) && tabsliderListCount !== 0){
            for(let i = 0; i < tabsliderListCount; i++){
                const tabslider = TabsliderHandler.tabsliderList[i];
                TabsliderHandler.initTabslider(tabslider);
            }
        }
    },

    initTabslider: function (tabslider) {
        const container = tabslider.querySelector('.js-tabslider-container');

        // Check if container element exists
        if (container == null) {
            console.error(`There is no container for the content tabs defined.`);
            return;
        }

        const triggers = tabslider.querySelectorAll('.js-tabslider-trigger');
        const tabs = container.querySelectorAll('.js-tabslider-content');

        // Check if number of triggers and tabs match
        if(triggers.length !== tabs.length) {
            console.error(`There are ${triggers.length} trigger buttons and ${tabs.length} tabs. These must match.`)
        }

        tabslider.addEventListener('click', function (event) {

            // Check if clicked on a step button (prev or next)
            if(event.target.closest('.js-tabslider-step')) {
                const dir = event.target.closest('.js-tabslider-step').getAttribute('data-step');
                TabsliderHandler.stepTabslider(dir, triggers, tabs);
            }

            // Bail if we didn't click on the trigger element
            if (!event.target.classList.contains('js-tabslider-trigger')) return;

            // Bail if already active
            if (event.target.classList.contains('is-active')) { return; }

            TabsliderHandler.toggleTabslider(event.target.dataset.tabId, triggers, tabs);

        });
    },

    toggleTabslider: function (tabId, triggers, tabs) {

        if(isset(triggers) && triggers.length > 0){

            // Loop through all tabs
            for(let i = 0; i < triggers.length; i++){
                const tabsliderTab = tabs[i];
                const tabsliderTrigger = triggers[i];

                tabsliderTrigger.classList.remove('is-active');
                tabsliderTrigger.tabIndex = 0;
                tabsliderTab.classList.remove('is-active');

                if (tabsliderTab.dataset.tabId === tabId) {
                    tabsliderTrigger.classList.add('is-active');
                    tabsliderTrigger.tabIndex = -1;
                    tabsliderTab.classList.add('is-active');
                }
            }
        }
    },

    stepTabslider: function (dir, triggers, tabs) {
        const triggerCount = triggers.length;
        let activeId = 1;
        let newId = 0;

        if(isset(triggers) && triggerCount > 0){

            // Loop through all tabs
            for(let i = 0; i < triggerCount; i++){
                const tabsliderTab = tabs[i];
                const tabsliderTrigger = triggers[i];

                // Set activeId
                if(tabsliderTab.classList.contains('is-active')){
                    activeId = Number(tabsliderTab.getAttribute('data-tab-id'));
                }

                // Check what next Id will be
                if(dir === 'next') {
                    if (activeId === triggerCount) {
                        newId = 0;
                    } else {
                        newId = activeId;
                    }
                } else if (dir === 'prev') {
                    if (activeId === 1) {
                        newId = triggerCount - 1;
                    } else {
                        newId = activeId - 2;
                    }
                }

                // Remove active class
                tabsliderTrigger.tabIndex = 0;
                tabsliderTab.classList.remove('is-active');
                tabsliderTrigger.classList.remove('is-active');
            }

            triggers[newId].tabIndex = -1;
            triggers[newId].classList.add('is-active');
            tabs[newId].classList.add('is-active');

        }
    }
};

TabsliderHandler.init();