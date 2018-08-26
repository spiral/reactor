<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\Partials\Aggregators;

use Spiral\Reactor\Partials\Property;
use Spiral\Reactor\Aggregator;

/**
 * Property aggregation. Can automatically create constant on demand.
 *
 * @method $this add(Property $element)
 */
class PropertyAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([Property::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return Property
     */
    public function get(string $name): Property
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $property = new Property($name);
            $this->add($property);

            return $property;
        }

        return parent::get($name);
    }
}