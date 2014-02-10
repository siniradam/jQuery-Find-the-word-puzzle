<!DOCTYPE html> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>OhShiftLabs</title>
<meta http-equiv="Content-Language" content="tr">
<meta name="description" content="Bildiğin kelime bulma puzzle'ı işte.">
<meta name="keywords" content="koray kırcaoğlu, puzzle, kelime bul, ohshift">
<style>

.status {
	float:left;
}

.puzzleholder {
	width:750px;
	height: 750px;
	float:left;
}

.puzzleholder .b {
	width:50px;
	height:50px;
	float: left;
	text-align: center;
	line-height: 50px;/*
	font-size:20pt;*/
	font-family: Helvetica;
	outline: 1px solid #56F06F;

	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.puzzleholder .b.selected {
	color:#fff;
	background: #000;
}

.puzzleholder .b.last{
	background-color: #cfd000;
}

.puzzleholder .b.found{
	background-color: #0FBB6F;
}

</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
<script>
	var wordList;
$(document).ready(function(){
	var lastclicked = "";
	var listclicked = Array();
	var word = "";
	var direction = "";

	$('.b').live('click', function() {
		var properties,nproperties;
		if(word==""){
			lastclicked	=	$(this);
			properties	=	lastclicked.attr("class").split(" ");
							lastclicked.addClass("selected");
							lastclicked.addClass("last");
			listclicked[listclicked.length]=lastclicked.attr("id");
			word+=			properties[3].replace("W","");//AddLetter

		}else{//Removing
			if($(this).attr("class")==lastclicked.attr("class")){
				lastclicked.removeClass("selected").removeClass("last");
				listclicked.pop();
				lastclicked = $("#"+listclicked[listclicked.length-1]);
				lastclicked.addClass("last");
				
				word =  word.slice(0, word.length-1);
				if(listclicked.length==1) direction="";

			}else if($(this).attr("id")==listclicked[listclicked.length-2]){
				//PreviousLetter

			}else{//NewLetterClicked
				word+= letterSelection(lastclicked,$(this));
				checkword(word);
			}
		}

//		console.log(word);
//		console.log(lastclicked);
//		console.log(listclicked);
	});

	function letterSelection(firstL,nextL){
		var Fprops = firstL.attr("class").split(" ");
		var Nprops = nextL.attr("class").split(" ");
		var nextWord = "";
		var able2add = false;

		var Line1 = parseInt(Fprops[1].replace("L",""));
		var Line2 = parseInt(Nprops[1].replace("L",""));

		var Column1 = parseInt(Fprops[2].replace("C",""));
		var Column2 = parseInt(Nprops[2].replace("C",""));

		var H = Line1-Line2;//Horizontal
		var V = Column1-Column2//Vertical

		if(direction==""){
			if((H==0) && (V==-1 || V==1)){
				direction = "H";
			}else if((V==0) && (H==-1 || H==1)){
				direction = "V";
			}else{
				direction = "X";
			}
		}//Direction


		if((H==0) && (V==-1 || V==1) && direction == "H"){//
			able2add = true;

		}else if((V==0) && (H==-1 || H==1) && direction == "V"){//
			able2add = true;

		}else if((H==-1) && (V==-1 || V==1) && direction == "X"){//direction = "X";
			able2add = true;

		}else if((H== 1) && (V==-1 || V==1) && direction == "X"){//direction = "X";
			able2add = true;
		}

		if(able2add){			
			$(".last").removeClass("last");

			nextWord 	= Nprops[3].replace("W","");
			lastclicked =	nextL;
							nextL.addClass("selected");
							nextL.addClass("last");

			listclicked[listclicked.length]=nextL.attr("id");
		}

		//Working //Can be used for boggle.
		//		if(H == 1 || H == -1 || H == 0){
		//			if(V == 1 || V == -1 || V == 0){

		//			}
		//		}

		return nextWord;
	}

	function checkword(search){
		var searchr = search.split("").reverse().join("");
		var pos = -1;

		if(wordList.indexOf(search)!=-1){
			pos = wordList.indexOf(search);
			s = search;

		}else if(wordList.indexOf(searchr)!=-1){
			pos = wordList.indexOf(searchr)
			s = searchr;
		}

		if(pos!=-1){//Found
			$(".status").append(s+" Bulundu! Tebrikler<br>");

			for(var i=0;i<listclicked.length;i++){
				$("#"+listclicked[i])
				.addClass("found")
				.removeClass("selected")
				.removeClass("last");
			}

			clearSelection();//ReturnToStart
			wordList.splice(pos, 1);

			console.log(pos);

			if(wordList.length==0){
				alert("Tüm kelimeler bulundu!");
			}
		}
	}

	function clearSelection(){
			lastclicked = "";
			listclicked = Array();
			word = "";
			direction = "";
	}



var letterlist = Array(
	"İ","Z","N","R","E","Z","A","L","E","T", 
	"M","R","İ","K","U","T","U","T","P","N", 
	"E","A","K","D","F","A","İ","A","L","O", 
	"C","T","L","Ş","C","H","H","T","J","K", 
	"A","Ş","İ","L","O","A","C","S","B","İ", 
	"İ","C","N","İ","L","L","E","Ü","T","V", 
	"H","V","T","I","O","R","F","B","P","N",
	"V","R","L","I","L","R","A","H","A","B", 
	"N","I","S","A","N","D","A","L","E","T", 
	"K","V","V","K","K","D","A","R","B","E"
	);

var wordlist = Array("ACEMİ","TARZ","REZALET","TUTUK",
	"SANDALET","PAHALILIK","DARBE","TAT");

	createPuzzle(10,10,letterlist,wordlist);

});

	function createPuzzle(w,h,list,wlist){

		var html="";
		var cls = "";
		wordList = wlist;
		//var total= w*h;
		wrd = 0;
		for(var i=1;i<=h;i++){//Line
			cls = "L"+i;
			for(var j=1;j<=w;j++){//Column
				html+="<div class=\"b "+cls+" C"+j+" W"+list[wrd]+"\" onclick=\"\" id=\"L"+wrd+"\">";
				html+=list[wrd];
				html+="</div>";
				wrd++;
				//"+cls+" C"+j+"
			}
		}

		$("#puzzleholder").css({
			width:(50*w),
			height:(50*h)
		}).html(html);

		$("#status").html("<b>Aramanız gerekenler;</b><br><ul>");
		wlist.forEach(function(w) {
    		$("#status").append("<li>"+w+"</li>");
		});
		$("#status").append("</ul>");

	}
</script>
</head>
<body>
<div class="main" id="main">
	
	<div id="puzzleholder" class="puzzleholder">
	</div>
	<div class="status" id="status">
		
	</div>

</div>
</body>
</html>