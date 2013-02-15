<?php
session_start();
if(!isset($_SESSION["myusername"])){
header("location:/Logout.php");//clean session and return to index.php
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("../includes/imports.php"); ?>

<title>BetaHomes</title>
</head>
<body>
<div id="wrapper">
	<div class="container" style="border-style:groove;">

	<?php include("../includes/header.php"); ?>


	<center>
	<!-- START BODY OF CONTAINER WITHIN CENTER TAG -->
	<div id="divBorder" style="text-align:left;border-style:none;">

	<h1>Z-Wave: The New Standard in Wireless Remote Control</h1>
	<div id="lipsum"><br />
	Z-Wave makes any home a &ldquo;smart home&rdquo; &ndash; quickly, easily and affordably!<br />
	<br />
	Z-Wave is a next-generation wireless ecosystem that lets all your home electronics talk to each other, and to you, via remote control. It uses simple, reliable, low-power radio waves that easily travel through walls, floors and cabinets. Z-Wave control can be added to almost any electronic device in your house, even devices that you wouldn't ordinarily think of as &quot;intelligent,&quot; such as appliances, window shades, thermostats and home lighting. &nbsp;&nbsp;&nbsp; <br />
	<br />
	<div align="center"><img width="637" height="303" src="/images/homecontrol_graphic.png" alt="" /><br />
	</div>
	<p></p>
	<table>
		<tbody>
			<tr>
				<td>Z-Wave unifies all your home electronics into an integrated wireless network, with no complicated programming and no new cables to run. Any Z-Wave enabled device can be effortlessly added to this network, and many non-Z-Wave devices can be made compatible by simply plugging them into a Z-Wave accessory module. In seconds, your device gets joined to the network and can communicate wirelessly with other Z-Wave modules and controllers.<strong><br />
				<br />
				</strong>And Z-Wave lets you control these devices in ways that give you complete command even when you're not at home yourself. You can control your Z-Wave household remotely from a PC and the Internet from anywhere in the world...even through your cell phone! <br />
				<br />
				</td>
				<td><br />
				</td>
			</tr>
		</tbody>
	</table>
	<p> </p>
	<strong>&bull; Z-Wave Is Simple</strong> &ndash; Z-Wave control is easily added to almost any device in minutes. Simply plug the device you want to control into a Z-Wave module, and &quot;join&quot; it to your Z-Wave network!<br />
	<br />
	<strong>&bull; Z-Wave Is Modular</strong> &ndash; With Z-Wave, you can add as much or as little home control as you want over time.&nbsp; You can add Z-Wave to a device, a room, a floor or the entire home, according to your needs and desires.<br />
	<br />
	<strong>&bull; Z-Wave Is Affordable</strong> &ndash; Unlike costly whole-home control systems that need special wiring and professional installation, Z-Wave is accessible and easy for the do-it-yourselfer.<br />
	<br />
	<strong>&bull; Z-Wave Is Powerful</strong> &ndash; Z-Wave's intelligent mesh networking 'understands&quot; the present status of any enabled device, and gives you confirmation that your devices have received the automatic or manual control commands you want.<br />
	<br />
	<strong>&bull; Z-Wave Is Versatile</strong> &ndash; Z-Wave can be added to almost anything in your home that uses electricity, and gives you the power to control or monitor them from your home or away from home.<br />
	<br />
	<strong>&bull; Z-Wave Is Intelligent</strong> &ndash; Z-Wave enabled devices can work together as a team.&nbsp; Have your garage door turn on your house lights when you come home. Have your door locks notify you when your children arrive home from school. Turn your downstairs lights off from upstairs.&nbsp; Create your own intelligent control &quot;scenes&quot; with Z-Wave!<br />
	<p> </p>
	<br />
	Z-Wave delivers on <u>all</u> the promises of the wired home, and opens up exciting new possibilities for the home of the future.  And that future is here now, because hundreds of Z-Wave enabled products are already widely available, from some of the best-known brands that you already know and trust.  Take a few moments to browse through this site and better acquaint yourself with Z-Wave.  Get to know how Z-Wave makes your home -- and your life -- safer, more secure, more economical, more convenient and more enjoyable!
	<p>&nbsp;</p>
	</div>

	</div>
	<!-- END BODY OF CONTAINER WITHIN CENTER TAG -->
	</center>

	</div><!-- end .container -->
	<div class="push"></div>
</div><!-- end #wrapper -->
<?php include("../includes/footer.php"); ?>

</body>
</html>
