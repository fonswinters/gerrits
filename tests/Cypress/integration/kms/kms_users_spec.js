import faker from 'faker'

/// <reference types="Cypress" />
describe('KMS Users', function() {
    it('Cannot create a user without entering data', () =>{
     cy.kms_login('kms');
        cy.get('[data-test="KMS gebruikers"]').click();
        cy.get('[data-test="add_button"]').click();

        cy.get('[data-test="save_button"]').click();
        cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '1'); //Data type 1 means error.
    });

    it('Create a user with e-mail, password and first name and test login', () =>{
        //Login and open the KMS users section
        cy.kms_login('kms');
        cy.get('[data-test="KMS gebruikers"]').click();
        cy.get('[data-test="add_button"]').click();

        //Generate credential data            cy.getCsrfToken((csrfToken) => {
        let email = faker.internet.email();
        let password = faker.internet.password()+'_!A1';
        let firstName = faker.name.firstName();

        //Create the user
        cy.get('[data-test="TextField-first_name"]').type(firstName);
        cy.get('[data-test="TextField-email"]').type(email);
        cy.get('[data-test="Password-password-1"]').type(password);
        cy.wait(150);
        cy.get('[data-test="Password-password-2"]').type(password);
        cy.wait(150);
        cy.get('[data-test="save_button"]').click({force: true});
        cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.
        cy.get('[data-test="entity_tab_algemeen"]').click();
        cy.get('[data-test="TextField-first_name"]').should('have.value', firstName);
        cy.get('[data-test="TextField-email"]').should('have.value', email);

        //Logout and test login
        cy.get('[data-test="log-out"]').click();
        cy.get('[data-test="email"]').type(email);
        cy.get('[data-test="password"]').type(password);
        cy.get('[data-test="submit"]').click();
        cy.get('[data-test="KMS gebruikers"]').should('exist');
    });

    it('Should be possible to search for a user', () => {
        cy.kms_login('kms');
        cy.get('[data-test="KMS gebruikers"]').click();

        //Get a user to search for first
        cy.getCsrfToken((csrfToken) => {
            cy.request(
                'testapi/v1/kms_users/show/',
            ).then((response) => {
                let user = response.body.data;
                cy.get('[data-test="entity_search_input"]').type(user.email);
                cy.get('[data-test="search-result-counter"]').contains('1').should('exist');
            });
        });
    });
});
