<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\Partials\Source;
use Spiral\Reactor\Serializer;
use Spiral\Reactor\Traits\SerializerTrait;

class SerializerTest extends TestCase
{
    //To cover this weird trait as well
    use SerializerTrait;

    public function setUp()
    {
        $this->setSerializer(new Serializer());
    }

    public function testSetGet()
    {
        $this->setSerializer($s = new Serializer());
        $this->assertSame($s, $this->getSerializer());
    }

    public function testEmptyArray()
    {
        $this->assertSame('[]', $this->getSerializer()->serialize([]));
    }

    public function testArrayOfArray()
    {
        $this->assertEquals(preg_replace('/\s+/', '',
            '[
    \'hello\' => [
        \'name\' => 123
    ]
]'), preg_replace('/\s+/', '', $this->getSerializer()->serialize([
            'hello' => ['name' => 123]
        ])));
    }

    public function testArrayOfArray2()
    {
        $this->assertEquals(preg_replace('/\s+/', '',
            '[
    \'hello\' => [
        \'name\' => 123,
        \'sub\'  => magic
    ]
]'), preg_replace('/\s+/', '', $this->getSerializer()->serialize([
            'hello' => ['name' => 123, 'sub' => new Source(['magic'])]
        ])));
    }

    public function testClassNames()
    {
        $this->assertEquals(preg_replace('/\s+/', '',
            '[
    \'hello\' => [
        \'name\' => 123,
        \'sub\'  => \Spiral\Reactor\Serializer::class
    ]
]'), preg_replace('/\s+/', '', $this->getSerializer()->serialize([
            'hello' => ['name' => 123, 'sub' => Serializer::class]
        ])));
    }

    /**
     * @expectedException \Spiral\Reactor\Exceptions\SerializeException
     */
    public function testSerializeResource()
    {
        $this->getSerializer()->serialize(STDOUT);
    }
}