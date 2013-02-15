
<script>
//***LOCAL TIME
function timerCust()
{	
	var currentTime = new Date;
	var currentHour = currentTime.getHours();
	var currentMinute = currentTime.getMinutes();
	var currentSecond = currentTime.getSeconds();
	
	$('#hour').text(currentHour);
	$('#minute').text(currentMinute);
	$('#second').text(currentSecond);
	
}

$(document).ready(function() {
	setInterval(timerCust, 100);
	setInterval(currTime, 100);
});

//***SEVER TIME
function currTime()
{	

	$.post("/includes/timer.php", { action: "currTime"},
		function(data) {
			//alert("Data Loaded: " + data);
			$('#currTime').html(data);
		});
}


</script>

<style>
.timer { font-family:Ti89pc;font-weight:bold;display:inline; }
</style>

<table width="100%"><tr>
<td width="360px">
<div class="header"><a href="/main.php" style="background:url(/images/betaHomes.png) no-repeat;display:block;width:354px;height:101px;"></a> 
    <!-- end .header --></div>
</td>

<td width="260px" align="center">
<div class="timer">Local Time</div>
<table>
	<tr>
		<td><div class="timer" id="hour"></div></td>
		<td>:</td>
		<td><div class="timer" id="minute"></div></td>
		<td>:</td>
		<td><div class="timer" id="second"></div></td>
	</tr>
</table>
</td>

<td width="260px" align="center">
<div class="timer">Server Time</div>
<table>
	<tr>
		<td><div class="timer" id="currTime"></div></td>
	</tr>
</table>
</td>


<td align="right" style="margin-top:30px; padding-right:20px;" width="450px">
<button title="Dead zone. No Reception." style="height:0;width:0; cursor: none;"> </button>
<button onClick="logout()">Logout</button>
<button onClick="about()">About Z-Wave</button>
</td>
<td width="50px" style="padding-right:20px;" ><a href="/bird.php" style="background:url(/images/singing_bird.png) no-repeat;display:inline-table;width:90px;height:61px;"></a>

</td>
</tr>
</table>



<?php

$currentFile = $_SERVER["PHP_SELF"];    
$parts = Explode('/', $currentFile);    
$page = $parts[count($parts) - 1];

if($page == "main.php"){
$headerMenu = <<<ETF

<header>
<nav>
	<ul id="menu">
		<li style="background:#3081c8;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#3081c8';"><a href="#" class="nav1">Home</a></li>
		<li style="background:#8cbd20;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;margin-top:10px;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#8cbd20';"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li style="background:#f09e2f;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;margin-top:10px;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#f09e2f';"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li style="background:#b530ba;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;margin-top:10px;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#b530ba';"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li style="background:#30b6c9;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;margin-top:10px;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#30b6c9';"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end" style="background:#8cbd20;border-radius:4px 4px 0 0;-moz-border-radius:4px 4px 0 0;-webkit-border-radius:4px 4px 0 0;margin-top:10px;" onmouseover="this.style.background='#323232';" onmouseout="this.style.background='#8cbd20';"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;

}else if($page == "rules.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li><a href="#" class="nav2">Rules</a></li>
		<li style="margin-top:10px;"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li style="margin-top:10px;"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li style="margin-top:10px;"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end" style="margin-top:10px;"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;

}else if($page == "schedules.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li style="margin-top:10px;"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li><a href="#" class="nav3">Schedules</a></li>
		<li style="margin-top:10px;"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li style="margin-top:10px;"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end" style="margin-top:10px;"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;

}else if($page == "groups.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li style="margin-top:10px;"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li style="margin-top:10px;"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li><a href="#" class="nav4">Groups</a></li>
		<li style="margin-top:10px;"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end" style="margin-top:10px;"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;

}else if($page == "map.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li style="margin-top:10px;"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li style="margin-top:10px;"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li style="margin-top:10px;"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li><a href="#" class="nav5">Map</a></li>
		<li class="end" style="margin-top:10px;"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;


}else if($page == "settings.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li style="margin-top:10px;"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li style="margin-top:10px;"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li style="margin-top:10px;"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li style="margin-top:10px;"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end"><a href="#" class="nav2">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;

}else if($page == "about.php"){
$headerMenu = <<<ETF
<header>
<nav>
	<ul id="menu">
		<li style="margin-left:auto;margin-top:10px;"><a href="/main.php" class="nav1" style="line-height:45px;">Home</a></li>
		<li style="margin-top:10px;"><a href="/pages/rules.php" class="nav2" style="line-height:45px;">Rules</a></li>
		<li style="margin-top:10px;"><a href="/pages/schedules.php" class="nav3" style="line-height:45px;">Schedules</a></li>
		<li style="margin-top:10px;"><a href="/pages/groups.php" class="nav4" style="line-height:45px;">Groups</a></li>
		<li style="margin-top:10px;"><a href="/pages/map.php" class="nav5" style="line-height:45px;">Map</a></li>
		<li class="end" style="margin-top:10px;"><a href="/pages/settings.php" class="nav2" style="line-height:45px;">Settings</a></li>
	</ul>
</nav>
</header>
<br>
ETF;
echo $headerMenu;


}else{
	echo "<br>";
}





?>