<?php

namespace Tests\Unit;

use Komma\KMS\Components\Component\ComponentAttributeKey;
use Komma\KMS\Components\ComponentArea\ComponentAreaSaveState;
use Komma\KMS\Components\ComponentArea\ComponentAreaService;
use Komma\KMS\Components\ComponentArea\ComponentAreaServiceInterface;
use Komma\KMS\Components\ComponentType\ComponentTypeFactory;
use App\Components\ComponentTypes;
use Komma\KMS\Components\ComponentType\AbstractComponentType;
use Komma\KMS\Components\ComponentArea\ComponentArea as ComponentAreaModel;
use App\Pages\Models\Page;
use App\Pages\Models\PageTranslation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\ComponentArea;
use Tests\TestCase;

class ComponentAreaTest extends TestCase
{
    use DatabaseTransactions; //Automatically rolls back database actions after tests

    /**
     * @group ComponentAreaTest
     * @test
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function testComponentAreaSaveWithSingleComponent()
    {
        //Initialize the ComponentAreaService
        $componentAreaService = app(ComponentAreaServiceInterface::class);

        //Create a page for the component area so we can link the component area to it
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        /** @var Page $page */
        $page = $pageTranslation->translatable()->first();

        //Create a json string that represents a component of type 1 (double text)
        //Such json is provided by the frontend. The id of 0 means that a user pressed a button
        //to add this component to the translation. If it was an existing one it would be a greater number.
        $componentAreaJson = '
        [
          {
            "id": 0,
            "version": "0.9.2",
            "componentTypeId": '.ComponentTypes::DOUBLE_TEXT.',
            "data": {
              "ComponentArea|dynamic_group_sections|C0|left|en": "<p>Left</p>",
              "ComponentArea|dynamic_group_sections|C0|right|en": "<p>Right</p>"
            },
            "componentableData": {},
            "sortOrder": 0
          }
        ]
        ';

        //Check that we have that component type in the database
        $type = ComponentTypeFactory::make(1);
        $this->assertInstanceOf(AbstractComponentType::class, $type);

        //Simulate the generation of attributes in the most simple form
        $componentArea = new ComponentArea();
        $componentArea->setSubFolder('component_area_center_files');
        $componentArea->mapValueFrom(Attribute::ValueFromComponentArea, 'component_area_center');

        $componentArea->setAssociatedLanguage($pageTranslation->language);

        //Simulate the fillAttributes with data method from the sectionService
        $componentArea->setValue($componentAreaJson);

        //The pageTranslation should have no componentArea yet.
        $this->assertTrue($pageTranslation->componentAreas()->count() == 0);

        //Save the json from the componentArea via the service.
        $pageTranslation = $componentAreaService->save($pageTranslation, collect([$componentArea]));

        //ComponentAreas count should be 1 after saving the the componentArea attribute
        $this->assertTrue($pageTranslation->componentAreas()->count() == 1);
        /** @var ComponentAreaModel $componentArea */
        $componentAreaModel = $pageTranslation->componentAreas()->first();

        //There should be one component for the component area. It's the double text type (componentTypeId 1)
        $this->assertTrue($componentAreaModel->components()->count() == 1);
    }

    /**
     * @test
     * @group ComponentAreaTest
     */
    public function testComponentAreaLoadWithSingleComponent()
    {
        //Save a component area that we can use later on
        //Initialize the ComponentAreaService
        $componentAreaService = app(ComponentAreaServiceInterface::class);

        //Create a page for the component area so we can link the component area to it
        /** @var PageTranslation $pageTranslation */
        $pageTranslation = factory(PageTranslation::class)->create();
        /** @var Page $page */
        $page = $pageTranslation->translatable;

        //Create a json string that represents a component of type 1 (double text)
        //Such json is provided by the frontend. The id of 0 means that a user pressed a button
        //to add this component to the translation. If it was an existing one it would be a greater number.
        $componentAreaJson =
            '[{
            "id":0,
            "version":"'.ComponentAreaService::SAVE_VERSION.'",
            "componentTypeId":'.ComponentTypes::DOUBLE_TEXT.',
            "data":
            {
                "ComponentArea|component_area_center|C0|FieldOne|nl":"Left text",
                "ComponentArea|component_area_center|C0|FieldTwo|nl":"Right text",
                "ComponentArea|component_area_center|C0|products|nl":""
            },
            "componentableData":{},
            "sortOrder":0
        }]';

        $componentArea = new ComponentArea();
        $componentArea->setSubFolder('component_area_center_files');
        $componentArea->mapValueFrom(Attribute::ValueFromComponentArea, 'component_area_center');
        $componentArea->setAssociatedLanguage($pageTranslation->language);
        $componentArea->setValue($componentAreaJson);
        $pageTranslation = $componentAreaService->save($pageTranslation, collect([$componentArea]));

        //Now try to load the component area and check that it is saved like it should.
        //Load the component area save state json
        /** @var $componentArea */
        $attributes = $componentAreaService->load($pageTranslation, collect([$componentArea]));
        $componentArea = $attributes->first();
        $componentAreaJson = $componentArea->getValue();

        //Check that we have a json string
        $this->assertTrue(is_string($componentAreaJson));
        $this->assertTrue(strlen($componentAreaJson) > 0);
        $this->assertJson($componentAreaJson);

        //Check if we can recreate the ComponentAreaSaveState
        $componentAreaSaveState = ComponentAreaSaveState::fromJsonString($componentAreaJson);
        $this->assertInstanceOf(ComponentAreaSaveState::class, $componentAreaSaveState);
        /** @var ComponentAreaSaveState $componentAreaSaveState */

        $this->assertTrue($componentAreaSaveState->getComponentsCount() === 1);

        //Check that the component save state was saved correctly
        $componentSaveState = $componentAreaSaveState->getComponentSaveStateAt(0);

        //The id should no be 0 anymore because the component data is saved
        $this->assertNotEquals(0, $componentSaveState->getId());
        $this->assertEquals(ComponentAreaService::SAVE_VERSION, $componentSaveState->getVersion());
        $this->assertEquals(ComponentTypes::DOUBLE_TEXT, $componentSaveState->getComponentTypeId());

        $data = $componentSaveState->getData();
        $this->assertCount(3, $data);

        $attributeKeysAsStrings = array_keys($data);
        $leftTextAreaAttributeKey = ComponentAttributeKey::createInstanceFromString($attributeKeysAsStrings[0]);
        $rightTextAreaAttributeKey = ComponentAttributeKey::createInstanceFromString($attributeKeysAsStrings[1]);

        $this->assertInstanceOf(ComponentAttributeKey::class, $leftTextAreaAttributeKey);
        $this->assertInstanceOf(ComponentAttributeKey::class, $rightTextAreaAttributeKey);

        $this->assertEquals("ComponentArea", $leftTextAreaAttributeKey->getAttributeShortClassName());
        $this->assertEquals("ComponentArea", $rightTextAreaAttributeKey->getAttributeShortClassName());
        $this->assertEquals("component_area_center", $leftTextAreaAttributeKey->getValuePart());
        $this->assertEquals("component_area_center", $rightTextAreaAttributeKey->getValuePart());
        $this->assertNotEquals(0, $leftTextAreaAttributeKey->getComponentId());
        $this->assertNotEquals(0, $rightTextAreaAttributeKey->getComponentId());
        $this->assertEquals('FieldOne', $leftTextAreaAttributeKey->getAttributeReference());
        $this->assertEquals('FieldTwo', $rightTextAreaAttributeKey->getAttributeReference());
        $this->assertEquals('nl', $leftTextAreaAttributeKey->getTranslationIso2());
        $this->assertEquals('nl', $rightTextAreaAttributeKey->getTranslationIso2());

        $this->assertEquals("Left text", $data[$attributeKeysAsStrings[0]]);
        $this->assertEquals("Right text", $data[$attributeKeysAsStrings[1]]);
    }
}
