<?php
/**
 * PHP Data Grid Source
 *
 * @license MIT
 * @author  Valentin V (vvval)
 */
declare(strict_types=1, ticks=1);


namespace Spiral\Reactor\Partial;

use Spiral\Reactor\DeclarationInterface;

class Directives implements DeclarationInterface
{
    /** @var string[] */
    private $directives;

    /**
     * @param string ...$directives
     */
    public function __construct(string ...$directives)
    {
        $this->directives = $directives;
    }

    /**
     * {@inheritDoc}
     */
    public function render(int $indentLevel = 0): string
    {
        if (empty($this->directives)) {
            return '';
        }

        return 'declare(' . join(', ', $this->directives) . ');';
    }
}
