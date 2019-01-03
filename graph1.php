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
      }
   }
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
//------------------------------------------------
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
      }
   }
    //------------Triuksmo salinimas----------------
  for($cnt = 2; $cnt < count($datay1)-3; $cnt++)
  {
    if(abs($datay1[$cnt]-$datay1[$cnt-1]) > 0.6)//$ydata0[$cnt-1]*0.07)//Randamas triuksmo
    {
    //echo PHP_EOL.$cnt." ";
      if(abs($datay1[$cnt+1]-$datay1[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei du taskai triuksme
      {
        if(abs($datay1[$cnt+2]-$datay1[$cnt-1]) > 0.6)//$ydata1[$cnt-1]*0.07)//Jei trecias->tai taip turi but
        {
          
        }
        else//Taisom du taskus
        {
          $datay1[$cnt] = $datay1[$cnt-1];
          $datay1[$cnt+1] = $datay1[$cnt-1];
        }
      }
      else//Taisom viena taska
      {
        $datay1[$cnt] = $datay1[$cnt-1];
      }
     }
     if($datay1[$cnt] > $max1)
       $max1 = $datay1[$cnt];
          
     if($datay1[$cnt] < $min1)
       $min1 = $datay1[$cnt];
  }
//------------------------------------------------
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
  $name1 = " ".$name1."(oranžinis) ".number_format($datay1[$k-1], 1)."°C ";
  if($max1 > $max)
    $max = $max1;
  if($min1 < $min)
    $min = $min1;
}
else
{
  $name1 = "";
}
if($min < 0 && $min > -1)
$min = $min - 1;
$min = floor(($min / 5)) * 5;

$max = floor(($max / 5)+1) * 5;
//($name."(r$$$as) ".number_format($datay0[$j-1], 1)."°C ".$name1.date('Y-m-d H:i:s', $datax[$j-1]));

echo "<font color=white>".$name."(mėlynas) ".number_format($datay0[$j-1], 1)."°C ".$name1.date('Y-m-d H:i:s', $datax[$j-1])."</font>";
echo "
<div class=\"jqPlot\" id=\"chart1\" style=\"height:600px; width:900px;\"></div>

<script type=\"text/javascript\">  
var Kambaryje = [";

for($i=0; $i < $array_size+2; $i++)
{
echo "['".date('Y-m-d H:i:s', $datax[$i])."',".$datay0[$i]."],";
}
echo "];

var Radiatoriaus = [";
for($i=0; $i < $array_size+2; $i++)
{
echo "['".date('Y-m-d H:i:s', $datax1[$i])."',".$datay1[$i]."],";
}

echo "];

</script>

  
  <script type=\"text/javascript\" class=\"code\">
      $(document).ready(function(){
    
    
      targetPlot = $.jqplot('chart1', [Kambaryje, Radiatoriaus], {
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
          textColor: '#ccccff'
        }
        },
        y2axis:{";
        if($unify == 1)
        {        
          echo "min:";echo $min.", max:";echo $max.",";
        }
        echo "
        tickOptions:{
          formatString:'%.2f°C',
          textColor: '#ffc266'
        }
        }
      },
        seriesDefaults:{ rendererOptions: {
                smooth: true,
                animation: {
                    show: false
                }
            },
            showMarker: false },
        series:[
          {label:'Kambario'},
          {label:'Radiatoriaus', yaxis:'y2axis'},
          {label:'Lauke', yaxis:'y3axis'},
        ],
        cursor:{
          show: true,
          zoom:true,
          showTooltip:true
        },
        highlighter: {
        show: true,
        sizeAdjust: 7.5
        },
        legend:{
          location:'nw',
          xoffset: 270,
          yoffset: 100
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
      $.jqplot._noToImageButton = true;
            
    });
</script>";}

?>

</body>


</html>
