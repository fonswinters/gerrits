Cypress.Commands.add('getCsrfToken', (callback, options = {}) => {
    cy.get("head meta[name=csrf-token]").then((metaTags) => {
        if(metaTags.length === 0) return;
        const csrf = metaTags[0].content;
        callback(csrf);
    });
});

