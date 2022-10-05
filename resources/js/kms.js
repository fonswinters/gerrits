import { bootKms } from '@KMSCore/js/kms'
import Initializer from "@KMSCore/js/entity/initializer";

import timepickerController from "@resources/js/attributes/timepickerController";

document.addEventListener('DOMContentLoaded', function(){
    bootKms();

    let attributeInitializer = new Initializer('#entity-form');

    attributeInitializer.bindSelectorToCallback('.js-timepicker', function(initializer, timePickerAttributeElement){
        let key = timePickerAttributeElement.dataset.key;
        let lang = timePickerAttributeElement.dataset.language;
        let hoursStep = timePickerAttributeElement.dataset.hoursStep;
        let minutesStep = timePickerAttributeElement.dataset.minutesStep;
        let timeFormat = timePickerAttributeElement.dataset.timeFormat;

        new timepickerController(key, lang, hoursStep, minutesStep, timeFormat);
    });
});