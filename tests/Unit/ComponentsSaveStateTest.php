<?php

namespace Tests\Unit;

use Komma\KMS\Components\ComponentType\SaveState\SaveState;
use Komma\KMS\Components\ComponentType\SaveState\SaveStateBuilder;
use Komma\KMS\Core\Attributes\Attribute;
use Komma\KMS\Core\Attributes\Documents;
use Komma\KMS\Core\Attributes\Models\ImageProperty;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ComponentsSaveStateTest extends TestCase
{
    use DatabaseTransactions; //Automatically rolls back database actions after tests

    /**
     * @group DynamicAttributesSaveStateTest
     * @test
     */
    public function instanceCreationTest()
    {
        $saveState = new SaveState();
        $this->assertInstanceOf(SaveState::class, $saveState);

        $saveStateBuilder = new SaveStateBuilder();
        $this->assertInstanceOf(SaveStateBuilder::class, $saveStateBuilder);
    }

    /**
     * @group DynamicAttributesSaveStateTest
     * @test
     */
    public function buildInvalidSaveStateTest()
    {
        $saveStateBuilder = new SaveStateBuilder();
        $this->expectException(\RuntimeException::class);
        $saveStateBuilder->build(); //Throws exception because the version is not set
    }

    /**
     * Helper function to create a new documents attribute as an example attribute.
     *
     * @return Attribute
     */
    private function getDocumentsAttribute()
    {
        return (new Documents())
            ->setLabelText(__('KMS::global.images'))
            ->onlyAllowImages()
            ->setMaxDocuments(5)
            ->setSubFolder('pages')
            ->setImageProperties([
                (new ImageProperty())->setName('thumb')->setCropMethod(ImageProperty::Fit)->setWidth(100),
                (new ImageProperty())->setName('small')->setCropMethod(ImageProperty::Resize)->setWidth(240),
                (new ImageProperty())->setName('medium')->setCropMethod(ImageProperty::Resize)->setWidth(460),
                (new ImageProperty())->setName('large')->setCropMethod(ImageProperty::Resize)->setWidth(500),
            ])
            ->mapValueFrom(Attribute::ValueFromDocuments, 'pages');
    }

    /**
     * @group DynamicAttributesSaveStateTest
     * @test
     */
    public function buildSaveStateTest()
    {
        $documentsAttribute = $this->getDocumentsAttribute();

        $saveStateBuilder = new SaveStateBuilder();

        $saveState = $saveStateBuilder->setVersion('1.0')
            ->addAttributeInstance($documentsAttribute)
            ->build();

        $this->assertInstanceOf(SaveState::class, $saveState);
        $this->assertEquals('1.0', $saveState->getVersion());
        $this->assertInstanceOf(Documents::class, $saveState->getAttributeInstances()[0]);
    }

    /**
     * @group DynamicAttributesSaveStateTest
     * @test
     */
    public function checkSaveStateToArrayAndJson()
    {
        $documentsAttribute = $this->getDocumentsAttribute();

        $saveStateBuilder = new SaveStateBuilder();

        $saveState = $saveStateBuilder->setVersion('1.0')
            ->addAttributeInstance($documentsAttribute)
            ->build();

        //Check to array
        $saveStateAsArray = $saveState->toArray();
        $this->assertTrue(is_array($saveStateAsArray));
        $this->assertArrayHasKey('version', $saveStateAsArray);
        $this->assertArrayHasKey('attributes', $saveStateAsArray);

        //Check json array
        $saveStateAsJson = json_encode($saveState);
        $this->assertTrue(is_string($saveStateAsJson));
    }
}
