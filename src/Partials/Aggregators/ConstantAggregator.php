<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\Partials\Aggregators;

use Spiral\Reactor\Partials\Constant;
use Spiral\Reactor\Aggregator;

/**
 * Constants aggregation. Can automatically create constant on demand.
 *
 * @method $this add(Constant $element)
 */
class ConstantAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([Constant::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return Constant
     */
    public function get(string $name): Constant
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $constant = new Constant($name, null);

            parent::add($constant);

            return $constant;
        }

        return parent::get($name);
    }
}