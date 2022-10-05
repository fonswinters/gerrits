// / <reference types="Cypress" />
import faker from 'faker'

describe('Posts', function() {
    it('Should be possible to create a post', function() {
        cy.kms_login('kms/posts');
        cy.getCsrfToken((csrfToken) => {
            let postName = 'Cypress test post '+faker.random.number({min: 10000, max: 99999});

            //Click the add button to make a new post
            cy.log('Making a new post');
            cy.get('[data-test="add_button"]').click();

            //Disable the active button
            cy.log('Disabling the active toggle');
            cy.get('[data-test="OnOff-active_toggle"]').click();
            cy.get('[data-test="OnOff-active"]').should('have.value', '0');

            //Set a date
            cy.log('Setting the date and time');
            cy.get('[data-test="DatePicker-date_date"]').type('{backspace}{backspace}{backspace}{backspace}{backspace}{backspace}{backspace}{backspace}{backspace}{backspace}'); //Clear date
            cy.get('[data-test="DatePicker-date_date"]').type('02/10/2017');
            cy.get('[data-test="DatePicker-date_date"]').type('{enter}');

            cy.log('Setting a dutch title and saving the post');
            cy.get('[data-test="entity_tab_nl"]').click();
            cy.get('[data-test="TextField-name-nl"]').type(postName);
            cy.get('[data-test="save_button"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success

            cy.log('Verify the post');
            cy.get('[data-test="entity_tab_algemeen"]').click();
            cy.get('[data-test="OnOff-active"]').should('have.value', '0');
            cy.get('[data-test="DatePicker-date_date"]').should('have.value', '02/10/2017');

            cy.log('Delete the post');
            cy.get('[data-test="entity_search_input"]').type(postName);
            cy.get('[data-test="search-result-counter"]').contains('1').should('exist');
            cy.get('[data-test="found_search_item"]').click();
            cy.get('[data-test="entity_header"]').contains(postName).should('exist');
            cy.get('[data-test="delete_button"]').click();
            cy.get('[data-test="confirmation_confirm"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '3'); //Data type 3 means general ok
        })
    });
});