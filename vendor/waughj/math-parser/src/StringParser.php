<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
	interface StringParser
	{
		public function parse( string $expression );
		public function getMainDivider() : string;
    }
}
