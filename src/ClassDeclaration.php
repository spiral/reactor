<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Reactor\Aggregators\Constants;
use Spiral\Reactor\Aggregators\Methods;
use Spiral\Reactor\Aggregators\Properties;
use Spiral\Reactor\Exceptions\ReactorException;
use Spiral\Reactor\Partials\Constant;
use Spiral\Reactor\Partials\Method;
use Spiral\Reactor\Partials\Property;
use Spiral\Reactor\Traits\CommentTrait;
use Spiral\Reactor\Traits\NamedTrait;

/**
 * Class declaration.
 */
class ClassDeclaration extends AbstractDeclaration implements ReplaceableInterface, NamedInterface
{
    use NamedTrait, CommentTrait;

    /** @var string */
    private $extends = '';

    /** @var array */
    private $interfaces = [];

    /** @var array */
    private $traits = [];

    /** @var Constants */
    private $constants = null;

    /** @var Properties */
    private $properties = null;

    /** @var Methods */
    private $methods = null;

    /**
     * @param string $name
     * @param string $extends
     * @param array  $interfaces
     * @param string $comment
     *
     * @throws ReactorException When name is invalid.
     */
    public function __construct(
        string $name,
        string $extends = '',
        array $interfaces = [],
        string $comment = ''
    ) {
        $this->setName($name);

        if (!empty($extends)) {
            $this->setExtends($extends);
        }

        $this->setInterfaces($interfaces);
        $this->initComment($comment);

        $this->constants = new Constants([]);
        $this->properties = new Properties([]);
        $this->methods = new Methods([]);
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): ClassDeclaration
    {
        $this->name = Inflector::classify($name);

        return $this;
    }

    /**
     * @param string $class Class name.
     *
     * @return self
     */
    public function setExtends($class): ClassDeclaration
    {
        $this->extends = ltrim($class, '\\');

        return $this;
    }

    /**
     * @return string
     */
    public function getExtends(): string
    {
        return $this->extends;
    }

    /**
     * @param string $interface
     *
     * @return bool
     */
    public function hasInterface(string $interface): bool
    {
        $interface = ltrim($interface, '\\');

        return isset($this->interfaces[$interface]);
    }

    /**
     * Declare class interfaces.
     *
     * @param array $interfaces
     *
     * @return self
     */
    public function setInterfaces(array $interfaces): ClassDeclaration
    {
        $this->interfaces = [];
        foreach ($interfaces as $interface) {
            $this->addInterface($interface);
        }

        return $this;
    }

    /**
     * @param string $interface
     *
     * @return self
     */
    public function addInterface(string $interface): ClassDeclaration
    {
        $this->interfaces[ltrim($interface, '\\')] = true;

        return $this;
    }

    /**
     * @param string $interface
     *
     * @return self
     */
    public function removeInterface(string $interface): ClassDeclaration
    {
        unset($this->interfaces[ltrim($interface, '\\')]);

        return $this;
    }

    /**
     * Declared interfaces.
     *
     * @return array
     */
    public function getInterfaces(): array
    {
        return array_keys($this->interfaces);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasTrait(string $class): bool
    {
        $class = ltrim($class, '\\');

        return isset($this->traits[$class]);
    }

    /**
     * Declare class traits.
     *
     * @param array $traits
     *
     * @return self
     */
    public function setTraits(array $traits): ClassDeclaration
    {
        $this->traits = [];
        foreach ($traits as $trait) {
            $this->addTrait($trait);
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public function addTrait(string $class): ClassDeclaration
    {
        $this->traits[ltrim($class, '\\')] = true;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    public function removeTrait(string $class): ClassDeclaration
    {
        unset($this->traits[ltrim($class, '\\')]);

        return $this;
    }

    /**
     * @return array
     */
    public function getTraits(): array
    {
        return array_keys($this->traits);
    }

    /**
     * @return Constants|Constant[]
     */
    public function getConstants(): Constants
    {
        return $this->constants;
    }

    /**
     * @param string $name
     *
     * @return Constant
     */
    public function constant(string $name): Constant
    {
        return $this->constants->get($name);
    }

    /**
     * @return Properties|Property[]
     */
    public function getProperties(): Properties
    {
        return $this->properties;
    }

    /**
     * @param string $name
     *
     * @return Property
     */
    public function property(string $name): Property
    {
        return $this->properties->get($name);
    }

    /**
     * @return Methods|Method[]
     */
    public function getMethods(): Methods
    {
        return $this->methods;
    }

    /**
     * @param string $name
     *
     * @return Method
     */
    public function method(string $name): Method
    {
        return $this->methods->get($name);
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    public function replace($search, $replace): ClassDeclaration
    {
        $this->constants->replace($search, $replace);
        $this->properties->replace($search, $replace);
        $this->methods->replace($search, $replace);

        return $this;
    }

    /**
     * @param int $indentLevel
     *
     * @return string
     */
    public function render(int $indentLevel = 0): string
    {
        $result = '';

        if (!$this->docComment->isEmpty()) {
            $result .= $this->docComment->render($indentLevel) . "\n";
        }

        //Class header
        $header = "class {$this->getName()}";

        //Rendering extends
        if (!empty($this->extends)) {
            $header .= " extends {$this->extends}";
        }

        if (!empty($this->interfaces)) {
            $interfaces = join(", ", array_keys($this->interfaces));
            $header .= " implements {$interfaces}";
        }

        $result .= $this->addIndent($header, $indentLevel) . "\n";
        $result .= $this->addIndent("{", $indentLevel) . "\n";

        //Rendering class body
        $result .= $this->renderBody($indentLevel);

        $result = rtrim($result, "\n") . "\n";
        $result .= $this->addIndent("}", $indentLevel);

        return $result;
    }

    /**
     * @param int $indentLevel
     *
     * @return string
     */
    protected function renderBody(int $indentLevel): string
    {
        $result = '';
        if (!empty($this->traits)) {
            $result .= $this->renderTraits($indentLevel + 1) . "\n\n";
        }

        if (!$this->constants->isEmpty()) {
            $result .= $this->constants->render($indentLevel + 1) . "\n\n";
        }

        if (!$this->properties->isEmpty()) {
            $result .= $this->properties->render($indentLevel + 1) . "\n\n";
        }

        if (!$this->methods->isEmpty()) {
            $result .= $this->methods->render($indentLevel + 1) . "\n\n";
        }

        return $result;
    }

    /**
     * @param int $indentLevel
     *
     * @return string
     */
    private function renderTraits(int $indentLevel = 0): string
    {
        $lines = [];
        foreach ($this->traits as $class => $options) {
            $lines[] = $this->addIndent("use {$class};", $indentLevel);
        }

        return join("\n", $lines);
    }
}