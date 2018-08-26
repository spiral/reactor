<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor\ClassPartials\Aggregators;

use Spiral\Reactor\ClassPartials\ParameterDeclaration;
use Spiral\Reactor\Aggregator;

/**
 * Constants aggregation. Can automatically create constant on demand.
 *
 * @method $this add(ParameterDeclaration $element)
 */
class ParameterAggregator extends Aggregator
{
    /**
     * @param array $constants
     */
    public function __construct(array $constants)
    {
        parent::__construct([ParameterDeclaration::class], $constants);
    }

    /**
     * Get named element by it's name.
     *
     * @param string $name
     *
     * @return ParameterDeclaration
     */
    public function get(string $name): ParameterDeclaration
    {
        if (!$this->has($name)) {
            //Automatically creating constant
            $parameter = new ParameterDeclaration($name);
            $this->add($parameter);

            return $parameter;
        }

        return parent::get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function render(int $indentLevel = 0): string
    {
        /**
         * Overwriting parent call.
         */

        $parameters = [];
        foreach ($this->getIterator() as $element) {
            $parameters[] = $element->render(0);
        }

        return join(', ', $parameters);
    }
}