<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
    class MathParserExceptionNonExistentFunctionCall extends \Exception
    {
        public function __construct( $function )
        {
            parent::__construct( ( string )( $function ) );
        }
    }
}
