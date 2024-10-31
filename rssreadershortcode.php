<?php
// Notes : -1 and 0 are invalid integer, first category and podcast start with 1
global $opencategorynumber;
$opencategorynumber = (isset($_GET["category"])) ? $_GET["category"] : -1 ;
$opencategorynumber = (int) $opencategorynumber;
global $highlightedpodcastnumber;
$highlightedpodcastnumber =  (isset($_GET["podcast"])) ? $_GET["podcast"] : -1 ;
$highlightedpodcastnumber = (int) $highlightedpodcastnumber;
//echo $opencategorynumber."<br>";
//echo $highlightedpodcastnumber ;
$playlist = array();
$index = 0;
$channeltitle = "";
function csrssreaderdoes_url_exists($url) {
  $response = wp_remote_get( $url );
  $code = wp_remote_retrieve_response_code( $response );
  if ($code == 200) {
      $status = true;
  } else {
      $status = false;
  }
return $status;
}


function csrssreaderprocesslistcategory($mysimplexmlelement){
global $channeltitle;
$array = [];
$channeltitle = strval($mysimplexmlelement->channel->title);
echo "<h2>".$channeltitle."</h2>";
  foreach ($mysimplexmlelement->channel->item as $item) {

  $mycategory = strval($item->category);
    if(!in_array($mycategory,$array)){
    array_push($array,$mycategory);
    }
  }
return $array;
}


function csrssreadershowaccordions($categorylist, $xmlparsed){
// In case no category is set
if(count($categorylist) == 1){
    foreach($xmlparsed->channel->item  as $item){
      //$cover = strval($anitem->children(rawvoice:poster));

      csrssreadershowsingleaccordion($item);
    }
    echo csrssreaderinitmediaplayer();
    return;
  }

// Many category set
$categoryindex = 1;
  foreach ($categorylist as $category ) {
    csrssreadershowsingleaccordionbutton($category,$categoryindex);
    $categoryindex = $categoryindex + 1;
    foreach($xmlparsed->channel->item  as $item){
      if($item->category == $category){
      csrssreadershowsingleaccordion($item);
      //var_dump($item);
      }
    }
    csrssreadershowsingleaccordionclosebutton();
  }
  echo csrssreaderinitmediaplayer();
}


function csrssreaderinitmediaplayer(){
  global $playlist;
  global $highlightedpodcastnumber;
  $highlightedpodcastnumber = $highlightedpodcastnumber - 1 ;
  //echo count($playlist);
  if($highlightedpodcastnumber < 0 || $highlightedpodcastnumber > (count($playlist)-1)){
    $highlightedpodcastnumber = 0;
    $autoplay = "";
    $startpodcastscript = "";
  }else{
    $startpodcastscript = "Amplitude.playSongAtIndex(".$highlightedpodcastnumber.");";
    $autoplay = "'autoplay': true,";
  }
  $songplaylistinitcode = "<Script>Amplitude.init({
      'bindings': {
        37: 'prev',
        39: 'next',
        32: 'play_pause'
      }, 'songs': ";
  $songplaylistinitcode = $songplaylistinitcode.json_encode($playlist)."\n, $autoplay start_song : $highlightedpodcastnumber });\n".$startpodcastscript."</Script>"."\n";
  return $songplaylistinitcode;
}


function csrssreadershowsingleaccordionbutton($name,$categoryindex){
  global $opencategorynumber;
  //echo $opencategorynumber ;
  $categoryselectedstyle = "";
  if($opencategorynumber==$categoryindex){
  $categoryselectedstyle = " accordionselected";
  }
  echo('<button class="accordion'.$categoryselectedstyle.'">'.$name.'</button> <div class="panel">');
}


function csrssreadershowsingleaccordion($anitem,$mediaurl=""){
$url = strval($anitem->enclosure['url']);
$title = strval($anitem->title);
$artist = "";
$description = strval($anitem->description);
$link = strval($anitem->link);
//$cover = strval($anitem->rawvoice);
//$cover = strval($anitem->children(rawvoice:poster));
//var_dump($anitem);
//echo "Blou".$cover;
//echo count($anitem->children('rawvoice'));

echo("<button class='accordionelement' ".csrssreadersinglesongjsongenerator($url,$title,"","","",$link).">"."<span class='tooltiptext'>".$description."</span>".$title.'</button>'."\n");
}


function csrssreadershowsingleaccordionclosebutton(){
  echo "</div>";
}


function csrssreadersinglesongjsongenerator($url, $name = "",$artist = "",$album = "",$cover_art_url = "", $link = ""){
global $playlist;
global $index;
global $channeltitle;
  $myObj = new stdClass();
  $myObj->url = $url;
  $myObj->name = $name ;
  $myObj->artist = $channeltitle ;

$playlist[] = $myObj ;
$index = $index + 1 ;
// In case there is no audio file url but there is a link, open the link in a new tab
  if($url == "" && $link != ""){
    return " onclick=\"openInNewTab('$link')\"";
  }
// Open Amplitude playlist item corresponding to $index
return " onclick=' Amplitude.playSongAtIndex(".($index - 1).")'";
}

if($wporg_atts[ 'rssurl' ] == "default"){
  echo "<H2>You must specify a valid URL with the rssurl attribute in your short code. Look at the plugin installation page.</H2>";
  return;
}

// include Amplitude player
include "player.html";
// if the rss file url exist, load it an parse it
if(csrssreaderdoes_url_exists($wporg_atts[ 'rssurl' ])){
  $response = wp_remote_get( $wporg_atts[ 'rssurl' ] );
  $body = wp_remote_retrieve_body( $response );
  $xmlparsed = new SimpleXMLElement($body);
  // fetch category list
  $categorylist = csrssreaderprocesslistcategory($xmlparsed);
  // Show all category enclosed in there own accordions
  csrssreadershowaccordions($categorylist,$xmlparsed);
}else{
  echo "URL Not valid";
}

?>
