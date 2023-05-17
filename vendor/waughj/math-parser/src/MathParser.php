<?php

declare( strict_types = 1 );
namespace WaughJ\MathParser
{
	class MathParser
	{

		//
		//  PUBLIC
		//
		/////////////////////////////////////////////////////////

			public function __construct( ?StringParser $parser = null )
			{
				$this->parser = ( $parser !== null ) ? $parser : new LisphpParser();
				$this->functions = $this->generateBuildInFunctionsList();
			}

			public function parse( string $expression )
			{
				return $this->eval( $this->parser->parse( $expression ) );
			}

			public function addFunction( string $name, callable $function ) : void
			{
				$this->functions[ $name ] = $function;
			}

			public function getParser() : StringParser
			{
				return $this->parser;
			}

			public function changeParser( StringParser $parser ) : void
			{
				$this->parser = $parser;
			}



		//
		//  PRIVATE
		//
		/////////////////////////////////////////////////////////

			private function eval( $data )
			{
				if ( is_array( $data ) )
				{
					if ( empty( $data ) )
					{
						// Return empty list.
						return $data;
					}
					else if ( count( $data ) === 1 )
					{
						// Is function that takes no arguments ( arguments is an empty list ).
						return $this->eval([ $data[ 0 ], [] ]);
					}
					else
					{
						$function = array_shift( $data );
						$function = $this->eval( $function );
						$args = array_map
						(
							function( $arg )
							{
								return $this->eval( $arg );
							},
							$data
						);
						if ( is_array( $function ) )
						{
							$function = $this->eval( $function );
						}
						else if ( !is_string( $function ) && is_callable( $function ) )
						{
							return $function( $args );
						}

						if ( array_key_exists( $function, $this->functions ) )
						{
							return $this->functions[ $function ]( $args );
						}
						else
						{
							throw new MathParserExceptionNonExistentFunctionCall( $function );
						}
					}
				}
				return $data;
			}

			private function doForEach( array $args, callable $function )
			{
				$answer = $this->eval( array_shift( $args ) );
				while ( !empty( $args ) )
				{
					$arg = $this->eval( array_shift( $args ) );
					$answer = $function( $answer, $arg );
				}
				return $answer;
			}

			private function replaceNameWithValue( array $function_body, string $name, $value ) : array
			{
				foreach ( $function_body as &$item )
				{
					if ( is_string( $item ) && $item === $name )
					{
						$item = $value;
					}
					else if ( is_array( $item ) )
					{
						$item = replaceNameWithValue( $item, $name, $value );
					}
				}
				return $function_body;
			}

			private function replaceNamesWithValues( array $function_body, array $argument_names, array $arguments ) : array
			{
				$argument_count = count( $argument_names );
				if ( $argument_count !== count( $arguments ) )
				{
					throw new \Exception( "Arguments passed don’t match function signature." );
				}
				for ( $i = 0; $i < $argument_count; $i++ )
				{
					$argument_name = $argument_names[ $i ];
					$value = $arguments[ $i ];
					$function_body = $this->replaceNameWithValue( $function_body, $argument_name, $value );
				}
				return $function_body;
			}

			private function generateBuildInFunctionsList() : array
			{
				return [
					'list' => function( array $args )
					{
						return $args;
					},
					'lambda' => function( array $args )
					{
						if ( empty( $args ) )
						{
							throw new MathParserExceptionInvalidFunctionCall( "Call to function “lambda” given no arguments. Needs at least 2." );
						}
						$argument_names = array_shift( $args );
						if ( empty( $args ) )
						{
							throw new MathParserExceptionInvalidFunctionCall( "Call to function “lambda” given only 1 argument. Needs at least 2." );
						}
						$function_body = array_shift( $args );
						return function( array $args ) use ( $argument_names, $function_body )
						{
							$function_body = $this->replaceNamesWithValues( $function_body, $argument_names, $args );

							return $this->eval( $function_body );
						};
					},
					'reduce' => function( array $args )
					{
						$arg_count = count( $args );
						if ( $arg_count !== 2 )
						{
							throw new MathParserExceptionInvalidFunctionCall( "Call to function “sum” given wrong number of arguments. Given {$arg_count}; needs exactly 2." );
						}
						$value = 0;
						$function = $args[ 0 ];
						foreach ( $args[ 1 ] as $arg )
						{
							$value = $this->eval([ $function, $value, $arg ]);
						}
						return $value;
					},
					'+' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) + floatval( $arg ); } );
					},
					'-' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) - floatval( $arg ); } );
					},
					'*' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) * floatval( $arg ); } );
					},
					'/' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) / floatval( $arg ); } );
					},
					'%' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) % floatval( $arg ); } );
					},
					'=' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return $orig == $arg; } );
					},
					'#=' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) === floatval( $arg ); } );
					},
					'!=' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return $orig != $arg; } );
					},
					'!#=' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return floatval( $orig ) !== floatval( $arg ); } );
					},
					'true' => function( array $args )
					{
						return true;
					},
					'false' => function( array $args )
					{
						return false;
					},
					'ceil' => function( array $args )
					{
						return ceil( floatval( $this->eval( array_shift( $args ) ) ) );
					},
					'if' => function( array $args )
					{
						$arg_count = count( $args );
						switch ( $arg_count )
						{
							case ( 0 ):
							case ( 1 ):
							{
								throw new MathParserExceptionInvalidFunctionCall( "Call to function “if” given only {$arg_count} arguments. Needs at least 2." );
							}
							break;

							case ( 2 ):
							{
								$condition = array_shift( $args );
								$do_on_yes = array_shift( $args );
								return ( $this->eval( $condition ) === true ) ? $this->eval( $do_on_yes ) : null;
							}
							break;

							default: // 3 or greater
							{
								$condition = array_shift( $args );
								$do_on_yes = array_shift( $args );
								$do_on_no = array_shift( $args );
								return ( $this->eval( $condition ) === true ) ? $this->eval( $do_on_yes ) : $this->eval( $do_on_no );
							}
							break;
						}
					},
					'or' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return $orig || $arg; } );
					},
					'&' => function( array $args )
					{
						return $this->doForEach( $args, function( $orig, $arg ) { return $orig && $arg; } );
					},
					'and' => function( array $args )
					{
						return $this->functions[ '&' ]( $args );
					},
					'=or' => function( array $args )
					{
						$comparison = floatval( $this->eval( array_shift( $args ) ) );
						foreach ( $args as $arg )
						{
							if ( $comparison === floatval( $this->eval( $arg ) ) )
							{
								return true;
							}
						}
						return false;
					},
					'=&' => function( array $args )
					{
						$comparison = floatval( $this->eval( array_shift( $args ) ) );
						foreach ( $args as $arg )
						{
							if ( $comparison !== floatval( $this->eval( $arg ) ) )
							{
								return false;
							}
						}
						return true;
					},
					'=and' => function( array $args )
					{
						return $this->functions[ '=&' ]( $args );
					},
					'"' => function( array $args )
					{
						return implode( $this->parser->getMainDivider(), $args );
					},
					'rand' => function( array $args )
					{
						if ( empty( $args ) )
						{
							return rand();
						}
						$min = array_shift( $args );
						if ( empty( $args ) )
						{
							return rand( $min );
						}
						$max = array_shift( $args );
						return rand( $min, $max );
					},
					'cmp' => function( array $args )
					{
						if ( empty( $args ) )
						{
							return true;
						}
						$function = array_shift( $args );
						if ( empty( $args ) )
						{
							return true;
						}
						$target = array_shift( $args );
						if ( empty( $args ) )
						{
							return true;
						}

						while ( !empty( $args ) )
						{
							if ( array_shift( $args ) >= $target )
							{
								return false;
							}
						}
						return true;
					},
					'>' => function( array $args )
					{
						if ( empty( $args ) )
						{
							return true;
						}

						$target = array_shift( $args );

						if ( empty( $args ) )
						{
							return true;
						}

						while ( !empty( $args ) )
						{
							if ( array_shift( $args ) >= $target )
							{
								return false;
							}
						}
						return true;
					},
					'gt' => function( array $args )
					{
						return $this->functions[ '>' ]( $args );
					},
					'>#' => function( array $args )
					{
						return $this->functions[ '>' ]( array_map( function( $arg ) { return floatval( $arg ); }, $args ) );
					},
					'gtf' => function( array $args )
					{
						return $this->functions[ '>#' ]( $args );
					}
				];
			}

			private $parser;
			private $functions;
	}
}
