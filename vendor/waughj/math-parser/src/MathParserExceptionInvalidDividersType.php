<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
    class MathParserExceptionInvalidDividersType extends \Exception
    {
        public function __construct( $dividers )
        {
            parent::__construct( gettype( $dividers ) );
        }
    }
}
