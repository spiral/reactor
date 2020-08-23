<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Reactor;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use ReflectionObject;
use Spiral\Reactor\Exception\ReactorException;

/**
 * Provides ability to aggregate specific set of elements (type constrained), render them or
 * apply set of operations.
 */
class Aggregator extends AbstractDeclaration implements
    ArrayAccess,
    IteratorAggregate,
    Countable,
    ReplaceableInterface
{
    /**
     * @var array
     */
    private $allowed;

    /**
     * @var DeclarationInterface[]
     */
    private $elements;

    /**
     * @param array $allowed
     * @param array $elements
     */
    public function __construct(array $allowed, array $elements = [])
    {
        $this->allowed = $allowed;
        $this->elements = $elements;
    }

    /**
     * Get element by it's name.
     *
     * @param string $name
     * @return DeclarationInterface
     * @throws ReactorException
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * Check if aggregation has named element with given name.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        foreach ($this->elements as $element) {
            if ($element instanceof NamedInterface && $element->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add new element.
     *
     * @param DeclarationInterface $element
     * @return self
     * @throws ReactorException
     */
    public function add(DeclarationInterface $element): Aggregator
    {
        $reflector = new ReflectionObject($element);

        $allowed = false;
        foreach ($this->allowed as $class) {
            if ($reflector->isSubclassOf($class) || get_class($element) === $class) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            $type = get_class($element);
            throw new ReactorException("Elements with type '{$type}' are not allowed");
        }

        $this->elements[] = $element;

        return $this;
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     * @return DeclarationInterface
     * @throws ReactorException
     */
    public function get(string $name)
    {
        return $this->find($name);
    }

    /**
     * Remove element by it's name.
     *
     * @param string $name
     * @return self
     */
    public function remove(string $name): Aggregator
    {
        foreach ($this->elements as $index => $element) {
            if ($element instanceof NamedInterface && $element->getName() === $name) {
                unset($this->elements[$index]);
            }
        }

        return $this;
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->remove($offset)->add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    /**
     * {@inheritdoc}
     * @return self
     */
    public function replace($search, $replace): Aggregator
    {
        foreach ($this->elements as $element) {
            if ($element instanceof ReplaceableInterface) {
                $element->replace($search, $replace);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function render(int $indentLevel = 0): string
    {
        $result = '';

        foreach ($this->elements as $element) {
            $result .= $element->render($indentLevel) . "\n\n";
        }

        return rtrim($result, "\n");
    }

    /**
     * Find element by it's name (NamedDeclarations only).
     *
     * @param string $name
     * @return DeclarationInterface
     * @throws ReactorException When unable to find.
     */
    protected function find(string $name): DeclarationInterface
    {
        foreach ($this->elements as $element) {
            if ($element instanceof NamedInterface && $element->getName() === $name) {
                return $element;
            }
        }

        throw new ReactorException("Unable to find element '{$name}'");
    }
}
