<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Defines a variable.
 *
 *  {% set foo = 'foo' %}
 *  {% set foo = [1, 2] %}
 *  {% set foo = {'foo': 'bar'} %}
 *  {% set foo = 'foo' ~ 'bar' %}
 *  {% set foo, bar = 'foo', 'bar' %}
 *  {% set foo %}Some content{% endset %}
 */
final class Twig_TokenParser_Set extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $names = $this->parser->getExpressionParser()->parseAssignmentExpression();

        $capture = false;
        if ($stream->nextIf(/* \Twig\Token::OPERATOR_TYPE */ 8, '=')) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();

            $stream->expect(/* \Twig\Token::BLOCK_END_TYPE */ 3);

            if (\count($names) !== \count($values)) {
                throw new \Twig\Error\SyntaxError('When using set, you must have the same number of variables and assignments.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        } else {
            $capture = true;

            if (\count($names) > 1) {
                throw new \Twig\Error\SyntaxError('When using set with a block, you cannot have a multi-target.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }

            $stream->expect(/* \Twig\Token::BLOCK_END_TYPE */ 3);

            $values = $this->parser->subparse([$this, 'decideBlockEnd'], true);
            $stream->expect(/* \Twig\Token::BLOCK_END_TYPE */ 3);
        }

        return new \Twig\Node\SetNode($capture, $names, $values, $lineno, $this->getTag());
    }

    public function decideBlockEnd(\Twig\Token $token)
    {
        return $token->test('endset');
    }

    public function getTag()
    {
        return 'set';
    }
}

class_alias('Twig_TokenParser_Set', 'Twig\TokenParser\SetTokenParser', false);
