import faker from 'faker';

/// <reference types="Cypress" />
describe('Component area Test', () => {
    it('Should be able to manipulate the tabs component', () => {
        let testPageName = 'Cypress Test Page '+faker.random.number({min: 10000, max: 99999});

        //Add a new page, enter a code name, and open a language tab.
        cy.kms_login('kms/default/pages');
        cy.get('[data-test="add_button"]').click();
        cy.get('[data-test="TextField-code_name"]').type(testPageName);
        cy.get('[data-test=entity_tab_nl]').click();

        //Add the tab component
        cy.log('Adding an tab component');
        cy.get('[data-test=add_content-slider_component_nl]').click();

        cy.wait(1000); //Wait because of some stupid tiny mce initialisation bug we cannot solve

        //Check that the first tab is active and fill it
        cy.get('[data-test="1"]').parent().should('have.class', 'active');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|label_1|nl"]').type('Tab 1');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|header_1|nl"]').type('Tab 1 title');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|text_1|nl"]').type('Tab 1 text', {force: true});

        //Check that the third tab is active and fill it
        cy.get('[data-test="3"]').parent().should('not.have.class', 'active');
        cy.get('[data-test="3"]').click();
        cy.get('[data-test="3"]').parent().should('have.class', 'active');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|label_3|nl"]').type('Tab 3');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|header_3|nl"]').type('Tab 3 title');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|text_3|nl"]').type('Tab 3 text', {force: true});

        //Select the first item in the select and look if it is selected (jQuery UI)
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|buttons_3|nl_items"]').children().should('have.length', 0);
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|buttons_3|nl-fake"]').type('{downArrow}{downArrow}{enter}');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|buttons_3|nl_items"]').children().should('have.length', 1);

        //Save the page and thus the tabs.
        cy.get('[data-test="save_button"]').click();

        cy.request('/testapi/v1/components/index').then((componentsResponse) => {
            let components = componentsResponse.body.data;
            let lastComponent = components[0];

            //Validate tab 1
            cy.get('[data-test="1"]').parent().should('have.class', 'active');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|label_1|nl"]').should('have.value', 'Tab 1');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|header_1|nl"]').should('have.value', 'Tab 1 title');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|text_1|nl"]').should('have.value', 'Tab 1 text');

            //Validate tab 3
            cy.get('[data-test="3"]').parent().should('not.have.class', 'active');
            cy.get('[data-test="3"]').click();
            cy.get('[data-test="3"]').parent().should('have.class', 'active');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|label_3|nl"]').should('have.value','Tab 3');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|header_3|nl"]').should('have.value','Tab 3 title');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|text_3|nl"]').should('have.value','Tab 3 text');

            //Delete the component. Verify that it is.
            cy.get('[data-test=component-delete-'+lastComponent.id+']').click();
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);

            //Save the page. It then must not have that component anymore.
            cy.get('[data-test="save_button"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);
        });
    });

    it('Should be able to manipulate the quote component', () => {
        let testPageName = 'Cypress Test Page '+faker.random.number({min: 10000, max: 99999});

        //Add a new page, enter a code name, and open a language tab.
        cy.kms_login('kms/default/pages');
        cy.get('[data-test="add_button"]').click();
        cy.get('[data-test="TextField-code_name"]').type(testPageName);
        cy.get('[data-test=entity_tab_nl]').click();

        //Add the quote component
        cy.log('Adding an quote component, and verify that it does not have images already');
        cy.get('[data-test="add_quote_component_nl"]').click();


        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|text|nl"]').type('Man\'s immortality is not to live forever; for that wish is born of fear. Each moment free from fear makes a man immortal."');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|title|nl"]').type('Alexander the Great');
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|subtitle|nl"]').type('King of Macedonia and Persia');

        //Save the page, and thus the image component
        cy.get('[data-test="save_button"]').click();

        cy.request('/testapi/v1/components/index').then((componentsResponse) => {
            let components = componentsResponse.body.data;
            let lastComponent = components[0];

            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|text|nl"]').should('have.value', 'Man\'s immortality is not to live forever; for that wish is born of fear. Each moment free from fear makes a man immortal."');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|title|nl"]').should('have.value','Alexander the Great');
            cy.get('[data-test="ComponentArea|dynamic_group_sections|C'+lastComponent.id+'|subtitle|nl"]').should('have.value','King of Macedonia and Persia');

            //Delete the component. Verify that it is.
            cy.get('[data-test=component-delete-'+lastComponent.id+']').click();
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);

            //Save the page. It then must not have that component anymore.
            cy.get('[data-test="save_button"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);
        });
    });

    it('Should be able to manipulate the image component', () => {
        let testPageName = 'Cypress Test Page '+faker.random.number({min: 10000, max: 99999});

        //Add a new page, enter a code name, and open a language tab.
        cy.kms_login('kms/default/pages');
        cy.get('[data-test="add_button"]').click();
        cy.get('[data-test="TextField-code_name"]').type(testPageName);
        cy.get('[data-test=entity_tab_nl]').click();

        //Request an overview of all documents
        cy.request('/testapi/v1/documents/index').then((response) => {
            let documents = response.body.data;
            let documentsCountBeforeUpload = documents.length;
            cy.log('Documents count before upload: '+documentsCountBeforeUpload);

            //Define some selectors for testing if uploading works.
            let imageComponentImagesListSelectorBeforeUpload = '#'+imageComponentKey(0)+'-wrapper > ul';
            let imageComponentDragAndDropAreaSelector = '[data-test="'+imageComponentKey(0)+'_file_catcher"]';
            let imageComponentFirstImageThumbSelector = '#'+imageComponentKey(0)+'-wrapper > ul > li:nth-child(1) .thumb';

            //Add the image component
            cy.log('Adding an image component, and verify that it does not have images already');
            cy.get('[data-test="add_image_component_nl"]').click();
            cy.get(imageComponentImagesListSelectorBeforeUpload).children().should('have.length', 0);

            //Upload a file
            cy.log('Uploading an image');
            cy.uploadFile('uploads/flower_image.jpg', imageComponentDragAndDropAreaSelector, 'image/jpeg', false);
            cy.get(imageComponentImagesListSelectorBeforeUpload).children().should('have.length', 1);
            cy.get(imageComponentFirstImageThumbSelector).should('have.class', 'is-uploading');
            cy.get(imageComponentFirstImageThumbSelector).should('not.have.class', 'is-uploading');

            //Save the page, and thus the image component
            cy.get('[data-test="save_button"]').click();

            //Request an overview of all documents
            cy.request('/testapi/v1/documents/index').then((response) => {
                let documents = response.body.data;
                let documentsCountAfterUpload = documents.length;
                cy.log('Documents count after upload: '+documentsCountAfterUpload);
                let uploadedDocument = documents[0];

                //Define some selectors for testing if the upload did work
                let imageComponentImagesListSelectorAfterUpload = '#'+imageComponentKey(uploadedDocument.documentable.id)+'-wrapper > ul';
                let imageComponentFirstImageSelector = '#'+imageComponentKey(uploadedDocument.documentable.id)+'-wrapper > ul > li:nth-child(1)';

                //Check if the given uploaded document is visible in the image component.
                cy.log('Checking if the uploaded document is visible');
                cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 1);

                //Delete the image
                cy.log('Deleting the image');
                cy.get(imageComponentFirstImageSelector).should('not.have.class', 'deleted');
                cy.get(imageComponentFirstImageSelector+' .delete').click();
                cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 1);

                //Save and verify that it is deleted.
                cy.log('Saving and verifying that it is deleted');
                cy.get('[data-test="save_button"]').click();
                cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 0);
            });
        })
    });

    it('Should be able to manipulate the text image component', () => {
        let testPageName = 'Cypress Test Page '+faker.random.number({min: 10000, max: 99999});

        //Add a new page, enter a code name, and open a language tab.
        cy.kms_login('kms/default/pages');
        cy.get('[data-test="add_button"]').click();
        cy.get('[data-test="TextField-code_name"]').type(testPageName);
        cy.get('[data-test=entity_tab_nl]').click();
        cy.document().then((doc) => {
            //Add the text image component
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);
            cy.get('[data-test=add_text-image_component_nl]').click();
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 1);

            //Get the first component
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().first().then((textImage) => {
                //Notice: Because of the way cypress serializes it's commands it is not possible to interact with tiny mce as this commented block of code.
                // let editor = doc.defaultView.tinyMCE.get("ComponentArea|dynamic_group_sections|C0|text|nl");
                // editor.on('SetContent', (event) => {
                //     cy.log('content set');
                //
                //     //Upload an image
                //     cy.log('Uploading an image');
                //     cy.uploadFile('uploads/flower_image.jpg', imageComponentDragAndDropAreaSelector, 'image/jpeg', false);
                //     cy.get(imageComponentImagesListSelectorBeforeUpload).children().should('have.length', 1);
                //     cy.get(imageComponentFirstImageThumbSelector).should('have.class', 'is-uploading');
                //     cy.get(imageComponentFirstImageThumbSelector).should('not.have.class', 'is-uploading');
                //
                //     //Type a caption for the image
                //     cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|caption|nl"]').type('Test Text Image');
                // });
                //
                // cy.wrap(editor).its('initialized').should('be', true);
                // cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|text|nl"]').type('Test', {force:true});

                //Same thing when using the npm plugin: @foreachbe/cypress-tinymce like this
                // cy.setTinyMceContent("ComponentArea|dynamic_group_sections|C0|text|nl", 'This is the new content');
                // let content = cy.getTinyMceContent("ComponentArea|dynamic_group_sections|C0|text|nl");
                // cy.wrap(content).should('equal', 'This is the new content');

                //Define some selectors
                let imageComponentImagesListSelectorBeforeUpload = '#'+imageComponentKey(0)+'-wrapper > ul';
                let imageComponentDragAndDropAreaSelector = '[data-test="'+imageComponentKey(0)+'_file_catcher"]';
                let imageComponentFirstImageThumbSelector = '#'+imageComponentKey(0)+'-wrapper > ul > li:nth-child(1) .thumb';

                //Upload an image
                cy.log('Uploading an image');
                cy.uploadFile('uploads/flower_image.jpg', imageComponentDragAndDropAreaSelector, 'image/jpeg', false);
                cy.get(imageComponentImagesListSelectorBeforeUpload).children().should('have.length', 1);
                cy.get(imageComponentFirstImageThumbSelector).should('have.class', 'is-uploading');
                cy.get(imageComponentFirstImageThumbSelector).should('not.have.class', 'is-uploading');

                //Type a caption for the image
                cy.log('Typing a text image');
                cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|caption|nl"]').type('Testing the image caption');

                //Switch text and image:
                cy.log('Switching text and image');
                cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|reversed|nl_toggle"]').click();

                //Save it
                cy.get('[data-test="save_button"]').click();
                cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.

                //Request an overview of all documents, then check if the uploaded image was uploaded. Delete it and verify that it was
                cy.request('/testapi/v1/documents/index').then((documentsResponse) => {
                    let documents = documentsResponse.body.data;
                    let documentsCountAfterUpload = documents.length;

                    cy.request('/testapi/v1/components/index').then((componentsResponse) => {
                        let components = componentsResponse.body.data;
                        let lastComponent = components[0];

                        cy.log('Documents count after upload: ' + documentsCountAfterUpload);
                        let uploadedDocument = documents[0];

                        //Define some selectors for testing if the upload did work
                        let imageComponentImagesListSelectorAfterUpload = '#' + imageComponentKey(uploadedDocument.documentable.id) + '-wrapper > ul';
                        let imageComponentFirstImageSelector = '#' + imageComponentKey(uploadedDocument.documentable.id) + '-wrapper > ul > li:nth-child(1)';

                        //Check if the given uploaded document is visible in the image component.
                        cy.log('Checking if the uploaded document is visible');
                        cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 1);

                        //Delete the image
                        cy.log('Deleting the image');
                        cy.get(imageComponentFirstImageSelector).should('not.have.class', 'deleted');
                        cy.get(imageComponentFirstImageSelector + ' .delete').click();
                        cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 1);

                        //Save and verify that it is deleted.
                        cy.log('Saving and verifying that it is deleted');
                        cy.get('[data-test="save_button"]').click();
                        cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 0);

                        //Verify the caption of the image
                        cy.get('[data-test="ComponentArea|dynamic_group_sections|C' + lastComponent.id + '|caption|nl"]').should('have.value', 'Testing the image caption');

                        //Verify the toggle
                        cy.get('[data-test="ComponentArea|dynamic_group_sections|C' + lastComponent.id + '|reversed|nl"]').should('have.value', '1');

                        //Save the text image component
                        cy.get('[data-test="save_button"]').click();
                        cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.

                        //Verify that it does not have the image anymore
                        cy.log('Checking if the uploaded document is visible');
                        cy.get(imageComponentImagesListSelectorAfterUpload).children().should('have.length', 0);

                        //Delete the component. Verify that it is.
                        cy.wait(1000); //Wait because of some stupid tiny mce initialisation bug we cannot solve
                        cy.get('[data-test=component-delete-'+lastComponent.id+']').click();
                        cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);

                        //Save the page. It then must not have that component anymore.
                        cy.get('[data-test="save_button"]').click();
                        cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.
                        cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().should('have.length', 0);
                    });
                });
            })
        });
    });

    it('Should be able to manipulate the order of components', () => {
        let testPageName = 'Cypress Test Page '+faker.random.number({min: 10000, max: 99999});

        //Add a new page, enter a code name, and open a language tab.
        cy.log('Putting sample data in the page so we can save it later on');
        cy.kms_login('kms/default/pages');
        cy.get('[data-test="add_button"]').click();
        cy.get('[data-test="TextField-code_name"]').type(testPageName);
        cy.get('[data-test=entity_tab_nl]').click();

        //Add the quote component
        cy.log('Adding a quote component');
        cy.get('[data-test="add_quote_component_nl"]').click();
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C0|title|nl"]').type('Quote provider 1');

        //And another one
        cy.log('Adding a quote component');
        cy.get('[data-test="add_quote_component_nl"]').click();
        cy.get('[data-test="ComponentArea|dynamic_group_sections|C-1|title|nl"]').type('Quote provider 2');

        //Get the components wrapper and validate the order of components. E.g. The second component must be inserted after the first.
        cy.log('Validating the order of components after initial creation');
        cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').then((componentsWrapper) => {
            let idCounter = 0;
            componentsWrapper.children().each((index,component) => {
                cy.wrap(component).should('have.attr', 'data-test', 'component-id-'+idCounter.toString());
                idCounter--;
            });
        });

        //Save the page
        cy.log('Saving the page');
        cy.get('[data-test="save_button"]').click();
        cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.

        //Get the components from the DB
        cy.request('/testapi/v1/components/index').then((componentsResponse) => {
            let components = componentsResponse.body.data;
            let lastComponent = components[1]; //The last component is saved first in the saving process.
            let secondLastComponent = components[0]; //The second last component is saved second in the saving process. So it has a newer updated_at value in it's record. And that's how the components are ordered when calling the api url.

            //Check that the components are in the order they where added
            cy.log('Checking that the components are in the same order as before the save');
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().then((components) => {
                cy.wrap(components[0]).should('have.attr', 'data-test', 'component-id-'+secondLastComponent.id);
                cy.wrap(components[1]).should('have.attr', 'data-test', 'component-id-'+lastComponent.id);
            });

            //Make the top component swap with the bottom component
            cy.log('Swapping the components');
            cy.get('[data-test="component-down-'+secondLastComponent.id+'"]').click(); //Move the second last component, 1 down. So that both components swap.

            //Validate the swap
            cy.log('Checking that the components did swap.');
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().then((components) => {
                cy.wrap(components[0]).should('have.attr', 'data-test', 'component-id-'+lastComponent.id);
                cy.wrap(components[1]).should('have.attr', 'data-test', 'component-id-'+secondLastComponent.id);
            });

            //Save the page. It then must not have that component anymore.
            cy.get('[data-test="save_button"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '2'); //Data type 2 means success.

            //Validate the swap after save
            cy.log('Checking still are swapped after saving.');
            cy.get('[data-test="ComponentArea-dynamic_group_sections-nl_components"]').children().then((components) => {
                cy.wrap(components[0]).should('have.attr', 'data-test', 'component-id-'+lastComponent.id);
                cy.wrap(components[1]).should('have.attr', 'data-test', 'component-id-'+secondLastComponent.id);
            });

            //Delete the page
            cy.get('[data-test="delete_button"]').click();
            cy.get('[data-test="confirmation_confirm"]').click();
            cy.get('[data-test=flash_message]').should('have.attr', 'data-type', '3'); //Data type 3 means general ok
        });
    });

    function imageComponentKey(componentId) {
        return `ComponentArea\\|dynamic_group_sections\\|C${componentId}\\|image\\|nl`;
    }
});