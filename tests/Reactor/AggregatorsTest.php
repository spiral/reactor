<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Tests\Reactor;

use PHPUnit\Framework\TestCase;
use Spiral\Reactor\Aggregators\Methods;
use Spiral\Reactor\Aggregators\Parameters;

class AggregatorsTest extends TestCase
{
    public function testMethods()
    {
        $aggr = new Methods([]);
        $m = $aggr->get('method');
        $this->assertNotNull($m);

        $this->assertSame($m, $aggr->get('method'));
    }

    public function testParameters()
    {
        $aggr = new Parameters([]);
        $m = $aggr->get('param');
        $this->assertNotNull($m);

        $this->assertSame($m, $aggr->get('param'));
    }
}