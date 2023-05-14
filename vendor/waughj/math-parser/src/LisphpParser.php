<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
	class LisphpParser implements StringParser
	{

		//
		//  PUBLIC
		//
		/////////////////////////////////////////////////////////

			public function __construct( $dividers = [] )
			{
				if ( !is_array( $dividers ) )
				{
					$dividers = [ $dividers ];
				}
				$this->dividers = ( !empty( $dividers ) ) ? $dividers : self::DEFAULT_DIVIDERS;
			}

			public function parse( string $expression )
			{
				$this->expression = $expression;
				$this->chars = str_split( $this->trim( $expression ) );
				$this->data = null;
				$this->stack = [];
				$this->current_arg = '';

				foreach ( $this->chars as $c )
				{
					// Start a function list.
					if ( $c === '(' )
					{
						if ( $this->testOutsideOfFunction() ) // Start the root function list.
						{
							$this->data = [];
							$this->stack[] = &$this->data;
						}
						else // Added function
						{
							$this->addNewListToLastItemOnStack();
							$this->addLatestFunctionToStack();
						}
					}
					// End current function list.
					else if ( $c === ')' )
					{
						if ( $this->testOutsideOfFunction() )
						{
							throw new MathParserExceptionInvalidSyntaxFunctionClosedOutsideFunction( $this->expression );
						}
						else
						{
							$this->current_arg = $this->trim( $this->current_arg );
							$this->moveCurrentArgumentToLastItemOnStack();
							array_pop( $this->stack ); // Since current function list is done, pop it off the stack so we return to the previous function is progress.
						}
					}

					else if ( in_array( $c, $this->dividers ) )
					{
						if ( !is_array( $this->data ) || empty( $this->stack ) )
						{
							throw new MathParserExceptionInvalidSyntaxContentOutsideOfFunction( $this->expression );
						}
						else if ( $this->testEmptyArgument( ( string )( $this->current_arg ) ) )
						{
							// Just ignore
						}
						else
						{
							$this->current_arg = $this->trim( $this->current_arg );
							$this->moveCurrentArgumentToLastItemOnStack();
						}
					}
					else
					{
						if ( $this->testOutsideOfFunction() )
						{
							throw new MathParserExceptionInvalidSyntaxContentOutsideOfFunction( $this->expression );
						}
						else
						{
							$this->current_arg .= $c;
						}
					}
				}
				return $this->data;
			}

			public function resetDividers( $dividers ) : LisphpParser
			{
				return new LisphpParser( $dividers );
			}

			public function addDividers( $dividers ) : LisphpParser
			{
				if ( !is_array( $dividers ) )
				{
					$dividers = [ $dividers ];
				}
				$dividers = array_merge( $this->dividers, $dividers );
				return new LisphpParser( $dividers );
			}

			public function getMainDivider() : string
			{
				return $this->dividers[ 0 ];
			}



		//
		//  PRIVATE
		//
		/////////////////////////////////////////////////////////

			private function addNewListToLastItemOnStack() : void
			{
				$last_item_on_stack = &$this->stack[ count( $this->stack ) - 1 ];
				$last_item_on_stack[] = [];
			}

			private function addLatestFunctionToStack() : void
			{
				$this->stack[] = &$this->stack[ count( $this->stack ) - 1 ][ count( $this->stack[ count( $this->stack ) - 1 ] ) - 1 ];
			}

			private function moveCurrentArgumentToLastItemOnStack() : void
			{
				if ( !$this->testEmptyArgument( $this->current_arg ) )
				{
					// Add argument to stack.
					$this->stack[ count( $this->stack ) - 1 ][] = $this->current_arg;
					// Clear argument variable ( so it's moved 'stead o' just copied ).
					$this->current_arg = '';
				}
			}

			private function testOutsideOfFunction() : bool
			{
				return !is_array( $this->data ) || empty( $this->stack );
			}

			private function testEmptyArgument( string $arg ) : bool
			{
				return empty( $arg ) && $arg !== '0';
			}

			private function trim( string $string ) : string
			{
				return trim( $string, implode( '', $this->dividers ) );
			}

			private $expression;
			private $chars;
			private $data;
			private $stack;
			private $current_arg;
			private $dividers;

			private const DEFAULT_DIVIDERS = [ ' ', "\t", "\n" ];
    }
}