/// <reference types="Cypress" />
import faker from 'faker'

describe('Document manipulation', () => {
    it('Should be possible to upload an image for a user, save and delete it.', () => {
        cy.kms_login('kms/kms_users');
        cy.getCsrfToken((csrfToken) => {
            cy.request({
                method: 'GET',
                url: 'testapi/v1/kms_users/create',
                headers: {'X-CSRF-TOKEN': csrfToken},
                body: {}
            }).then((response) => {
                let user = response.body.data;
                cy.visit('kms/kms_users/'+user.id);
                cy.log('Checking that there should not be any uploaded documents.');
                cy.get('[data-test="Documents-user-uploads"]').children().should('have.length', 0);

                cy.log('Upload an image and check that it is.');
                cy.uploadFile('uploads/flower_image.jpg', '[data-test="Documents-user_file_catcher"]', 'image/jpeg', false);
                cy.get('[data-test="Documents-user-uploads"]').should('have.length', 1);
                cy.get('[data-test="Documents-user-uploads"] > li:nth-child(1) .thumb').should('have.class', 'is-uploading');
                cy.get('[data-test="Documents-user-uploads"] > li:nth-child(1) .thumb').should('not.have.class', 'is-uploading');

                cy.log('Save the user and check that it still has one document.');
                cy.get('[data-test="save_button"]').click();
                cy.get('[data-test="Documents-user-uploads"]').should('have.length', 1);

                cy.log('Delete the document.');
                cy.get('[data-test="Documents-user-uploads"] > li:nth-child(1) .delete').click();
                cy.get('[data-test="Documents-user-uploads"] > li:nth-child(1)').should('have.class', 'deleted');

                cy.log('Save the user and check that it does not have the previously deleted document.');
                cy.get('[data-test="save_button"]').click();
                cy.get('[data-test="Documents-user-uploads"]').children().should('have.length', 0);

                cy.getCsrfToken((csrfToken) => {
                    cy.log('Deleting test user with id ' + user.id);
                    cy.request({
                        method: 'DELETE',
                        url: 'testapi/v1/kms_users/' + user.id,
                        headers: {'X-CSRF-TOKEN': csrfToken},
                    });
                });
            })
        });
    });
});


// /**
//  * @group DocumentUploading
//  * @test
//  * @throws \Throwable
//  * @see \App\Tests\Browser\DocumentUploaderTestShop The shop document uploading test
//  */
// public function testUserImageUploadSavingAndDeleting()
// {
//     //Create a sample user
//     /** @var KmsUser $user */
//     $user = factory(KmsUser::class)->create();
//     $user->role = KmsUserRole::SuperAdmin;
//     $user->save();
//     $this->assertInstanceOf(KmsUser::class, $user);
//     $this->assertDatabaseHas((new KmsUser())->getTable(), [
//     'id' => $user->id
// ]);
//
//     $this->browse(function (Browser $browser) use($user) {
//     $browser->loginAs($user, 'kms')
//     //Open the user section
// ->visit(new KmsUsersSectionTestPage())
// ->pause(1000)
//
//     //Search and open the user
// ->type('@entity_search_input', $user->first_name)
// ->assertSeeIn('@search-result-counter', '1')
// ->click('@found_search_item')
// ->assertInputValue('@TextField-email', $user->email);
//
//     //Get the count of uploaded documents before we are going to upload. And get the latest id
//     $countBeforeUpload = Document::count();
//
//     //Upload profile picture
//     $browser->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul\').childElementCount == 0', null, 'No images should be uploaded yet.') //Make sure no image is uploaded yet
// ->attach('@Documents-user_file_catcher', base_path('tests/Uploads/flower_image.jpg')) //Give the document uploader an image
// ->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul\').childElementCount == 1', null,'An image could not uploaded') //One image should be getting uploaded (not yet uploaded completely).
// ->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul > li:nth-child(1) .thumb\').classList.contains(\'is-uploading\');', null, 'Uploading was not started') //Wait until the image is uploading
// ->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul > li:nth-child(1) .thumb\').classList.contains(\'is-uploading\') == false', null, 'Uploading did not complete'); //Wait until the image is uploaded completely
//
//     //Check that the document was uploaded and that it is referred to correctly in the database
//     $countAfterUpload = Document::count();
//     /** @var Document $latestDocumentAfterUpload */
//     $latestDocumentAfterUpload = Document::latest()->first();
//     $this->assertEquals($countBeforeUpload + 1, $countAfterUpload);
//     $this->assertFileExists(public_path(substr($latestDocumentAfterUpload->file_system_path, 1)));
//     $this->assertEquals(0, $latestDocumentAfterUpload->documentable_id); //This field will only contain a correct id after saving
//     $this->assertEmpty($latestDocumentAfterUpload->documentable_type); //This field will only be filled after saving
//
//
//     //Save the user. The documents documentable id and documentable type fields should now refer to the user
//     $browser->click('@save_button');
//     $latestDocumentAfterUpload->refresh();
//     $this->assertEquals($user->id, $latestDocumentAfterUpload->documentable_id); //This field will only contain a correct id after saving
//     $this->assertEquals(KmsUser::class, $latestDocumentAfterUpload->documentable_type); //This field will only be filled after saving
//
//     //Delete the image
//     $browser->click('#Documents-user-wrapper > ul > li:nth-child(1) .delete')
// ->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul > li:nth-child(1)\').classList.contains(\'deleted\');', null, 'The document was not marked as deleted')
// ->click('@save_button')
// ->waitUntil('document.querySelector(\'#Documents-user-wrapper > ul\').childElementCount == 0', null, 'The just deleted image was not deleted.'); //Make the image is deleted
//
//     $this->assertFileNotExists(public_path(substr($latestDocumentAfterUpload->file_system_path, 1)));
//     $this->assertNull(Document::find($latestDocumentAfterUpload->id));
// });
// }