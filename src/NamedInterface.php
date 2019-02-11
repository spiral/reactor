<?php
declare(strict_types=1);
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Reactor;

/**
 * Declaration with name.
 */
interface NamedInterface extends DeclarationInterface
{
    /**
     * @return string
     */
    public function getName(): string;
}