<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
    class MathParserExceptionInvalidSyntaxContentOutsideOfFunction extends \Exception
    {
        public function __construct( string $expression )
        {
            parent::__construct( $expression );
        }
    }
}
