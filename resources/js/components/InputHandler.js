/* ==========================================================================
    Input handler

    This handler can be remove when the css support for :focus-within is better
 ========================================================================== */

const InputHandler = {

    inputFields: null,

    init : function()
    {
        // Get the form inputs
        InputHandler.inputFields = document.querySelectorAll('.js-form-input');

        const inputLength = InputHandler.inputFields.length;
        for(let i = 0; i < inputLength; i++){

            const input = InputHandler.inputFields[i];
            const inputId = input.getAttribute('id');

            // Check if there is an id on the input
            if(isset(inputId)){

                const inputLabel = document.querySelector('.js-form-label[for="'+inputId+'"]');

                // // And if there is a belonging label
                if(isset(inputLabel)){
                    InputHandler.addEventListenersToInput(input);
                }
            }

        }
    },

    addEventListenersToInput : function(input)
    {
        // Add the fill class and trigger removeFocus so the input will be automatic be marked as filled or not
        parentElement = input.parentNode;
        parentElement.classList.add('is-filled');
        InputHandler.removeFocus(input);

        input.addEventListener('focus', function () {

            // We loop through the input elements because autocomplete (on chrome) triggers al the focus elements but none focus out
            const inputLength = InputHandler.inputFields.length;
            for(let i = 0; i < inputLength; i++){
                const loopedInput = InputHandler.inputFields[i];

                // Trigger add focus on this focused element
                if(loopedInput === input){
                    InputHandler.addFocus(this);
                }
                // Remove focus on all other input/textarea elements
                else{
                    InputHandler.removeFocus(loopedInput);
                }
            }

        });

        input.addEventListener('focusout', function () {
            InputHandler.removeFocus(this);
        });

    },

    addFocus  : function (input) {
        if(isset(input)) {

            const inputId = input.getAttribute('id');

            parentElement = input.parentNode;
            parentElement.classList.add('is-focused');
            parentElement.classList.add('is-filled');

            // If there is a error message remove it on focus
            const errorMessage = document.querySelector('form .error-message#' + inputId + '-error');
            if (isset(errorMessage)) {
                errorMessage.classList.add('fade-out');
            }
        }

    },

    // Reset the label location only if the input is empty
    removeFocus : function (input) {
        if(isset(input)){

            const inputValue = input.value;

            parentElement = input.parentNode;
            parentElement.classList.remove('is-focused');

            if(!isset(inputValue) || inputValue === ''){
                parentElement.classList.remove('is-filled');
            }
        }
    },

};

InputHandler.init();