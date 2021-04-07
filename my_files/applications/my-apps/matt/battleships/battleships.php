<?php

/*
 * Battleships.php
 * Meta: This file contains the main logic and routing
*/


// Load configuration for grid size, number of ships and sizes
$config=parse_ini_file("config.ini",1);
$config['ships']=explode(",",$config['ships']);

session_start();
require_once("battleships.class.php");

$game = new battleships($config['x'],$config['y']);

/*
 * If no session->grid, set up a new game, otherwise load the grid from the session into the object
 */
if (!$_SESSION['grid']){

	$game->add_ships($config['ships']);
	$game_grid = $game->get_grid();
	$_SESSION['grid']=$game_grid;
	$_SESSION['shots']=0;

} else {
	$game->load_grid($_SESSION['grid']);
}

if ($_GET['action']){

	/* Fire action */
	if ($_GET['action']=="fire"){
		
		// parse the input to get x and y co-ordinates
		if (stristr($_GET['xy'],",")){
			// comma separated input in the format x,y - matches the internal grid diretly so no conversion to a grid reference required
			$co_ords=explode(",",$_GET['xy']);
			$x=$co_ords[0];
			$y=$co_ords[1];
		} else {
			// 2 letter input in the format A1 - convert this to a numeric grid reference
			$co_ords=str_split($_GET['xy']);
			$y = ord($co_ords[0])-65;
			$x = $co_ords[1];
		}
		
		// fire
		$fire=$game->fire($x,$y);
	
		// load the response object with our results, also send back the current grid
		$response['hit_value']=$fire['result'];
		$response['message'] = $fire['message'];
		$response['grid']=$game->export_grid_visual();
		$response['co_ords']=$x.",".$y;

		if ($game->check_game_status()){
			$response['win_message'] .= "Well done! You completed the game in " . $_SESSION['shots'] . " shots";
		}

		// store updated grid
		$_SESSION['grid']=$game->get_grid();

	} else if ($_GET['action']=="new_game"){

		ob_start(); // purely a temporary measure to clear debugging text

		if (!$config){ print "NO CONFIG";}
		$game = new battleships($config['x'],$config['y']);
		$game->add_ships($config['ships']);
		$game_grid = $game->get_grid();

		ob_end_clean();

		$_SESSION['grid']=$game_grid;
		$_SESSION['shots']=0;

		$response['message']="New game ready";

	} else if ($_GET['action']=="show"){

		$game->load_grid($_SESSION['grid']);
		print $game->export_grid_visual();
		exit;

	} else {

		$response['message'] = "No action specified";

	}
	

} else { 
	$response = "no action was called - nothing to do";
}

print json_encode($response);

