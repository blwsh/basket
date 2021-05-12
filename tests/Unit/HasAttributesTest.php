<?php namespace blwsh\basket\Tests\Unit;

use PHPUnit\Framework\TestCase;
use blwsh\basket\Traits\HasAttributes;

/**
 * Class AttributeStub
 *
 * @property string name
 *
 * @package blwsh\basket\Tests
 */
class AttributeStub
{
    use HasAttributes;

    /**
     * @var string
     */
    public string $test;
}

/**
 * Class HasAttributesTest
 *
 * @package blwsh\basket\Tests
 */
class HasAttributesTest extends TestCase
{
    /**
     * Test adding and getting a new field to attributes array via magic method.
     *
     * @param string $fieldName
     */
    public function testSetAttribute($fieldName = 'name')
    {
        $stub = new AttributeStub;
        $stub->$fieldName = $expected = 'Test';
        $this->assertEquals($expected, $stub->$fieldName);
        $this->assertNotFalse(array_search($expected, $stub->getAttributes()));

    }

    /**
     * Test null returned when getting property that doesn't exist in attributes array and isset returns false.
     */
    public function testGetUndefinedAttributeIsNull()
    {
        $stub = new AttributeStub;
        $this->assertEquals(null, $stub->undefinedTest);
        $this->assertFalse(isset($stub->undefinedTest));
    }

    /**
     * Check public class properties take priority over attributes.
     */
    public function testPreferClassProperty()
    {
        $stub = new AttributeStub;
        $stub->test = $expected = 'test';
        $this->assertEquals($expected, $stub->test);
        $this->assertTrue(property_exists($stub, 'test'));
        $this->assertFalse(array_search($expected, $stub->getAttributes()));
    }
}
