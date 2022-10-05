<?php

use Komma\KMS\Core\Sequence\Parts\NumberPart;
use Komma\KMS\Core\Sequence\Sequence;
use App\Shop\Orders\Models\Order;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SequenceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNumberPartBasicFunctionality()
    {
        $numberPart = new NumberPart(4);

        $this->assertEquals('0000', $numberPart->startingAt(0)->getValue());
        $this->assertEquals('0010', $numberPart->startingAt(10)->getValue());
        $this->assertEquals('0011', $numberPart->next()->getValue());
        $this->assertEquals('0013', $numberPart->next()->next()->getValue());
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testSequenceDissection()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4)) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20170020'); //Internally dissects the value into the startsWith and followedBy parts

        //The sequence casted to a string triggers internal __toString magic method,
        //converting it to a string, using the parts and the startingAt value
        $this->assertEquals('FACT-20170020', (string) $sequence);
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testInvalidSequenceDissection()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
        ->followedBy('-')
            ->followedBy(new NumberPart(4)) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4))); //The four indicates that the number will be 4 digits long. Zero-padded if needed

        $this->expectException(InvalidArgumentException::class);
        $sequence->startingAt('FACT-2010020'); //Internally dissects the value into the startsWith and followedBy parts. Notice the typo in the year. It is one digit to short
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNamedAndUnnamedParts()
    {
        $sequence = new Sequence();
        $sequence->startsWith('TEStING')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy(new NumberPart(4, 'InvoiceNo')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy(new NumberPart(2)) //The 2 indicates that the number will be 2 digits long. Zero-padded if needed
            ->startingAt('TEStING-2019000102');

        $this->assertEquals('TEStING', $sequence->getPartByName('testing')); //Case insensitive. Unnamed
        $this->assertEquals('2019', $sequence->getPartByName('year')->getValue()); //Case insensitive. Named
        $this->assertEquals('0001', $sequence->getPartByName('InvoiceNo')->getValue()); //Case insensitive. Named
        $this->assertEquals('02', last($sequence->getParts())->getValue()); //Case insensitive. Unnamed
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNextValues()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
                 ->followedBy('-')
                 ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
                 ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
                 ->startingAt('FACT-20170020');

        //Lets not specify which part to increment. This should increment the last part only. That would be the InvoiceNo part.
        $this->assertEquals('FACT-20170021', (string) $sequence->next());

        //Lets increment the Invoice number part...
        $this->assertEquals('FACT-20170022', (string) $sequence->next('InvoiceNo'));
        $this->assertEquals('FACT-20170023', (string) $sequence->next(3));

        //...and the year
        $this->assertEquals('FACT-20180023', (string) $sequence->next('yeaR'));
        $this->assertEquals('FACT-20190023', (string) $sequence->next(2));
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNextValueWithWrongIntPartIdentifier()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20170020');

        $this->expectException(OutOfBoundsException::class);
        $this->assertEquals('FACT-20190023', (string) $sequence->next(4)); //The last part identifier is 3. Not 4.
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNextValueWithWrongNamePartIdentifier()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20170020');

        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals('FACT-20190023', (string) $sequence->next('Yeah!')); //Yeah! Should be year.
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testNextValueWithNameThatResolvesToStringPart()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20170020');

        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals('FACT-20190023', (string) $sequence->next('FACT')); //You cannot calculate the next value of a string
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testOverflowingLastPartStrict()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20179999');

        //When we increment 9999 the four digit number would overflow becoming a 5 digit number.
        //This causes the current sequence value to be different then what is allowed by the parts.
        //Simply because the last Numberpart must be 4 digits long, and it now is 5.
        //That's why there is an exception thrown by default.
        $this->expectException(OutOfBoundsException::class);
        $this->assertEquals('FACT-201710000', (string) $sequence->next());
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testOverflowingLastPart()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-20179999');

        $sequence->lastPartMayOverflow();

        //When we increment 9999 the four digit number would overflow becoming a 5 digit number. This causes the current sequence value to be different then what is usually allowed by the parts.
        //Thanks to the call to lastPartMayOverflow, the last value may overflow (others aren't allowed to overflow).
        $this->assertEquals('FACT-201710000', (string) $sequence->next());
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testOverflowingMiddlePart()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-99990001');

        //When we increment 9999 the four digit number would overflow becoming a 5 digit number. This causes the current sequence value to be different then what is allowed by the parts.
        //Also the sequence value cannot be used anymore to reconstruct a sequence object. Simply because the last Numberpart must be 4 digits long, and it now is 5 and following parts cannot be found anymore.
        //That's why there is an exception thrown.
        $this->expectException(OutOfBoundsException::class);
        $this->assertEquals('FACT-100000001', (string) $sequence->next('Year'));
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testCreatingSequenceWithOverflownLastValueStrict()
    {
        $sequence = new Sequence();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))); //The four indicates that the number will be 4 digits long. Zero-padded if needed

        $this->expectException(InvalidArgumentException::class);
        $sequence->startingAt('FACT-201910000');
    }

    /**
     * @group SequenceTest
     * @test
     */
    public function testCreatingSequenceWithOverflownLastValue()
    {
        $sequence = new Sequence();
        $sequence->lastPartMayOverflow();

        $sequence->startsWith('FACT')
            ->followedBy('-')
            ->followedBy(new NumberPart(4, 'Year')) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->followedBy((new NumberPart(4, 'InvoiceNo'))) //The four indicates that the number will be 4 digits long. Zero-padded if needed
            ->startingAt('FACT-201910000');

        $this->assertEquals('FACT', (string) $sequence->getParts()[0]);
        $this->assertEquals('-', (string) $sequence->getParts()[1]);
        $this->assertEquals(2019, (string) $sequence->getPartByName('year')->getValue());
        $this->assertEquals(10000, (string) $sequence->getPartByName('InvoiceNo')->getValue());
    }
}