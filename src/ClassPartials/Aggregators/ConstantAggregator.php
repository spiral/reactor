<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\ClassPartials\Aggregators;

use Spiral\Reactor\ClassPartials\ConstantDeclaration;
use Spiral\Reactor\Aggregator;

/**
 * Constants aggregation. Can automatically create constant on demand.
 *
 * @method $this add(ConstantDeclaration $element)
 */
class ConstantAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([ConstantDeclaration::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return ConstantDeclaration
     */
    public function get(string $name): ConstantDeclaration
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $constant = new ConstantDeclaration($name, null);

            parent::add($constant);

            return $constant;
        }

        return parent::get($name);
    }
}