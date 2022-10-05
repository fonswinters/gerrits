Cypress.Commands.add('jqueryUiSelectMenuSelect', (textToSelect, attributeKey, options = {}) => {
    cy.document().then(function(document) {
        if(!attributeKey) {
            cy.log('Not selecting the text "' + textToSelect + '" because no select menu attribute key was given');
            return;
        }

        if(!textToSelect) {
            cy.log('Not selecting empty text in the select with attribute key: '+attributeKey);
            return;
        }

        cy.get('[data-test='+attributeKey+']').click();
        //The mouseover trigger is needed to make the click work. Else the select menu won't select the option.
        cy.get('li[data-test^="'+attributeKey+'-"]').contains(textToSelect).parent().trigger('mouseover').click();
    })
});