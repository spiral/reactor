<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\DeclarationInterface;
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
}

/**
 * Generic element declaration.
 */
abstract class Declaration implements DeclarationInterface
{
    /**
     * Access level constants.
     */
    const ACCESS_PUBLIC    = 'public';
    const ACCESS_PROTECTED = 'protected';
    const ACCESS_PRIVATE   = 'private';

    /**
     * @param string $string
     * @param int    $indent
     *
     * @return string
     */
    protected function addIndent(string $string, int $indent = 0): string
    {
        return str_repeat(self::INDENT, max($indent, 0)) . $string;
    }

    /**
     * Normalize string endings to avoid EOL problem. Replace \n\r and multiply new lines with
     * single \n.
     *
     * @param string $string       String to be normalized.
     * @param bool   $joinMultiple Join multiple new lines into one.
     *
     * @return string
     */
    protected function normalizeEndings(string $string, bool $joinMultiple = true): string
    {
        if (!$joinMultiple) {
            return str_replace("\r\n", "\n", $string);
        }

        return preg_replace('/[\n\r]+/', "\n", $string);
    }
}