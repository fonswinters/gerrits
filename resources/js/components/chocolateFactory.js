/* ==========================================================================
 | Prevent Bots handler
 |
 | We named this chocolate factory and belonging confusing js hooks
 | to prevent smart bots from blocking these variable or functions.
 |
 ========================================================================== */
import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const ChocolateFactory = {

    bannedVisitor: [],

    /** Get all the chocolate factory and start
     *  Looping through those start flashing tickets
     */
    enter: function()
    {

        const chocolateFactories = document.querySelectorAll('.js-chocolate-factory');

        if(isset(chocolateFactories)){

            const amountOfChocolateFactories = chocolateFactories.length;
            for(let i = 0; i < amountOfChocolateFactories; i++){

                const ChocolateBar = chocolateFactories[i];
                ChocolateFactory._flashTicket(ChocolateBar);
            }
        }
    },

    /**
     * If factory has a golden ticket
     * Then we can make them go on the tour if there has been click on the ticket
     *
     * @param chocolateBar
     * @private
     */
    _flashTicket: function (chocolateBar) {

        // Try get the ticket from the chocolateBar
        const goldenTicket = chocolateBar.querySelector('.js-golden-ticket');

        // Only continue if chocolate bar has a golden ticket
        if(isset(goldenTicket)){

            goldenTicket.addEventListener('click', function () {
                ChocolateFactory.enjoyTheTour(chocolateBar);
            });
        }
        else{
            console.log('Too bad, no golden tickets has been found.');
        }
    },

    /**
     * Start the tour through the factory
     * And get the names and properties of the members of the group
     * If there are members in the group of course
     *
     * @param chocolateFactory
     */
    enjoyTheTour(chocolateFactory){

        // Ask for the tour group
        const tourGroup = chocolateFactory.querySelectorAll('input, textarea');

        if(isset(tourGroup)){

            // For each visitor we want a belonging Oompa Loompa
            let oompaLoompas = {};
            let amountOfOompaLoompas = 0;



            const tourGroupSize = tourGroup.length;
            const chocolateGod = new FormData();
            for(let i = 0; i < tourGroupSize; i++){
                // Get the visitor from the group
                const visitor = tourGroup[i];

                // Ask for its name
                const visitorName = visitor.getAttribute('name');

                // Check if the visitor is banned
                if( ChocolateFactory._isVisitorBanned(visitorName)) continue;

                if(!visitor.files)
                {
                    chocolateGod.set(visitorName, ChocolateFactory._getArrangement(visitor));
                }
                else
                {
                    for (let oompaLoompaFiles = 0; oompaLoompaFiles < visitor.files.length; oompaLoompaFiles++)
                    {
                        chocolateGod.append('files[]', visitor.files[oompaLoompaFiles]);
                    }
                }


                amountOfOompaLoompas++;
            }

            // Check for insurance that there are oompa loompas
            if(amountOfOompaLoompas === 0) return;

            // Send submit request
            ChocolateFactory._finishTour(chocolateGod, chocolateFactory);
        }
        else{
            console.log('Too bad, no members to visit this factory')
        }
    },


    /**
     * Check if the visitor name isn't allow
     *
     * @param visitorName
     * @returns {boolean}
     * @private
     */
    _isVisitorBanned: function(visitorName){
        if(ChocolateFactory.bannedVisitor.indexOf(visitorName) !== -1){
            return true;
        }
        return false;
    },

    /**
     * Most get visitor have a normal arrangement
     * But sometimes there are special cases
     * Like a Selector or checkbox
     *
     * @param visitor
     * @returns {*}
     * @private
     */
    _getArrangement: function(visitor)
    {
        if(visitor.type === 'checkbox') {
            return visitor.checked;
        }

        const visitorType = visitor.nodeName;

        switch (visitorType) {
            default:
                return visitor.value;
        }
    },

    /**
     * Finish the tour2
     * If successful show thanks message
     * Or show defined error message or fallback
     *
     * @param group
     * @param chocolateFactory
     * @returns {*|void}
     * @private
     */
    _finishTour: function(group, chocolateFactory)
    {

        group.set('_willie', 'wonka')
        const gate = chocolateFactory.action.replace('send', 'process');

        axios({
            method: 'post',
            url: gate,
            data: group,
            headers: {'Content-Type': 'multipart/form-data' },
        }).then(function (response){
            // TODO: Check response status code and body before redirecting
            return ChocolateFactory._thanksForVisiting(response.data.redirectUrl);
        }).catch(function (error){
            if(Object.prototype.hasOwnProperty.call(error, 'response') &&
               Object.prototype.hasOwnProperty.call(error.response, 'data') &&
               Object.prototype.hasOwnProperty.call(error.response.data, 'errors')) {

                return ChocolateFactory._giveFeedbackToMembers(error.response.data.errors, chocolateFactory);
            } else {
                console.error('Could not post to: "' + gate + '"');
            }
        })


    },


    /**
     * Add willie wonka to the group
     * Ps... it actually the secret code!
     *
     * @param group
     * @returns {*}
     * @private
     */
    _addWillieWonka: function(group){
        group._willie = 'wonka';
        return group;
    },

    /**
     * Add the feedback to the desired area.
     * Most likely to the visitor directly, but sometime to the factory desired feedback area
     *
     * @param errors
     * @param chocolateFactory
     * @private
     */
    _giveFeedbackToMembers: function(errors, chocolateFactory){

        // Grab the factory feedback area
        const feedbackArea = chocolateFactory.querySelector('.js-error-area');

        // Clear the current html
        if(isset(feedbackArea)) feedbackArea.innerHTML = '';

        // Clear the previous marked jackets
        ChocolateFactory._clearPreviousMarkedJackets(chocolateFactory);

        Object.keys(errors).forEach(function(visitor) {

            let jacket = null;
            let visitorFeedbackArea = null;


            // Honey elements doesn't has a accessible element
            if(visitor !== '_honey' && visitor !== '_secretCode' && visitor.substring(0,5) !== 'files') {

                // Grab the visitor
                const visitorNode = chocolateFactory.querySelector('#' + visitor);

                // Find the jacket of a visitor
                jacket = ChocolateFactory._grabVisitorJacket(visitorNode);

                if(isset(jacket)) {

                    // If found get the desired area
                    visitorFeedbackArea = jacket.querySelector('.js-form-group-error');

                    // Clear the current html
                    if(isset(visitorFeedbackArea)) visitorFeedbackArea.innerHTML = '';
                }
            }

            // Get the feedback for this visitor
            const visitorFeedback = errors[visitor];

            // Spit out each line
            const visitorFeedbackAmount = visitorFeedback.length;
            for(let i = 0; i < visitorFeedbackAmount; i++){
                const visitorFeedbackLine = visitorFeedback[i];

                // Honey elements doesn't has a accessible element area
                if(visitor !== '_honey' && visitor !== '_secretCode') {

                    // Mark the jacket
                    if(isset(jacket)) jacket.classList.add('has-error');

                    // Append feedback to visitor feedback area if defined
                    if(isset(visitorFeedbackArea) && visitorFeedbackLine.length > 0)
                    {
                        let currentFeedbackArea = visitorFeedbackArea.innerHTML;
                        currentFeedbackArea += '<span>' + capitalizeFirstLetter(visitorFeedbackLine) + '</span>';
                        visitorFeedbackArea.innerHTML = currentFeedbackArea;
                    }
                }

                // Append feedback to factory feedback area if defined
                if(isset(feedbackArea) && visitorFeedbackLine.length > 0){
                    let currentFeedbackArea = feedbackArea.innerHTML;
                    currentFeedbackArea += '<li>' + capitalizeFirstLetter(visitorFeedbackLine) + '</li>';
                    feedbackArea.innerHTML = currentFeedbackArea;
                }
            }
        });
    },

    /**
     * Clear the previous marked jackets
     *
     * @param chocolateFactory
     * @private
     */
    _clearPreviousMarkedJackets(chocolateFactory) {

        const markedJackets = chocolateFactory.querySelectorAll('.has-error');
        const markedJacketsAmount = markedJackets.length;

        for(let i = 0; i < markedJacketsAmount; i++){
            markedJackets[i].classList.remove('has-error');
        }
    },

    /**
     * Grab the jacket of the visitor
     *
     * @param visitor
     * @returns {null|*|(() => (Node | null))|ActiveX.IXMLDOMNode|(Node & ParentNode)}
     * @private
     */
    _grabVisitorJacket: function(visitor){

        // Check if visitor is defined
        if (!isset(visitor)) return null;

        // Do loop settings
        let currentLayer = visitor;
        let safetyBreak = 0;

        // Grab the next layer till it is the jacket (or safetyBreak has been reached
        do {
            safetyBreak++;
            currentLayer = currentLayer.parentNode;
            if(currentLayer.classList.contains('js-form-group')) return currentLayer;
        } while (currentLayer.tagName !== 'BODY' && safetyBreak <= 10);

        return null;
    },

    /**
     * Redirect the visitor to the thanks page
     *
     * @param nextStop
     * @private
     */
    _thanksForVisiting: function (nextStop) {
        window.location = nextStop;
    },

    /**
     * Unknown error occurred, log the error
     *
     * @param chocolateFactory
     * @private
     */
    _unknownGapInFactory: function (chocolateFactory) {
        console.log(chocolateFactory);
        //console.log('ChocolateFactory: Unkown Error');
    },
};

ChocolateFactory.enter();