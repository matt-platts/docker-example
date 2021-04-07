
/*
 * @Function pringGrid
 * @Meta print a blank grid to the page. There's no information on where the ships are stored on the fornt end//
*/
function printGrid(){

	var y=0;
	var letters= Array("A","B","C","D","E","F","G","H","I","J");

	// top row of column identifier blocks 0-9
	for (i=0;i<10;i++){
		var el = document.createElement("div");
		var cl = document.createAttribute("class");
		var t = document.createTextNode(i);
		cl.value="block identifier";
		el.setAttributeNode(cl);
		el.appendChild(t);
		document.getElementById("grid").appendChild(el);
		
	}

	for (i=0;i<100;i++){

		var x = i.toString();
		if (i>10){
			x = x.charAt(x.length-1);
		}

		var el = document.createElement("div");
		var cl = document.createAttribute("class");
		cl.value = "block";
		if (i % 10 == 0){
			if (i!=0){
				y++; // increment y - this should be done for the current block - ie before its id is placed
			}

			// insert left column row identifier - A-J
			var id_el = document.createElement("div");
			var id_cl = document.createAttribute("class");
			var t = document.createTextNode(letters[y]);
			id_cl.value="block identifier rowstart";
			id_el.setAttributeNode(id_cl);
			id_el.appendChild(t);
			document.getElementById("grid").appendChild(id_el);

		}
		var id = document.createAttribute("id");
		id.value= letters[y] + "-" + x;
		el.setAttributeNode(id);
		el.setAttributeNode(cl);


		document.getElementById("grid").appendChild(el);
	}
}


$(document).ready(function(){
	printGrid();

	/*
	 * @Function .block click
	 * @Meta - get the result and update the view accordingly
	*/
	$(".block").click(function(){
		co_ords=$(this).attr("id").replace("-","");
		$(this).css("background-color","orange");
		var that = $(this);

		data = new Object();
		data.xy=co_ords;
		data.show=1;
		data.action="fire";

		$.ajax({
			url: 'battleships.php',
			data: data,
			method: 'get',
			dataType: 'json',
			success: function(response){
				if (response.hit_value=="1"){
					that.css("background-color","red");
				} else {
					that.css("background-color","#eee");
				}

				
				$("#info").html(response.message);
				if (response.win_message){
					$("#info").html(response.message + "<br /><br />" + response.win_message);
				}
				if ($.urlParam('show')==1){
					$("#info").html($("#info").html() + "<pre>" + response.grid + "</pre>");
				}
	
			},
			error: function(x,y,z){
				alert("Server or connection error");	
			}
		});
	});
});

/*
 * @Function newGame
 * @Meta call the reset action on the back end, for now just perform a reload in the browser
*/
function newGame(){
	$.ajax({
		url: "battleships.php?action=new_game",
		method: "GET",
		dataType: 'json',
		success: function(){
			location.reload();
		},
		error: function(){
			alert("Error reloading");
		}
	});
}

/*
 * Extend jquery to get url paramaters
*/
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}
