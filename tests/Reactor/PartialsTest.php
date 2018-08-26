<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\ClassDeclaration;
use Spiral\Reactor\Partials\Method;
use Spiral\Reactor\Partials\Parameter;
use Spiral\Reactor\Partials\Property;

class PartialsTest extends TestCase
{
    public function testParameter()
    {
        $p = new Parameter("name");
        $this->assertSame("name", $p->getName());
        $this->assertFalse($p->isOptional());
        $this->assertSame(null, $p->getDefaultValue());
        $this->assertFalse($p->isPBR());

        $p->setDefaultValue("default");
        $this->assertTrue($p->isOptional());
        $this->assertSame("default", $p->getDefaultValue());

        $p->setPBR(true);
        $this->assertTrue($p->isPBR());

        $this->assertSame("&\$name = 'default'", $p->render());

        $this->assertSame('', $p->getType());
        $p->setType('int');
        $this->assertSame('int', $p->getType());
        $this->assertSame("int &\$name = 'default'", $p->render());

        $p->removeDefaultValue();
        $this->assertSame("int &\$name", $p->render());
    }

    public function testProperty()
    {
        $p = new Property("name");
        $this->assertSame("name", $p->getName());
        $this->assertFalse($p->hasDefaultValue());
        $this->assertSame(null, $p->getDefaultValue());

        $this->assertSame("private \$name;", $p->render());

        $p = new Property("name", 10);
        $this->assertSame("name", $p->getName());
        $this->assertTrue($p->hasDefaultValue());
        $this->assertSame(10, $p->getDefaultValue());
        $this->assertSame("private \$name = 10;", $p->render());

        $p->removeDefaultValue();

        $this->assertFalse($p->hasDefaultValue());
        $this->assertSame(null, $p->getDefaultValue());

    }

    public function testMethod()
    {
        $m = new Method("method");
        $this->assertSame("method", $m->getName());
        $this->assertFalse($m->isStatic());

        $m->setStatic(true);
        $this->assertTrue($m->isStatic());

        $m->parameter('name')->setDefaultValue('value');
        $this->assertCount(1, $m->getParameters());

        $m->setSource("return \$name;");

        $this->assertSame(preg_replace('/\s+/', '', "private function method(\$name = 'value')
{
    return \$name;
}"), preg_replace('/\s+/', '', $m->render()));

        $m2 = new Method("method", ["return true;"], "some method");
        $m2->setPublic();

        $this->assertSame(preg_replace('/\s+/', '', "/**
 * some method
 */
public function method()
{
    return true;
}"), preg_replace('/\s+/', '', $m2->render()));

        $m3 = new Method("method", "return true;", ["some method"]);
        $m3->setProtected();

        $this->assertSame(preg_replace('/\s+/', '', "/**
 * some method
 */
protected function method()
{
    return true;
}"), preg_replace('/\s+/', '', $m3->render()));
    }

    public function testDefaultsInClass()
    {
        $c = new ClassDeclaration("TestClass");
        $c->constant('SCHEMA')->setValue([
            'key' => 'value'
        ]);

        $c->property('schema')->setDefaultValue([
            'key' => 'value'
        ]);

        $this->assertSame(preg_replace('/\s+/', '', "class TestClass
{
    const SCHEMA = [
        'key' => 'value'
    ];

    private \$schema = [
        'key' => 'value'
    ];
}"), preg_replace('/\s+/', '', $c->render()));
    }
}