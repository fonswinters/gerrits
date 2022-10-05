//TODO Refactor datepicker to be able to remove the block below. The datepicker attribute has an inline script which uses $
import $ from 'jquery';
import 'jquery-ui/ui/widgets/spinner';

export default class DatepickerController {
    constructor(attributeKey, language = null, hoursStep, minutesStep, timeFormat) {
        //Setup all variables
        this.attributeKey = attributeKey;
        this.timeFormat = timeFormat;

        this.hour = 0;
        this.minute = 0;
        this.twelveOrTwentyFour = this.timeFormat;
        this.itIsPm = true;
        this.hoursStep = hoursStep;
        this.minutesStep = minutesStep;

        //Define associated inputs
        this.realInput = document.getElementById(this.attributeKey);
        this.$hoursInput = $('#'+this.attributeKey+"_time_hours");
        this.$minutesInput = $('#'+this.attributeKey+"_time_minutes");

        this.$hoursInput.on('input', this.forceToNumber);
        this.$minutesInput.on('input', this.forceToNumber);

        this.setup();
        this.initialize();
    }

    setup() {
        $.widget("ui.timespinner", $.ui.spinner, {
            _format: function (value) {
                if (value <= 9) return ('0' + value);
                else return value;
            },
        });


            //DATE PICKER initialisation
        let self = this;


        //TIME PICKER - HOURS
        this.$hoursInput.timespinner({
            step: this.hoursStep,
            min: 0,
            max: 23,
            change: function( event, ui ) {
                let showValue = $(this).timespinner("value");
                if(showValue <= 9) return $(this).timespinner("value", ('0'+showValue));
            },
        });

        //TIME PICKER - MINUTES
        this.$minutesInput.timespinner({
            step: this.minutesStep,
            min: 0,
            max: 59,
            change: function( event, ui ) {
                let showValue = $(this).timespinner("value");
                if(showValue <= 9) return $(this).timespinner("value", ('0'+showValue));
            }
        });

        // Update input time when losing focus of the hour or minute input field
        $(this.$hoursInput).focusout(function(){
            self.timeChanged();
        });
        $(this.$minutesInput).focusout(function(){
            self.timeChanged();
        });
    }

    //Helper functions
    /**
     * Check if it am or pm and reflect that in the itIsPm boolean
     */
    checkAmPm(ui) {
        if (this.twelveOrTwentyFour === 12) {
            this.itIsPm = !this.itIsPm;
        } else {
            this.itIsPm = (ui.value >= 12 && ui.value <= 24);
        }
    }

    /**
     * Time has changed
     */
    timeChanged() {
        this.hour = this.$hoursInput.timespinner('value') || 0;
        this.minute = this.$minutesInput.timespinner('value') || 0;
        this.updateRealValue();
    }


    /**
     * Updates the hidden input that holds the value that is pushed to the database
     */
    updateRealValue() {
        //Make sure our variables are set in any case
        if (this.hour === undefined) this.hour = 0;
        if (this.minute === undefined) this.minute = 0;

        // console.log('hours: '+ hour);
        // console.log('minutes: '+ minute);

        this.realInput.setAttribute('value', JSON.stringify({
            hour: this.hour,
            minute: this.minute,
            second: 0
        }));

        // console.log('Updated real input: ' + realInput.value);
    }

    /**
     * Set the date picker and time spinners on to the date in the real input field
     */
    initialize() {
        //Retrieve real input value and validate that it has all properties we need
        if(this.realInput.value.trim() === ""){
            this.updateRealValue();
        }
        let date = this.getDateObjectFromInput(this.realInput);

        //Update the real input value with the correct data
        // console.log('Initializing hour spinner with value: '+realInputValue.hour);
        this.$hoursInput.timespinner('value', date.getHours());

        // console.log('Initializing minute spinner with value: '+realInputValue.minute);
        this.$minutesInput.timespinner('value', date.getMinutes());
    }

    /**
     * Looks at an input value and if it is a json object formatted like below, returns a date with those values in the json.
     * Else it wil log an error and set the date to now
     */
    getDateObjectFromInput(inputElement)
    {
        let inputElementValue = inputElement.value;
        let date = new Date();
        if (inputElementValue) {
            // console.log('initializing with real input value: ' + inputElementValue);
            let json = JSON.parse(inputElementValue);

            if(json.hasOwnProperty('hour') && json.hasOwnProperty('minute') && json.hasOwnProperty('second'))
            {
                date = new Date();
                date.setHours(json.hour);
                date.setMinutes(json.minute);
                date.setSeconds(json.second);
            } else {
                console.error('The input element did not have a json value correctly specified. It should have all the properties: hour, minute, second. but did not have them all. Returning a date object of "now"');
            }
        } else {
            console.warn('The input element did not have json value specified. Returning a date object of "now"');
        }

        return date;
    }
}