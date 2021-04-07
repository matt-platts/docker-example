<?php

/*
 * CLASS: battleships 
*/
class battleships{

	private $x;
	private $y;
	private $grid;

	function __construct($x,$y){

		$this->x = $x;
		$this->y = $y;

		$grid = array();
	
	}

	/*
	 * @Function get_grid
	 * Return array
	*/
	public function get_grid(){
		return $this->grid;
	}

	/* @Function load_grid
	 * @Return null
	*/
	public function load_grid($grid){
		$this->grid=$grid;
	}

	/* 
	 * @Function generate_ship
	 * @Param ship_size int (how large ths ship should be)
	 * @Return Mixed
	*/
	private function generate_ship($ship_size){

		// get 2 random co-ordinates
		$ship_x = rand(0,$this->x-1);
		$ship_y = rand(0,$this->y-1);

		// get a random orientation 
		$orientation = (rand() % 2 == 0 ? "horizontal" : "vertical");

		// plot forwards/up or backwards/down (use forwards and backwards only as identifiers, no need to be specific here) 
		$plot_direction = (rand() %2 == 0 ? "forwards" : "backwards");

		// if our chosen plot direction goes of the grid, simply swap the direction so it remains on-grid
		if ($orientation == "horizontal"){
			if ($ship_x + $ship_size > $this->x && $plot_direction=="forwards"){ 
				$plot_direction="backwards";
			}
			if ($ship_x - $ship_size < 0 && $plot_direction=="backwards"){
				$plot_direction="forwards";
			}
		} else {
			if ($ship_y + $ship_size > $this->y && $plot_direction=="forwards"){ 
				$plot_direction="backwards";
			}
			if ($ship_y - $ship_size < 0 && $plot_direction=="backwards"){
				$plot_direction="forwards";
			}

		}

		// we have a ship at random co-ordinates and a random orientation. Now we need to check for collisions and adjust if necessary..
		$collisions=0;
		for ($i=0;$i<$ship_size;$i++){

			if ($orientation=="horizontal"){
				if ($plot_direction=="forwards"){
					$x = $ship_x + $i;
					if ($this->grid[$x][$ship_y] || $collisions){
						$collisions++;
						//print "COLLISION AT $x $ship_y on ship block $i\n";
					}
				}
				if ($plot_direction=="backwards"){
					$x = $ship_x - $i;
					if ($this->grid[$x][$ship_y] || $collisions){
						$collisions++;
						//print "COLLISION AT $x $ship_y on ship block $i\n";
					}
				}
			} else if ($orientation=="vertical"){
				if ($plot_direction=="forwards"){
					$y = $ship_y + $i;
					if ($this->grid[$ship_x][$y] || $collisions){
						$collisions++;
						//print "COLLISION AT $ship_x $y on ship block $i\n";
					}
				}
				if ($plot_direction=="backwards"){
					$y = $ship_y - $i;
					if ($this->grid[$ship_x][$y] || $collisions){
						$collisions++;
						//print "COLLISION AT $ship_x $y on ship block $i\n";
					}
				}

			}
			
		}

		// if we have a collision, see if we have room to move the ship just out of the way. Otherwise, set a flag to regenrate the ship. 
		// in an ideal world I would write something more complex to move the ship to the nearest available spot rather than simply seeing
		// if I can shift it on it's axis and regenerating if not. However, this is a start..
		if ($collisions){
			//print "Have $collisions for ship of size $ship_size at $ship_x and $ship_y";	
			$regenerate=null;
			if ($plot_direction=="forwards"){
				if ($orientation=="horizontal" && $ship_x >= $collisions){
					print "MOVE SHIP BACK $collisions spaces";
					$ship_x -= $collisions;
				} else if ($orientation=="vertical" && $ship_y >= $collisions){
					print "MOVE SHIP UP $collisions spaces";
					$ship_y -= $collisions;
				} else {
					print "regenrate this ship!";
					$regenerate=1;
				}
			} else {

				if ($orientation=="horizontal" && 10-$ship_x > $collisions){
					print "MOVE SHIP BACK $collisions spaces";
					$ship_x += $collisions;
				} else if ($orientation=="vertical" && 10-$ship_y > $collisions){
					print "MOVE SHIP UP $collisions spaces";
					$ship_y += $collisions;
				} else {
					print "regenrate this ship!";
					$regenerate=1;
				}

			}
			// If the ship has collisions we can't adjust quickly, regenrate this ship
			if ($regenerate){
				list($ship_x,$ship_y,$orientation,$plot_direction)=$this->generate_ship($ship_size);
			}
		}


		return array($ship_x,$ship_y,$orientation,$plot_direction);
	}

	
	/* Function add_ships
	 * @Return null 
	*/
	public function add_ships($ships){

		foreach ($ships as $ship_size){

			list($ship_x,$ship_y,$orientation,$plot_direction) = $this->generate_ship((int)$ship_size);


			// plot the ship on our virtual grid
			for ($i=0;$i<$ship_size;$i++){

				$x=$ship_x;
				$y=$ship_y;

				if ($orientation=="horizontal"){
					if ($plot_direction=="forwards"){
						$x = $ship_x+$i;
					} else {
						$x = $ship_x-$i;
					}
				} else {
					if ($plot_direction=="forwards"){
						$y = $ship_y+$i;
					} else {
						$y = $ship_y-$i;
					}
				}
				//print "plotting.. " . $x . "," . $y . "\n";
				$this->grid[$x][$y]=1;
			}
			
			//print $ship_size . ": " . $ship_x . ",".$ship_y . " " . $orientation . " - " . $plot_direction . "\n";

		}

		//var_dump($this->grid);

	}

	/* 
	 * @Function fire
	 * @Param $x - String (x-co-ordinate)
	 * @Param $y - String (y-co-ordinate)
	 * @Return mixed
	*/
	public function fire($x,$y){

		$message = "Target: " . chr($y+65) . "$x: ";
		$message .= $this->grid[$x][$y] !== null? "Hit!" : "Miss";

		$result=$this->grid[$x][$y];
		if ($result==1){
			$this->grid[$x][$y]=2;
		} else if ($result==2){
			$result=1; // we send back a positive response even though it's already been hit - we don't need to identify this to the user at this point
		} else if (!$result){ $result="0";}

		$response['result']=$result;
		$response['message']=$message;	

		$_SESSION['shots']++; // increment the number of shots made

		return $response;
	}

	/* 
	 * @Function check_game_status
	 * Return bool (true = game won)
	*/
	public function check_game_status(){

		foreach ($this->grid as $x){
			foreach ($x as $y){
				if ($y==1){ return false; }
			}
		}

		return true;

	}

	/*
	* @Function export_grid_visual
	* @Meta - return a pure text visualisation of the array showing the battleships
	* @Return mixed
	*/
	public function export_grid_visual(){

		$output=null;
		$output = "   ";

		for ($i=0;$i<$this->x;$i++){
			$output .= $i . "  ";
		};

		$output .= "\n";

		for ($i=0;$i<$this->y;$i++){
			$output .= "" . chr(65+$i) ." ";
			for ($j=0;$j<$this->x;$j++){
				if ($this->grid[$j][$i]){
					$output .=" X ";
				} else {
					$output .= " - ";
				}
			}
			$output .= "\n";
		}

		return $output;
	}

}

?>
