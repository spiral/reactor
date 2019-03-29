<?php declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\Partial;

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

        $value = $this->getSerializer()->serialize($this->value);
        if (is_array($this->value)) {
            $value = $this->mountIndents($value, $indentLevel);
        }

        return $result . "{$value};";
    }

    /**
     * Mount indentation to value. Attention, to be applied to arrays only!
     *
     * @param string $serialized
     * @param int    $indentLevel
     *
     * @return string
     */
    private function mountIndents(string $serialized, int $indentLevel): string
    {
        $lines = explode("\n", $serialized);
        foreach ($lines as &$line) {
            $line = $this->addIndent($line, $indentLevel);
            unset($line);
        }

        return ltrim(join("\n", $lines));
    }
}