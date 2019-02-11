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
 * Generic element declaration.
 */
abstract class AbstractDeclaration implements DeclarationInterface
{
    /**
     * Access level constants.
     */
    const ACCESS_PUBLIC    = 'public';
    const ACCESS_PROTECTED = 'protected';
    const ACCESS_PRIVATE   = 'private';

    /**
     * @param string $string
     * @param int    $indent
     *
     * @return string
     */
    protected function addIndent(string $string, int $indent = 0): string
    {
        return str_repeat(self::INDENT, max($indent, 0)) . $string;
    }
}
