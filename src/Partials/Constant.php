<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\Partials;

use Doctrine\Common\Inflector\Inflector;
use Spiral\Reactor\AbstractDeclaration;
use Spiral\Reactor\NamedInterface;
use Spiral\Reactor\Traits\CommentTrait;
use Spiral\Reactor\Traits\NamedTrait;
use Spiral\Reactor\Traits\SerializerTrait;

/**
 * Class constant declaration.
 */
class Constant extends AbstractDeclaration implements NamedInterface
{
    use NamedTrait, CommentTrait, SerializerTrait;

    /**
     * @var mixed
     */
    private $value = null;

    /**
     * @param string       $name
     * @param string       $value
     * @param string|array $comment
     */
    public function __construct(string $name, $value, $comment = '')
    {
        $this->setName($name);
        $this->value = $value;
        $this->initComment($comment);
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): Constant
    {
        $this->name = strtoupper(Inflector::tableize(strtolower($name)));

        return $this;
    }

    /**
     * Array values allowed (but works in PHP7 only).
     *
     * @param mixed $value
     *
     * @return self
     */
    public function setValue($value): Constant
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function render(int $indentLevel = 0): string
    {
        $result = '';
        if (!$this->docComment->isEmpty()) {
            $result .= $this->docComment->render($indentLevel) . "\n";
        }

        $result .= $this->addIndent("const {$this->getName()} = ", $indentLevel);

        return $result . $this->getSerializer()->serialize($this->value) . ';';
    }
}