Cypress.Commands.add('kms_login', (intended_url, username = 'info@komma.nl', password = '$tilD3H0nger10') => {
    cy.log('Heading to the intended url if possible...');
    cy.visit(intended_url).then(() => {
        cy.url().then((url) => {
            cy.log('Current url is: '+url+' '+window.location);
            if(url.includes('kms/login')) {
                cy.log('That was not possible. We need to login.');
                cy.get('input[name=email]').type(username);
                cy.get('input[name=password]').type(password);
                cy.get('input[type=submit]').click();
                cy.log('Logged in. Heading to the intended url');
                cy.visit(intended_url).then(() => {
                    cy.url().should('include', intended_url);
                });
            } else {
                cy.log('Intended url was reached. Not logging in :).');
            }
        })
    });
});