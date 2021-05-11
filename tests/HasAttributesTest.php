<?php namespace blwsh\basket\Tests;

use blwsh\basket\Traits\HasAttributes;
use PHPUnit\Framework\TestCase;

/**
 * Class AttributeSub
 *
 * @property string name
 *
 * @package blwsh\basket\Tests
 */
class AttributeSub
{
    use HasAttributes;
}

/**
 * Class HasAttributesTest
 *
 * @package blwsh\basket\Tests
 */
class HasAttributesTest extends TestCase
{
    /**
     * @param string $fieldName
     */
    public function testSetAttribute($fieldName = 'name')
    {
        $stub = new AttributeSub;
        $stub->$fieldName = $expected = 'Test';
        $this->assertEquals($expected, $stub->$fieldName);
    }

    /**
     *
     */
    public function testGetUndefinedAttributeIsNull()
    {
        $stub = new AttributeSub;
        $this->assertEquals(null, $stub->undefinedTest);
        $this->assertFalse(isset($stub->undefinedTest));
    }
}
