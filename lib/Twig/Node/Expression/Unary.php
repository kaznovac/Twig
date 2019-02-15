<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 * (c) Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class Twig_Node_Expression_Unary extends \Twig\Node\Expression\AbstractExpression
{
    public function __construct(\Twig\Node\Node $node, $lineno)
    {
        parent::__construct(['node' => $node], [], $lineno);
    }

    public function compile(\Twig\Compiler $compiler)
    {
        $compiler->raw(' ');
        $this->operator($compiler);
        $compiler->subcompile($this->getNode('node'));
    }

    abstract public function operator(\Twig\Compiler $compiler);
}

class_alias('Twig_Node_Expression_Unary', 'Twig\Node\Expression\Unary\AbstractUnary', false);
