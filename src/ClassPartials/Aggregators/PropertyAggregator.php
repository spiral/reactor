<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\ClassPartials\Aggregators;

use Spiral\Reactor\ClassPartials\PropertyDeclaration;
use Spiral\Reactor\Aggregator;

/**
 * Property aggregation. Can automatically create constant on demand.
 *
 * @method $this add(PropertyDeclaration $element)
 */
class PropertyAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([PropertyDeclaration::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return PropertyDeclaration
     */
    public function get(string $name): PropertyDeclaration
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $property = new PropertyDeclaration($name);
            $this->add($property);

            return $property;
        }

        return parent::get($name);
    }
}