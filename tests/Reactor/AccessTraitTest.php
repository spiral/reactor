<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\AbstractDeclaration;
use Spiral\Reactor\Traits\AccessTrait;

class AccessTraitTest extends TestCase
{
    use AccessTrait;

    public function testProtected()
    {
        $this->setProtected();
        $this->assertSame(AbstractDeclaration::ACCESS_PROTECTED, $this->getAccess());
    }


    public function testPrivate()
    {
        $this->setPrivate();
        $this->assertSame(AbstractDeclaration::ACCESS_PRIVATE, $this->getAccess());
    }


    public function testPublic()
    {
        $this->setPublic();
        $this->assertSame(AbstractDeclaration::ACCESS_PUBLIC, $this->getAccess());
    }

    /**
     * @expectedException \Spiral\Reactor\Exception\ReactorException
     */
    public function testBad()
    {
        $this->setAccess('wrong');
    }
}
