<!DOCTYPE html>
<HTML>
<HEAD>
 <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
 <link rel="stylesheet" href="include/style.css" type="text/css">


	<TITLE>Detail Seances</TITLE>
</HEAD>

<script type="text/javascript">
var fseuil=150; 

function compute_if_tss() {

var fmoy=parseInt(document.getElementsByName('fmoy')[0].value); 
var IF=fmoy/fseuil; 
console.log(IF); 
    document.getElementById("if").innerHTML=parseFloat(Math.round(IF * 100) / 100).toFixed(2);
var duration=document.getElementsByName('duration')[0].value; 
var dur_in_s=parseInt(duration.split(":")[0]*3600)+parseInt(duration.split(":")[1]*60)+parseInt(duration.split(":")[2]) ; 
var TSS=(dur_in_s*fmoy*IF)/(fseuil*3600)*100 ;
   document.getElementById("tss").innerHTML=parseFloat(Math.round(TSS * 100) / 100).toFixed(2);
console.log(TSS) ;
}
	
</script>



<?php
/* Connecting, selecting database */
   define('PUN_ROOT', './');
require PUN_ROOT.'config2.php';
require PUN_ROOT.'include/fonctions.php';
$link=connect_db($db_host, $db_username, $db_password, $db_name);
if(isset($_POST['seance_id']) && !empty($_POST['seance_id']))
{
	$seance_id = $_POST['seance_id'];

	$query_sport = "select seance_id, name, sport_name, date, calories, distance, duration,
	fat_consumption, above, average, below, in_zone, lower, maximum,
	upper, Vaverage, Vmaximum , altitude, link
	from seances, sport_type
	Where seances.sport_id = sport_type.sport_id
	AND seance_id='$seance_id';";
//	print $seance_id ;
//	print $query_sport ;
	$result_seance = mysqli_query($link,$query_sport) or die("La requete seance a
 echouee");
	
	while ($row_seance= mysqli_fetch_array($result_seance, MYSQLI_NUM))
	{
		$name = $row_seance[1];
		$sport = $row_seance[2];
		$date = $row_seance[3];
		$calories = $row_seance[4];
		$distance = $row_seance[5];
		$duration = $row_seance[6];
		$fat_consumption = $row_seance[7];
		$above = $row_seance[8];
		$average = $row_seance[9];
		$below = $row_seance[10];
		$in_zone = $row_seance[11];
		$lower = $row_seance[12];
		$maximum = $row_seance[13];
		$upper = $row_seance[14];
		$Vaverage = $row_seance[15];
		$Vmaximum = $row_seance[16];
		$altitude = $row_seance[17];
		$url = $row_seance[18];
		$link_slash = htmlspecialchars($row_seance[18]);
			
	}
}
else
{
	$date=date("Y/m/d");
}

$sportname=str_replace(' ','_',$sport);
printf("
<body onload=\"compute_if_tss()\"><div  id=\"%s\">",$sportname );
printf("
<H1>Detail Seances</H1>

	<form action=\"enr_seance.php\" method=\"post\">");
 
printf("<input type=\"hidden\" name=\"seance_id\" value=\"%s\">",$seance_id);
print"	<TABLE>
	<TR  >
		<TD>
		<TABLE>	

<TR ><TD> Nom de la seance:</TD>";
printf("<TD> <Input name=\"seance_name\" type=\"TEXT\" size=\"30\" value=\"%s\"></TD>
</TR>",$name);

print"<TR ><TD> Sport:</TD><TD><SELECT NAME=\"sport_id\">

<option>";


 $query_sport = "SELECT * FROM sport_type ORDER BY sport_name";
 $result_sport = mysqli_query($link,$query_sport) or die("La requete sport a
 echouee");
 
   
while ($row = mysqli_fetch_array($result_sport, MYSQLI_NUM))
         {
if ( $row[1] == $sport )
{
printf("<OPTION value=%d selected>",$row[0]);
printf("%s",  $row[1] );
print"</OPTION>";
}
else
{
printf("<OPTION value=%d>",$row[0]);
printf("%s",  $row[1] );
print"</OPTION>";
}
	 } ?></SELECT> </TD>
</TR>
<TR ><TD> Date:</TD><TD> 
<?php
printf("<Input name=\"date\" type=\"date\" value=%s size=\"8\"></TD>",$date);
print"
</TR>
<TR ><TD> Duree:</TD><TD> <Input name=\"duration\" type=\"time\" step=\"1\" value=\"$duration\" onchange=\"compute_if_tss()\" /></TD>
</TR>
<TR ><TD> Lower:</TD><TD> <Input name=\"lower\" type=\"int\" size=\"8\" value=\"$lower\"></TD>
</TR>
<TR ><TD> Upper:</TD><TD> <Input name=\"upper\" type=\"int\" size=\"8\" value=\"$upper\"></TD>
</TR>
<TR ><TD> Above:</TD><TD> <Input name=\"above\" type=\"time\" step=\"1\" value=\"$above\"/></TD>
</TR>
<TR ><TD> Below:</TD><TD> <Input name=\"below\" type=\"time\" step=\"1\" value=\"$below\" /></TD>
</TR>
<TR ><TD> In zone:</TD><TD> <Input name=\"in_zone\" type=\"time\" step=\"1\" value=\"$in_zone\" /></TD>
</TR>
<TR ><TD> Fmax:</TD><TD> <Input name=\"fmax\" type=\"int\" size=\"8\" value=\"$maximum\"></TD>
</TR>
<TR ><TD> Fmoy:</TD><TD> <Input name=\"fmoy\" type=\"int\" size=\"8\" value=\"$average\" onchange=\"compute_if_tss()\" ></TD>
</TR>
<TR ><TD> Calorie:</TD><TD><Input name=\"cal\" type=\"int\" size=\"5\" value=\"$calories\"> </TD>
</TR>
<TR ><TD> % gras:</TD><TD> <Input name=\"fat\" type=\"int\" size=\"2\" value=\"$fat_consumption\"></TD>
</TR>
<TR ><TD> Distance:</TD><TD><Input name=\"dist\" type=\"int\" size=\"5\" value=\"$distance\"></TD>
</TR>
<TR ><TD> Vmoy:</TD><TD> <Input name=\"vmoy\" type=\"int\" size=\"8\" value=\"$Vaverage\"></TD>
</TR>
<TR ><TD> Vmax:</TD><TD> <Input name=\"vmax\" type=\"int\" size=\"8\" value=\"$Vmaximum\"></TD>
</TR>
<TR ><TD> Deniv:</TD><TD> <Input name=\"altitude\" type=\"int\" size=\"3\" value=\"$altitude\"></TD>
</TR>
<TR ><TD> IF:</TD><TD><p id=\"if\" ></p></TD>
</TR>
<TR ><TD> TSS:</TD><TD> <p id=\"tss\" ></p></TD>
</TR>
</TABLE></TD><TD> $url</TD></TR></TABLE><TABLE>
<TR ><TD> link:</TD><TD> <textarea name=\"url\" cols=\"60\" rows=\"8\" class=\"widebox\" id=\"url\"  >$url</textarea></TD>
</TR>
<TR ><TD><input type=\"radio\" name=\"action\" value=\"insert\"> Insert</TD>
<TD>			
<input type=\"radio\" name=\"action\" value=\"update\" checked> update</tD>
<TD>			
<input type=\"radio\" name=\"action\" value=\"delete\"> delete</tD>
</TR>
<TR >
<TD> <INPUT TYPE=\"RESET\" VALUE=\"Effacer\"></TD><TD><INPUT TYPE=\"SUBMIT\" VALUE=\"Enregistrer\"></form></TD>
</TR></table>
";
// style="width:97%; font-size:0.8 em;"

/* Free resultset */
 mysqli_free_result($result_seance);
 mysqli_free_result($result_sport);
 /* Closing connection */
  mysqli_close($link);
?>
</div>
</BODY>
</HTML>
