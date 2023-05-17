Math Parser
=========================

Simple Lisp-like math parser.

## Example

    use WaughJ\MathParser\MathParser;

    $math = new MathParser();
    echo( $math->parse( '(+ 2 2)' ) );

Prints "4".

To use a different parser:

    use WaughJ\MathParser\MathParser;

    $math = new MathParser( new LisphpParser( [ ',' ] ) );
    echo( $math->parse( '(*,2,5)' ) );

Prints "10".

To create a new custom function:

    use WaughJ\MathParser\MathParser;
    use WaughJ\MathParser\MathParserExceptionInvalidFunctionCall;

    $math = new MathParser();
    $math->addFunction
    (
        'double',
        function( array $args )
        {
            $arg_count = count( $args );
            if ( $arg_count < 1 )
            {
                throw new MathParserExceptionInvalidFunctionCall
                (
                    "Call to function “double” given no arguments. Needs at least 1."
                );
            }
            return array_shift( $args ) * 2;
        }
    );
    echo( $math->parse( '(double 222)' ) );

Prints "444".

## Changelog

### 0.1.0
* Initial stable version.