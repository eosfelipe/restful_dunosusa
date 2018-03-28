<?php

function get_mes( $mes ){
	$mes -= 1;
	$meses = array(
		'enero',
		'febrero',
		'marzo',
		'abril',
		'mayo',
		'junio',
		'julio',
		'agosto',
		'septiembre',
		'octubre',
		'noviembre',
		'diciembre',
		);

	return $meses[$mes];

}

function get_mes_menos(){
	$date;
	$mesmenos;

	$date = strtotime(date('Y-m'));
	$mesmenos = date("Y-m", strtotime("-1 month", $date));

	return $mesmenos;
}

?>