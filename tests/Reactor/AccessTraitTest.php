<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\Prototypes\Declaration;
use Spiral\Reactor\Traits\AccessTrait;

class AccessTraitTest extends TestCase
{
    use AccessTrait;

    public function testProtected()
    {
        $this->setProtected();
        $this->assertSame(Declaration::ACCESS_PROTECTED, $this->getAccess());
    }


    public function testPrivate()
    {
        $this->setPrivate();
        $this->assertSame(Declaration::ACCESS_PRIVATE, $this->getAccess());
    }


    public function testPublic()
    {
        $this->setPublic();
        $this->assertSame(Declaration::ACCESS_PUBLIC, $this->getAccess());
    }

    /**
     * @expectedException \Spiral\Reactor\Exceptions\ReactorException
     */
    public function testBad()
    {
        $this->setAccess('wrong');
    }
}