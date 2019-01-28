<?php
session_start();
?>
<html>
<head>
    <link class="include" rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.min.css" />
    <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="./jqplot/excanvas.js"></script><![endif]-->
    <script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script class="include" type="text/javascript" src="./jqplot/jquery.jqplot.min.js"></script>
    <script class="include" type="text/javascript" src="./jqplot/plugins/jqplot.cursor.min.js"></script>
    <script type="text/javascript" src="./jqplot/plugins/jqplot.dateAxisRenderer.js"></script>
    <script type="text/javascript" src="./jqplot/plugins/jqplot.highlighter.js"></script>
    <script type="text/javascript" src="./jqplot/plugins/jqplot.canvasTextRenderer.js"></script>
    <script type="text/javascript" src="./jqplot/plugins/jqplot.canvasAxisTickRenderer.js"></script>
    
<script type="text/javascript" src="./jqplot/plugins/jqplot.cursor.js"></script>
</head>
<body>
<?php // content="text/plain; charset=utf-8"

//require_once ('./jpgraph/jpgraph.php');
//require_once ('./jpgraph/jpgraph_line.php');
//require_once ('./jpgraph/jpgraph_flags.php');
//require_once ('./jpgraph/jpgraph_iconplot.php');
//require_once ('./jpgraph/jpgraph_date.php');
//require_once ('./jpgraph/jpgraph_scatter.php');
//require_once ('./jpgraph/jpgraph_regstat.php');

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$date = new DateTime("now");
$date->setTimezone(new DateTimeZone('Europe/Vilnius'));
$season_time = $date->format('P')." hour";
$season_time = substr($season_time, 0, 3)." hour";

$season_time = "0 hour";
if($end == "")
  $end = intval(strtotime($season_time));

$show = 1;
$max = -99999;
$min = 99999;

$start = $_GET['start'];
$end = $_GET['end'];
$unify = $_GET['unify'];
$file_cnt = $_GET['cnt'];
$usr = $_SESSION["usr"];
$files = scandir("./".$usr."/duomenys/");

echo "<div class=\"jqPlot\" id=\"chart1\" style=\"height:600px; width:900px;\"></div><script type=\"text/javascript\">"; 

for($file_i = 0; $file_i< $file_cnt; $file_i++)
{
 $name = $_GET['name'.$file_i.''];
 $file = $_GET['file'.$file_i.''];

if($file == "")
 echo "Nera failo!";
 else
 {
 
$fp = @fopen($file, 'r'); 
if ($fp) {
   $line = fgets($fp);//explode("\n", fread($fp, filesize($file)+1));
}

if($line === false)
{
	fclose($fp);
	continue;
}

$dat_split = explode("|", $line);//[count($data)-2]
$min1 = $dat_split[0];
$max1 = $dat_split[0];
//---------------------------------------------
//$time_start = microtime_float();
if(((intval(strtotime($season_time)) - $end) < 120) && ($end - $start - 24*3600 < 120))
{
  if(filesize($file) > 30000)
    fseek($fp, -30000, SEEK_END);
}
//---------------------------------------------
$file_count = 0;
if (true) {//count($data) > 1
   while($line !== false)
   {
      $dat_split = explode("|", $line);
      //echo $line;
      if($dat_split[1] > $start && $dat_split[1] < $end)
      {
        $min1 = $dat_split[0];
        $max1 = $dat_split[0];
        $data[$file_count] = $line;
        $file_count++;
      }
	  $line = fgets($fp);
   }
  }
  fclose($fp);
//-------------------------------------------------------
  //$time_end = microtime_float();
//$time = $time_end - $time_start;
//-------------------------------------------------------
  $mastelis = intval($file_count/15840)+1;
//----------------Grafiko duomenys-----------------
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
      }
   }
   $dat_split = explode("|", $data[count($data)-1]);
   $lasty = $dat_split[0];
   $lastx = $dat_split[1];
   unset($data);
   //------------Triuksmo salinimas----------------
  for($cnt = 2; $cnt < count($datay0)-3; $cnt++)
  {
    if(abs($datay0[$cnt]-$datay0[$cnt-1]) > 0.6)//$ydata0[$cnt-1]*0.07)//Randamas triuksmo
    {
    //echo PHP_EOL.$cnt." ";
      if(abs($datay0[$cnt+1]-$datay0[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei du taskai triuksme
      {
        if(abs($datay0[$cnt+2]-$datay0[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei trecias->tai taip turi but
        {
          
        }
        else//Taisom du taskus
        {
          $datay0[$cnt] = $datay0[$cnt-1];
          $datay0[$cnt+1] = $datay0[$cnt-1];
        }
      }
      else//Taisom viena taska
      {
        $datay0[$cnt] = $datay0[$cnt-1];
      }
     }
     if($datay0[$cnt] > $max)
       $max = $datay0[$cnt];
          
     if($datay0[$cnt] < $min)
       $min = $datay0[$cnt];
  }
}
//------------------------------------------------
//Filtravimas
$ampl = $max1-$min1;
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
$datax[$array_size+3] = $lastx;
$datay0[$array_size+3] = $lasty;
//------------------------------------------------
echo "var Failas".$file_i." = [";
for($i=0; $i < $array_size+4; $i++)
{
echo "['".date('Y-m-d H:i:s', $datax[$i])."',".$datay0[$i]."],";
}
echo "];   
";
}
 if($max1 > $max)
    $max = $max1;
 if($min1 < $min)
    $min = $min1;
}

echo "</script>";
//------------------------------------------------
unset($datax);
unset($datay0);
if($min < 0 && $min > -1)
$min = $min - 1;
$min = floor(($min / 5)) * 5;
$max = floor(($max / 5)+1) * 5;

//echo "<font color=white>".$name."(mėlynas) ".number_format($datay0[$j-1], 1)."°C ".$name1.date('Y-m-d H:i:s', $datax[$j-1])."</font>";
 

  
  echo "<script type=\"text/javascript\" class=\"code\">
  var colors = [ '#4bb2c5', '#EAA228', '#c5b47f', '#579575', '#839557', '#958c12', '#953579', '#4b5de4',
  '#d8b83f', '#ff5800', '#0085cc', '#c747a3', '#cddf54', '#FBD178', '#26B4E3', '#bd70c7'];
      $(document).ready(function(){
    
    
      targetPlot = $.jqplot('chart1', [";
      for($file_i = 0; $file_i< $file_cnt; $file_i++)
      {
         echo "Failas".$file_i.", ";
      }
      echo "], {
        axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
          formatString:'%Y-%m-%d %H:%M:%S',
                textColor: '#ffffff'
                },
        },
        yaxis:{";
        if($unify == 1)
        {        
          echo "min:";echo $min.", max:";echo $max.",";
        }
        echo "
        tickOptions:{
          formatString:'%.2f°C',
          ";
          if($unify != 1)
          echo "textColor:colors[0]";
        echo "}
        },";
        for($file_i = 2; $file_i< $file_cnt+1; $file_i++)
        {
         echo "
         y".$file_i."axis:{";
        if($unify == 1)
        {        
          echo "min:";echo $min.", max:";echo $max.",";
        }
        echo "
        tickOptions:{
          formatString:'%.2f°C',
          textColor:colors[".($file_i-1)."],
        }},";
        }
        echo "
      },
        seriesDefaults:{ rendererOptions: {
                smooth: true,
                animation: {
                    show: false
                }
            },
            showMarker: false },
        series:[
          {label:'Kambario'},";
        if($unify != 1)
          for($file_i = 2; $file_i< $file_cnt+1; $file_i++)
          {
            echo "{label:'Name".$file_i."', yaxis:'y".$file_i."axis'},";
          }
        echo "],
        cursor:{
          show: true,
          zoom:true,
          showTooltip:true
        },
        highlighter: {
        show: true,
        sizeAdjust: 7.5
        },
        \"legend\":{
        \"labels\":[";
        for($file_i = 0; $file_i< $file_cnt; $file_i++)
        {
           $name = $_GET['name'.$file_i.''];
           echo "\"".$name."\",";
        }
         echo "],
          \"show\":true,
          placement: \"outsideGrid\",
          \"rendererOptions\":{
            \"numberRows\":1
          },
         \"renderer\":$.jqplot.EnhancedLegendRenderer
        },    
        axesDefaults: {
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
        tickOptions: {
          angle: -30,
          fontSize: '10pt',
          textColor: '#ffffff'
        }
    },
          
      }); 
      
      
      $.jqplot.Cursor.zoomProxy(targetPlot, controllerPlot);
      $.jqplot._noToImageButton = false;
            
    });
</script>";

?>

</body>


</html>
