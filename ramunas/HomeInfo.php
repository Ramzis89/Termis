<?php

$graph = $_GET['graph'];
if(strlen($graph) > 2)
{
	$date = new DateTime("now");
	$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
	$season_time = $date->format('P')." hour";
	$season_time = substr($season_time, 2, 1);


	 $day = 1;
	  $days = floor($day);
	  $hours = $season_time;
	  $str = "+".$hours." hour, -".$days." day";
	$sekundes = strtotime($str);

	$grafikas = "./duomenys/".$graph;


	$fp = @fopen($grafikas, 'r');
	$i = 0;
	 while (($linex = fgets($fp)) !== false) {
	 $line = explode("|", $linex);
	  if($line[1] > $sekundes)
	  {
		$datay[$i] = $line[0];
		$i++;

	  }
	}
	fclose($fp);
	$array_size = $i;

//Triuksmo salinimas
for($cnt = 2; $cnt < count($datay)-3; $cnt++)
  {
    if(abs($datay[$cnt]-$datay[$cnt-1]) > 0.6)//$ydata0[$cnt-1]*0.07)//Randamas triuksmo
    {
    //echo PHP_EOL.$cnt." ";
      if(abs($datay[$cnt+1]-$datay[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei du taskai triuksme
      {
        if(abs($datay[$cnt+2]-$datay[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei trecias->tai taip turi but
        {
          
        }
        else//Taisom du taskus
        {
          $datay[$cnt] = $datay[$cnt-1];
          $datay[$cnt+1] = $datay[$cnt-1];
        }
      }
      else//Taisom viena taska
      {
        $datay[$cnt] = $datay[$cnt-1];
      }
     }

  }
  //Pabaiga

	$vid_sk = floor(($i-1)/144);
	for($cnt = 144; $cnt > 0; $cnt--)
	{
	  $vid = 0;
	  for($cntv = 0; $cntv < $vid_sk; $cntv++)
	  {
	  //if($datay[$array_size-$cnt*$vid_sk-$cntv] > $vid)
		$vid = $datay[$array_size-$cnt*$vid_sk-$cntv];
	  }

	  //$tValue = intval($vid);
	  //$text = $text.$tValue." ";
	  $text = $text.number_format($vid, 2)."|";
	}
	$text = $text.PHP_EOL;
	echo $text;
}
?>