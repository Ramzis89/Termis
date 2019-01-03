<?php // content="text/plain; charset=utf-8"

require_once ('./jpgraph/jpgraph.php');
require_once ('./jpgraph/jpgraph_line.php');
require_once ('./jpgraph/jpgraph_flags.php');
require_once ('./jpgraph/jpgraph_iconplot.php');
require_once ('./jpgraph/jpgraph_date.php');
require_once ('./jpgraph/jpgraph_scatter.php');
require_once ('./jpgraph/jpgraph_regstat.php');

$days = 1;
$show = 1;

$start = $_GET['start'];
$end = $_GET['end'];
$name = $_GET['name'];
$name1 = $_GET['name1'];
$file = $_GET['file'];
$file1 = $_GET['file1'];
$unify = $_GET['unify'];

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";
if($end == "")
  $end = intval(strtotime($season_time));


//echo "'".$days."'";
if($days == '')
  $days = 1;
if($file == "")
 echo "Nera failo!";
 else
 {
 
$fp = @fopen($file, 'r'); 
if ($fp) {
   $data = explode("\n", fread($fp, filesize($file)+1));
   fclose($fp);
}


$dat_split = explode("|", $data[count($data)-2]);
$min = $dat_split[0];
$max = $dat_split[0];
$file_count = 0;
if (count($data) > 1) {
   for($i = 0; $i < count($data)-1; $i++)
   {
      $dat_split = explode("|", $data[$i]);
      
      if($dat_split[1] > $start && $dat_split[1] < $end)
      {
        $min = $dat_split[0];
        $max = $dat_split[0];
        $file_count++;
      }
   }
  }
  $mastelis = intval($file_count/15840)+1;
//----------------Pirmas grafikas-----------------
$j = 0;
if (count($data) > 1) {
   for($i = 0; $i < count($data)-1; $i+=$mastelis)
   {
      $dat_split = explode("|", $data[$i]);
      
      if($dat_split[1] > $start && $dat_split[1] < $end)
      {
        $datay0[$j] = $dat_split[0];
        $datax[$j] = $dat_split[1];        
        $j = $j + 1;
        if($dat_split[0] > $max)
          $max = $dat_split[0];
          
        if($dat_split[0] < $min)
          $min = $dat_split[0];
      }
   }
}
//Filtravimas
$ampl = $max-$min;
if($ampl > 10)
  $vidur = 1;
else if($ampl > 4)
  $vidur = 4;
else if($ampl > 0)
  $vidur = 9;
  

$array_size = $j-2; 
if(($vidur > 1) && ($array_size > 128))
for($cnt = 0; $cnt < $array_size-$vidur; $cnt++)
{
   for($k = 1; $k < $vidur+1; $k++)
   {
      $datay0[$array_size-$cnt] += $datay0[$array_size-$cnt-$k];
   }
   $datay0[$array_size-$cnt] /= $vidur+1;
}

//------------------------------------------------
if(strlen($file1) > 5)
{
  $fp = @fopen($file1, 'r'); 
  if ($fp) {
    $data1 = explode("\n", fread($fp, filesize($file1)+1024));
    fclose($fp);
  }
$file1_count = 0;
$dat_split1 = explode("|", $data1[count($data1)-2]);
$min1 = $dat_split1[0];
$max1 = $dat_split1[0];

if (count($data1) > 1) {
   for($i = 0; $i < count($data1)-1; $i++)
   {
      $dat_split1 = explode("|", $data1[$i]);
      
      if($dat_split1[1] > $start && $dat_split1[1] < $end)
      {
        $min1 = $dat_split1[0];
        $max1 = $dat_split1[0];
        $file1_count++;
      }
   }
 }
$mastelis1 = intval($file1_count/15480)+1;
//----------------Antras grafikas-----------------
$k = 0;
if (count($data1) > 1) {
   for($i = 0; $i < count($data1)-1; $i+=$mastelis1)
   {
      $dat_split1 = explode("|", $data1[$i]);
      
      if($dat_split1[1] > $start && $dat_split1[1] < $end)
      {
        $datay1[$k] = $dat_split1[0];
        $datax1[$k] = $dat_split1[1]; 
        $k++;
        if($dat_split1[0] > $max1)
          $max1 = $dat_split1[0];
          
        if($dat_split1[0] < $min1)
          $min1 = $dat_split1[0];
      }
   }
}

//Filtravimas
$ampl1 = $max1-$min1;
if($ampl1 > 10)
  $vidur1 = 1;
else if($ampl1 > 4)
  $vidur1 = 4;
else if($ampl1 > 0)
  $vidur1 = 9;
  
  
$array_size1 = $k-2; 
if(($vidur1 > 1) && ($array_size1 > 128))
for($cnt = 0; $cnt < $array_size1-$vidur1; $cnt++)
{
   for($i = 1; $i < $vidur1+1; $i++)
   {
      $datay1[$array_size1-$cnt] += $datay1[$array_size1-$cnt-$i];
   }
   $datay1[$array_size1-$cnt] /= $vidur1+1;
}
}
//------------------------------------------------
if(strlen($name1) > 0)
{
  $name1 = " ".$name1."(žalias) ".number_format($datay1[$k-1], 1)."°C ";
  if($max1 > $max)
    $max = $max1;
  if($min1 < $min)
    $min = $min1;
}
else
{
  $name1 = "";
}

$min = floor((intval ($min) / 5)) * 5;
$max = floor((intval ($max) / 5)+1) * 5;

// Setup the graph
$graph = new Graph(900,600);	
$graph->SetMargin(50,50,5,120);
if($unify == 1)
  $graph->SetScale("datlin", $min, $max, 0, 0);
else
  $graph->SetScale("datlin");
$graph->title->Set($name."(raudonas) ".number_format($datay0[$j-1], 1)."°C ".$name1.date('Y-m-d H:i:s', $datax[$j-1]));
$graph->yaxis->SetColor('lightred','darkred');

$p1 = new LinePlot($datay0, $datax);
$p1->SetColor("darkred");
$graph->xaxis->SetLabelAngle(90);
$graph->Add($p1);


if(count($datay1) > 0)
{
  $p2 = new LinePlot($datay1, $datax1);
  $p2->SetColor('chartreuse4');
  $graph->AddY(0,$p2);
  if($unify == 1)
    $graph->SetYScale(0,'lin', $min, $max);
  else
    $graph->SetYScale(0,'lin');
  $graph->ynaxis[0]->SetColor('chartreuse4');
}

$graph->Stroke();
}

?>