/// <reference types="Cypress" />

import faker from 'faker'
describe('KMS Login and password reset', function() {
    it('Shows email and password required errors', function() {
        cy.clearCookies();
        cy.visit('/kms');
        cy.get('input[type=submit]').click();
        cy.contains('E-mailadres is verplicht.').should('exist');
        cy.contains('Wachtwoord is verplicht.').should('exist');
    });


    it('Shows wrong account error', function() {
        cy.clearCookies();
        cy.visit('/kms');
        cy.get('input[name=email]').type('doesnotexist@example.org');
        cy.get('input[name=password]').type('heyhowareyou');
        cy.get('input[type=submit]').click();
        cy.contains('Het opgeven e-mailadres en/of wachtwoord komen niet overeen, Probeer nogmaals?');
    });

    it('Can be logged in to', () => {
        cy.clearCookies();
        cy.visit('/kms');

        cy.getCsrfToken((csrfToken) => {
            cy.request({
                method: 'GET',
                url: 'testapi/v1/kms_users/create',
                headers: {'X-CSRF-TOKEN': csrfToken},
                body: {'attributes': {'password': 'Test123'}}
            }).then(function (response) {
                let user = response.body.data;

                cy.get('input[name=email]').type(user.email);
                cy.get('input[name=password]').type('Test123');
                cy.get('input[type=submit]').click();
                cy.contains('Afmelden');

                cy.getCsrfToken((csrfToken) => {
                    cy.request({
                        method: 'DELETE',
                        url: 'testapi/v1/kms_users/' + user.id,
                        headers: {'X-CSRF-TOKEN': csrfToken},
                        body: {}
                    });
                });
            });
        });
    });

    it('Should be able to reset the password', () => {
        cy.clearCookies();
        cy.visit('/kms');

        cy.getCsrfToken((csrfToken) => {
            cy.request({
                method: 'GET',
                url: 'testapi/v1/kms_users/create',
                headers: {'X-CSRF-TOKEN': csrfToken},
                body: {'attributes': {'password': 'Test123' }}
            }).then(function(response) {
                let user = response.body.data;

                cy.log('Telling laravel that it should intercept the next mails');
                cy.request('testapi/v1/mail_intercept/enable').then((response) => {
                    cy.log('Requesting a password reset');
                    cy.get('[data-test="reset_password"]').click();
                    cy.get('input[name="email"]').type(user.email);
                    cy.get('[data-test="send_email"]').click();
                    cy.contains('We hebben een e-mail verstuurd').should('exist');

                    cy.log('Getting the latest sent mails from laravel');
                    cy.request('testapi/v1/mail_intercept/get').then((response) => {
                        let mails = response.body;

                        //Get the mails for the user only
                        let mailsForUser = mails.filter(function(mail) {
                            for (let mailAddress in mail.to) if(mailAddress === user.email) return true;
                            return false;
                        });
                        let passwordResetMail = mailsForUser[0];

                        //Validate the mail
                        cy.log('Validating the email');
                        expect(passwordResetMail.subject).to.contain('Nieuw wachtwoord instellen');
                        expect(passwordResetMail).to.have.property('data');
                        expect(passwordResetMail.data).to.have.property('resetPasswordUrl');

                        //Construct the reset url and visit it.
                        cy.log('Heading to the password reset page');
                        cy.visit(passwordResetMail.data.resetPasswordUrl).then(() => {
                            let newPassword = faker.internet.password();
                            cy.get('[data-test="email"]').type(user.email);
                            cy.get('[data-test="password"]').type(newPassword);
                            cy.get('[data-test="password_confirmation"]').type(newPassword);
                            cy.get('[data-test="reset_password"]').click();
                            cy.contains('Je wachtwoord is succesvol ingesteld.').should('exist');

                            cy.log('Trying to login!');
                            cy.get('input[name=email]').type(user.email);
                            cy.get('input[name=password]').type(newPassword);
                            cy.get('input[type=submit]').click();

                            cy.get('[data-test="log-out"]').should('exist');
                        });

                        cy.getCsrfToken((csrfToken) => {
                            cy.request({
                                method: 'DELETE',
                                url: 'testapi/v1/kms_users/' + user.id,
                                headers: {'X-CSRF-TOKEN': csrfToken},
                                body: {}
                            });
                        });
                    });
                });
            });
        });
    })

    it('Should be able to set a password the first time', () => {
        cy.clearCookies();
        cy.visit('/kms');

        cy.getCsrfToken((csrfToken) => {
            cy.request({
                method: 'GET',
                url: 'testapi/v1/kms_users/create',
                headers: {'X-CSRF-TOKEN': csrfToken},
                body: {'attributes': {'password': '' }}
            }).then(function(response) {
                let user = response.body.data;

                cy.log('Sending the user a password set mail via kms');
                cy.kms_login('kms/kms_users');
                cy.getCsrfToken((csrfToken) => {
                    cy.get('[data-test="KMS gebruikers"]').click();
                    cy.contains(user.first_name+' '+user.last_name).click({force: true});

                    cy.log('Telling laravel that it should intercept the next mails');
                    cy.request('testapi/v1/mail_intercept/enable').then((response) => {
                        cy.log('Sending the password set / welcome mail to the user');
                        cy.get('[data-test=mail_sent]').should('not.be.visible');
                        cy.get('[data-test=send_password_set_mail_button]').click();
                        cy.get('[data-test=mail_sent]').should('be.visible');

                        cy.log('Getting the latest sent mails from laravel');
                        cy.request('testapi/v1/mail_intercept/get').then((response) => {
                            let mails = response.body;

                            //Get the mails for the user only
                            let mailsForUser = mails.filter(function(mail) {
                                for (let mailAddress in mail.to) if(mailAddress === user.email) return true;
                                return false;
                            });
                            let passwordSetMail = mailsForUser[0];

                            cy.log('Logging out so we can set the password');
                            cy.get('[data-test="log-out"]').click();

                            //Validate the mail
                            cy.log('Validating the email');
                            expect(passwordSetMail.subject).to.contain('Welkom in het beheersysteem');
                            expect(passwordSetMail).to.have.property('data');
                            expect(passwordSetMail.data).to.have.property('setPasswordUrl');

                            //Construct the reset url and visit it.
                            cy.log('Heading to the password set page');
                            cy.visit(passwordSetMail.data.setPasswordUrl).then(() => {
                                let newPassword = faker.internet.password();
                                cy.get('[data-test="email"]').type(user.email);
                                cy.get('[data-test="password"]').type(newPassword);
                                cy.get('[data-test="password_confirmation"]').type(newPassword);
                                cy.get('[data-test="reset_password"]').click();
                                cy.contains('Je wachtwoord is succesvol ingesteld.').should('exist');

                                cy.log('Trying to login!');
                                cy.get('input[name=email]').type(user.email);
                                cy.get('input[name=password]').type(newPassword);
                                cy.get('input[type=submit]').click();

                                cy.get('[data-test="log-out"]').should('exist');
                            });

                            cy.getCsrfToken((csrfToken) => {
                                cy.request({
                                    method: 'DELETE',
                                    url: 'testapi/v1/kms_users/' + user.id,
                                    headers: {'X-CSRF-TOKEN': csrfToken},
                                    body: {}
                                });
                            });
                        });
                    });
                });
            });
        });
    })
});
