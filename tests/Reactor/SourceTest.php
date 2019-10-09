<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\Partial\Source;

class SourceTest extends TestCase
{
    public function testSource()
    {
        $s = Source::fromString("   \$name='antony';\r\n       return \$name;");
        $this->assertSame("   \$name='antony';", $s->getLines()[0]);
        $this->assertSame("       return \$name;", $s->getLines()[1]);

        $s = Source::fromString("   \$name='antony';\r\n       return \$name;", true);
        $this->assertSame("\$name='antony';", $s->getLines()[0]);
        $this->assertSame("    return \$name;", $s->getLines()[1]);
    }

    public function normalizeEndings()
    {
        $string = "line\n\rline2";
        $this->assertSame("line\nline2", Source::normalizeEndings($string));
        $string = "line\n\r\nline2";
        $this->assertSame("line\n\nline2", Source::normalizeEndings($string, false));
        $this->assertSame("line\nline2", Source::normalizeEndings($string, true));
    }

    public function testNormalizeEndingsEmptyReference()
    {
        $input = ['', '    b', '    c'];
        $output = ['', 'b', 'c'];
        $this->assertSame(
            join("\n", $output),
            Source::normalizeIndents(join("\n", $input))
        );
    }

    public function testNormalizeEndingsEmptySpaceReference()
    {
        $input = [' ', '    b', '    c'];
        $output = ['', 'b', 'c'];
        $this->assertSame(
            join("\n", $output),
            Source::normalizeIndents(join("\n", $input))
        );
    }

    public function testNormalizeEndingsNonEmptyReference()
    {
        $input = ['a', '    b', '    c'];
        $output = ['a', '    b', '    c'];
        $this->assertSame(
            join("\n", $output),
            Source::normalizeIndents(join("\n", $input))
        );
    }
}
