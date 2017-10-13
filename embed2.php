<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
$driver = $_GET['id'];
include('simpdom.php');
$udkux = $_SERVER['HTTP_REFERER'];
if (strpos($udkux, 'mizi.ml') !== false || $udkux === null) {

} else {
  exit('no direct linking!');
}
$driver = preg_match('/([\w-_]{28})/',$driver,$driver)?$driver[1]:null;
$e = time() + 14000;
$md5 = md5($driver);
$md52 = md5($e);
	$cachefile = 'cache/'.$driver.'-embedv';
	// define how long we want to keep the file in seconds. I set mine to 4 hours.
	$cachetime = 13999;
	// Check if the cached file is still fresh. If it is, serve it up and exit.
	if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
    include($cachefile);
    	exit;
	}
	// if there is either no file OR the file to too old, render the page and capture the HTML.
	ob_start();
 
$ch = curl_init("https://drive.google.com/get_video_info?docid=$driver");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// get headers too with this line
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$result = curl_exec($ch);
// get cookie
// multi-cookie variant contributed by @Combuster in comments
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $matches);
$cookies = array();
foreach($matches[1] as $item) {
    parse_str($item, $cookie);
    $cookies = array_merge($cookies, $cookie);
}
$cookiz = str_replace("DRIVE_STREAM=" ,"" , $matches[1]); 
 
$data = $result;
parse_str($data,$data);
		$sources = explode(',',$data['fmt_stream_map']);
		if(!$sources)return false;
		foreach($sources as $source){
			$source = explode('|',$source);
			//$quality = str_replace([18,59,22,37],[360,480,720,1080],$source[0]);
			$source[1] = preg_replace('/[^\/]+\.google\.com/','redirector.googlevideo.com',$source[1]);
			$source[1] = preg_replace('/app=[^\/&]+/',"app=free",$source[1]);
 
			$expire = preg_match('/expire=([\d]+)/',$source[1],$expire)?$expire[1]:false;
		}
			 $sources = str_replace("|" ,"<file>" , $sources); 
 
			$sources = preg_replace('@<file>https://(.*)@si','<file>https://$1&apps=mizi.ml</file>',$sources);
			$sources = str_replace("c.drive.google.com" ,"googlevideo.com" , $sources); 
 	ob_start();
print_r($sources, false);
 
$output = ob_get_contents();
$output = str_replace("%2C" ,"," , $output ); 
//$output = str_replace("&" ,"%26" , $output ); 
$output = str_replace("18<file>" ,"<quality>360</quality><file>" , $output ); 
$output = str_replace("59<file>" ,"<quality>480</quality><file>" , $output ); 
$output = str_replace("22<file>" ,"<quality>720</quality><file>" , $output ); 
$output = str_replace("37<file>" ,"<quality>1080</quality><file>" , $output ); 
ob_end_clean();

$regex3='|</quality><file>(.+?)</file>|';
preg_match_all($regex3,$output,$parts3);
$sort3 = $parts3[1];
 
	$links3=$sort3;  
 
$regex2='|<quality>(.+?)</quality>|';
preg_match_all($regex2,$output,$parts2);
$sort2 = $parts2[1];

	$links2=$sort2;  

include("servers.php");
include("config.php");
$drv = "<div>".$driver."</div><t>".$e."</t>";
$drvid = $driver;
$enc = base64_encode(openssl_encrypt($drvid,$encrypt_method, $key, 0, $iv));
$i = 0;
  $sourcesx = "";
foreach($links2 as $kiz => $link2){
$i++;
$var = explode('&',$links3[$i -1]);
$domain = $var[0];
$domain = base64_encode(preg_replace('@(.*)videoplayback(.*)@si',"$1",$domain));
$links3[$i -1] = preg_replace('@https://(.*).com/videoplayback@si',"",$links3[$i -1]);
$sub = preg_replace('@https://(.*).com/videoplayback@si',"$1",$links3[$i -1]);
$links3[$i -1] = preg_replace('@&ip=(.+?)&@si',"&ip=$1&ck=$cookiz[0]&dom=$domain&",$links3[$i -1]);
$links3[$i -1] = preg_replace('@&driveid=(.+?)&@si',"&driveid=$enc&api=$cookiz[0]&",$links3[$i -1]);
$vf[] = array();
$lafbel = $links2[$i -1];
$fulllink = $proxy.$links3[$i -1];
$denc = base64_encode($links3[$i -1]); 
$denc = str_replace("-", "/", $denc); 
$denc = str_replace("+", "_", $denc);      

$vf[$kiz] = $fulllink;
$vki = $vf;
 $sourcesx .= '<source src="'.$fulllink.'" type="video/mp4" res="'.$lafbel.'" label="'.$lafbel.'" />';
}

$posterk = PosterImg('https://drive.google.com/file/d/'.$driver.'/view');
 $vid['result'][0] = array();
  $vid['result'][0]['link'] = $vki;
  $vid['result'][0]['img'] = $posterk;
    $vidarray = json_encode($vid, JSON_PRETTY_PRINT);
?> 


<!DOCTYPE html>
<html >
   <head>
    <link rel="stylesheet" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.11.9/video-js.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body, html {margin:0px !important; border-radius:0px !important; padding:0px !important;}
 
    #vjs-image-overlay-holder { -webkit-transition: all 0.2s linear; transition: all 0.2s linear; height:auto !important;
    width: 100%; opacity:0;
    max-width: 250px;
    margin-left: 5px;
    margin-top: 5px;
    }
   #vjs-image-overlay-holder img {opacity: 0.6;
    max-width: 309px; height: auto !important;
    width: 100%;
    float: left;}
    .vjs-control-bar:hover ~ #vjs-image-overlay-holder {
         opacity:1 !important; -webkit-transition: all 0.2s linear; transition: all 0.2s linear;
    }
    .vjs-menu-button-popup .vjs-menu {width:auto !important;}
    .video-js.vjs-has-started .vjs-poster {
  display: none !important;
}
    .video-js.vjs-has-ended .vjs-poster {
  display: none !important;
}
    .vjs-paused .vjs-poster {
    display:none !important;
}
    .video-js .vjs-control-bar {
        font-size:13px;
    }
    .custogxb {    position: relative;
        max-width: 174px;
    width: 100% !important;
    margin-left: 10px !important;
    margin-right: -10px !important;}
    .vjs-looping .vjs-loading-spinner {
  display: none;
}
    .video-js, .video-js .vjs-tech, .video-js video, .vjs-poster {border-radius: 0px !important;}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/videojs-resolution-switcher/0.4.2/videojs-resolution-switcher.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.11.9/ie8/videojs-ie8.min.js"></script>
</head>
<body>
     <video id="uniqueID" class="video-js vjs-fluid vjs-16-9" controls preload="auto" width="640" height="264" poster="<?php echo $posterk; ?>" data-setup='{}'>
<?php
         
       echo $sourcesx;
    
         ?>
                
      </video>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- VIDEOJS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/5.11.9/video.min.js"></script>
    <script>
        !function(){"use strict";var e=null;e="undefined"==typeof window.videojs&&"function"==typeof require?require("video.js"):window.videojs,function(e,t){function l(e,t,l,s){return i={label:l,sources:t},"function"==typeof s?s(e,t,l):e.src(t.map(function(e){return{src:e.src,type:e.type,res:e.res}}))}var s,r={},i={},n={},o=t.getComponent("MenuItem"),a=t.extend(o,{constructor:function(e,t,l,s){this.onClickListener=l,this.label=s,o.call(this,e,t),this.src=t.src,this.on("click",this.onClick),this.on("touchstart",this.onClick),t.initialySelected&&(this.showAsLabel(),this.selected(!0),this.addClass("vjs-selected"))},showAsLabel:function(){this.label&&(this.label.innerHTML=this.options_.label)},onClick:function(e){this.onClickListener(this);var t=this.player_.currentTime(),s=this.player_.paused();this.showAsLabel(),this.addClass("vjs-selected"),s||this.player_.bigPlayButton.hide(),"function"!=typeof e&&"function"==typeof this.options_.customSourcePicker&&(e=this.options_.customSourcePicker);var r="loadeddata";"Youtube"!==this.player_.techName_&&"none"===this.player_.preload()&&"Flash"!==this.player_.techName_&&(r="timeupdate"),l(this.player_,this.src,this.options_.label,e).one(r,function(){this.player_.currentTime(t),this.player_.handleTechSeeked_(),s||this.player_.play().handleTechSeeked_(),this.player_.trigger("resolutionchange")})}}),c=t.getComponent("MenuButton"),u=t.extend(c,{constructor:function(e,l,s,r){if(this.sources=l.sources,this.label=r,this.label.innerHTML=l.initialySelectedLabel,c.call(this,e,l,s),this.controlText("Quality"),s.dynamicLabel)this.el().appendChild(r);else{var i=document.createElement("span");t.addClass(i,"vjs-resolution-button-staticlabel"),this.el().appendChild(i)}},createItems:function(){var e=[],t=this.sources&&this.sources.label||{},l=function(t){e.map(function(e){e.selected(e===t),e.removeClass("vjs-selected")})};for(var s in t)t.hasOwnProperty(s)&&(e.push(new a(this.player_,{label:s,src:t[s],initialySelected:s===this.options_.initialySelectedLabel,customSourcePicker:this.options_.customSourcePicker},l,this.label)),n[s]=e[e.length-1]);return e}});s=function(e){function s(e,t){return e.res&&t.res?+t.res-+e.res:0}function o(e){var t={label:{},res:{},type:{}};return e.map(function(e){a(t,"label",e),a(t,"res",e),a(t,"type",e),c(t,"label",e),c(t,"res",e),c(t,"type",e)}),t}function a(e,t,l){null==e[t][l[t]]&&(e[t][l[t]]=[])}function c(e,t,l){e[t][l[t]].push(l)}function h(e,t){var l=y["default"],s="";return"high"===l?(l=t[0].res,s=t[0].label):"low"!==l&&null!=l&&e.res[l]?e.res[l]&&(s=e.res[l][0].label):(l=t[t.length-1].res,s=t[t.length-1].label),{res:l,label:s,sources:e.res[l]}}function d(e){e.tech_.ytPlayer.setPlaybackQuality("default"),e.tech_.ytPlayer.addEventListener("onPlaybackQualityChange",function(){e.trigger("resolutionchange")}),e.one("play",function(){var t=e.tech_.ytPlayer.getAvailableQualityLevels(),l={highres:{res:1080,label:"1080",yt:"highres"},hd1080:{res:1080,label:"1080",yt:"hd1080"},hd720:{res:720,label:"720",yt:"hd720"},large:{res:480,label:"480",yt:"large"},medium:{res:360,label:"360",yt:"medium"},small:{res:240,label:"240",yt:"small"},tiny:{res:144,label:"144",yt:"tiny"},auto:{res:0,label:"auto",yt:"default"}},s=[];t.map(function(t){s.push({src:e.src().src,type:e.src().type,label:l[t].label,res:l[t].res,_yt:l[t].yt})}),f=o(s);var r=function(t,l,s){return e.tech_.ytPlayer.setPlaybackQuality(l[0]._yt),e},i={label:"auto",res:0,sources:f.label.auto},n=new u(e,{sources:f,initialySelectedLabel:i.label,initialySelectedRes:i.res,customSourcePicker:r},y,b);n.el().classList.add("vjs-resolution-button"),e.controlBar.resolutionSwitcher=e.controlBar.addChild(n)})}var y=t.mergeOptions(r,e),p=this,b=document.createElement("span"),f={};t.addClass(b,"vjs-resolution-button-label"),p.updateSrc=function(e){if(!e)return p.src();p.controlBar.resolutionSwitcher&&(p.controlBar.resolutionSwitcher.dispose(),delete p.controlBar.resolutionSwitcher),e=e.sort(s),f=o(e);var r=h(f,e),i=new u(p,{sources:f,initialySelectedLabel:r.label,initialySelectedRes:r.res,customSourcePicker:y.customSourcePicker},y,b);return t.addClass(i.el(),"vjs-resolution-button"),p.controlBar.resolutionSwitcher=p.controlBar.el_.insertBefore(i.el_,p.controlBar.getChild("fullscreenToggle").el_),p.controlBar.resolutionSwitcher.dispose=function(){this.parentNode.removeChild(this)},l(p,r.sources,r.label)},p.currentResolution=function(e,t){return null==e?i:(null!=n[e]&&n[e].onClick(t),p)},p.getGroupedSrc=function(){return f},p.ready(function(){p.options_.sources.length>1&&p.updateSrc(p.options_.sources),"Youtube"===p.techName_&&d(p)})},t.plugin("videoJsResolutionSwitcher",s)}(window,e)}();
      $( document ).ready(function() {
           videojs('uniqueID').videoJsResolutionSwitcher({default: 'high', dynamicLabel: true});
        function InitializeIFrame() {
            document.body.getcss = true;
        }
videojs('uniqueID', {
      
    controls: true,
    plugins: {
      videoJsResolutionSwitcher: {
      
      }
    }
  }, function(){
    var player = this;
    window.player = player
    player.on('resolutionchange', function(){
     player.posterImage.hide();
     player.play();
    })
      player.on('seeking', function(e) {
  if(player.currentTime() === 0) {
    player.addClass('vjs-looping');
  }
})
player.on('playing', function(e) {
  if(player.currentTime() === 0) {
    player.removeClass('vjs-looping');
  }
})

  
  })    

          $(document).ready(function(){
    $('video').bind('contextmenu',function() { return false; });
              
});
        });
    </script>

</body>
</html>
<?php
	// We're done! Save the cached content to a file
	$fp = fopen($cachefile, 'w');
	fwrite($fp, ob_get_contents());
	fclose($fp);
	// finally send browser output
	ob_end_flush();
?>
