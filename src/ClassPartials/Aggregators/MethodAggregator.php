<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\ClassPartials\Aggregators;

use Spiral\Reactor\ClassPartials\MethodDeclaration;
use Spiral\Reactor\Aggregator;
use Spiral\Reactor\DeclarationInterface;

/**
 * Method aggregation. Can automatically create constant on demand.
 *
 * @method MethodDeclaration add(MethodDeclaration $element)
 */
class MethodAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([MethodDeclaration::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return MethodDeclaration|DeclarationInterface
     */
    public function get(string $name): MethodDeclaration
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $method = new MethodDeclaration($name);
            $this->add($method);

            return $method;
        }

        return parent::get($name);
    }
}