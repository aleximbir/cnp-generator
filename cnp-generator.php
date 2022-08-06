<?php
// PRINT
echo isCnpValid( '1920704070919' ) ? 'Valid' : 'Invalid';

// CNP VALIDATOR FNC
function isCnpValid( $cnp_value = '', $display_error = false ) {
	$error = false;

	$gender   = ( int ) substr( $cnp_value, 0, 1 );
	$month    = ( int ) substr( $cnp_value, 3, 2 );
	$day      = ( int ) substr( $cnp_value, 5, 2 );
	$district = ( int ) substr( $cnp_value, 7, 2 );
	$control  = ( int ) substr( $cnp_value, -1, 1 );

	$cnp_array = array_map( 'intval', str_split( $cnp_value ) );

	if ( ! $cnp_array )
		$error = 'Introduceti un CNP';

	elseif ( ! preg_match( "/^\d+$/", $cnp_value ) )
		$error = 'CNPul introdus trebuie sa contina doar cifre';

	elseif ( count( $cnp_array ) != 13 )
		$error = 'Numarul de caractere introdus trebuie sa fie 13';

	elseif ( $gender < 1 && $gender > 9 )
		$error = 'Primul numar introdus trebuie sa fie intre 1 si 9, reprezentand genul persoanei';

	elseif ( $month < 1 && $month > 12 )
		$error = 'Numarul asociat lunii nasterii este invalid';

	elseif ( $day < 1 && $day > 31 )
		$error = 'Numarul asociat lunii nasterii este invalid';

	elseif ( $district < 1 && $district > 52 )
		$error = 'Numarul asociat judetului este invalid';

	elseif ( $control != getGeneratedControlNumber( $cnp_array ) )
		$error = 'Numarul de control este invalid';

	if ( $error ) {
		if ( $display_error ) echo $error;
		return false;
	}

	return true;
}

// CONTROL NUMBER VALIDATOR FNC
function getGeneratedControlNumber( $cnp ) {
	$control = 0;

	$default_number_arr = array( 2, 7, 9, 1, 4, 6, 3, 5, 8, 2, 7, 9 );

	for ( $i = 0; $i < 13; $i++ ) {
		$cnp[$i] = ( int ) $cnp[$i];
		if ( $i < 12 ) $control += ( int ) $cnp[$i] * ( int ) $default_number_arr[$i];
	}

	$control = $control % 11;
	if ( $control == 10 ) $control = 1;

	return $control;
}