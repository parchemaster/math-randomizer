<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
    class MathParserExceptionInvalidFunction extends \Exception
    {
        public function __construct( $function )
        {
            if ( is_array( $function ) )
            {
                $function = json_encode( $function );
            }
            parent::__construct( ( string )( $function ) );
        }
    }
}
