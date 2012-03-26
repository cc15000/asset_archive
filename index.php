<!DOCTYPE HTML>
<html>
<head>
<meta charset="ISO-8859-1">
<title>:: assets archive ::</title>

<!-- Inline CSS for Flash Player -->
<style type="text/css" media="screen"> 
html, body  { 
	height:100%; 
}
body { 
margin:0; padding:0; overflow:auto; 
}   
object:focus { 
outline:none; 
}
#flashContent { 
display:none; 
}
</style>
        

<!-- External CSS -->
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="history/history.css" />
<link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />

<!-- External JavaScript -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<script type="text/javascript" src="history/history.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">
            // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection. 
            var swfVersionStr = "10.2.0";
            // To use express install, set to playerProductInstall.swf, otherwise the empty string. 
            var xiSwfUrlStr = "playerProductInstall.swf";
            var flashvars = {};
            var params = {};
            params.quality = "high";
            params.bgcolor = "#ffffff";
            params.allowscriptaccess = "sameDomain";
            params.allowfullscreen = "true";
            var attributes = {};
            attributes.id = "webTest4";
            attributes.name = "webTest4";
            attributes.align = "middle";
            swfobject.embedSWF(
                "webTest4.swf", "flashContent", 
                "770", "644", 
                swfVersionStr, xiSwfUrlStr, 
                flashvars, params, attributes);
            // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
            swfobject.createCSS("#flashContent", "display:block;text-align:left;");
</script>

</head>

<header id="header" class="grid">
<div id="header" class="wrapper">
		<div class="row">
        </div>
</div>
</header>
<!-- End Header -->
<body>
<div id="container" class="container_12">
<div id="wrapper">
<div id="body">
<div class="grid_12"></div>
<div class="clear"></div>
<div id="main" role="main">
<div class="grid excerpts">
<!-- Begin Left Panel -->
<div id="container">
<div id="content">
<div>&nbsp;&nbsp;</div>
<div class="column two" style="margin:-10px; padding-top:30px;">
<div class="box sidebox videos" id="sb-videos">
<h2><strong>Featured Media</strong></h2><br>
                <div id="sb-v-featured"></div>
				<?php 
				    
					include('Iteration02/getRecentVideos.php');
					include('Iteration02/getRecentImages.php');

					$assets = array();

					// Read the filenames in the directories
					$videos = glob('assets/videos/*.{mp4,mov,wmv}', GLOB_BRACE);
					$images = glob('assets/images/*.{jpg}', GLOB_BRACE);
					
					// Determine which files are the most recent
					usort($videos, 'filemtime_compare');
					usort($images, 'filemtime_compare');

					function filemtime_compare($a, $b)
					{
						return filemtime($b) - filemtime($a);
					}

					// Randomly sort the most recent video and image files
					$videos = getRecentVideos($videos);
					$images = getRecentImages($images);
					
					// Merge the randomly sorted video and image files
					$assets = array_merge($videos, $images);
					
					
					$i = 0;
					$show = 3;
					echo '<ul>';
					foreach ($assets as $asset)
					{
					
					// Shuffle the merged assets once again for more randomness
					$assets = shuffleMergedAssets($assets);
					
					// Extract the file paths from the assets
					$path = extractPath($assets);
					
					// Generate a filename from the path
					$filename = preg_replace("/\\.[^.\\s]{3,4}$/", "", extractFilename($assets));
					
					// Generate top, middle and bottom captions for each asset
					$caption_top = matchCaptionTop(extractFilename($assets));
					$caption_middle = matchCaptionMiddle(extractFilename($assets));
					$caption_bottom = matchCaptionBottom(extractFilename($assets));
					
					// Build URL for asset links and thumbnails
					if($i == $show) break; else ++$i;
					if (preg_match("/^.*\.(jpg|jpeg)$/i", extractFilename($assets))) {
						$image = "assets/images/" . extractFilename($assets);
						$asset_type = "image";
						$asset_path = "images";
						$thumb_path = "/thumbnails/Tb_";
						$rel_input = "lightbox";
						list($img_width, $img_height, $img_type, $img_attr) = getimagesize($image);
						if ($img_width < $img_height) {
							$width = "145";
							$height = "212";
						} else {
							$width = "144";
							$height = "101";
						}
						$play = "#";
						$extension = ".jpg";
					} else {
						$asset_type = "video";
						$asset_path = "videos";
						$thumb_path = "/";
						$rel_input = "#";
						$video_style = "display:block;width:425px;height:300px;";
						$video_id = "player";
						$width = "145";
						$height = "84";
						$play = "play";
						if (preg_match("/^(_resized)\.(mp4)$/i", extractFilename($assets))) {
							$extension = "_resized.mp4";
						} elseif (preg_match("/^(_resized)\.(mov)$/i", extractFilename($assets))) {
							$extension = "_resized.mov";
						} elseif (preg_match("/mp4$/i", extractFilename($assets))) {
							$extension = ".mp4";
						} else {
							$extension = ".mov";
						}
						if (preg_match("/TWD/", extractFilename($assets))) {
							$asset_path = "videos/resized";
						} else {
							$asset_path = "videos";
						}
					}
					
					// Output assets in an unordered list using URL
					echo '<li><a href="../assets/' . $asset_path . $thumb_path . $filename . $extension . '" class="' . $asset_type . '-thumb" rel="' . $rel_input . '">
                    <span class="image">
                    	<span class="' . $play . '"></span>
                        <img src="../assets/' . $asset_path . '/thumbnails/Tb_' . $filename . '.jpg" alt="" width="' . $width . '" height="' . $height . '" />
                    </span>' . 
                    $caption_top . '<br>' .
					$caption_middle . '<br>' .
					$caption_bottom . 
                    '</a></li>';
					}
					echo '</ul>';
					echo '<br><br>';
					
function shuffleMergedAssets($assets) {
  $copy = array();
  while (count($assets)) {
    $element = array_rand($assets);
    $copy[$element] = $assets[$element];
    unset($assets[$element]);
  }
  return $copy;
}
					
function extractPath($assets) {
$i = 0;
$limit = 3;
   foreach ($assets as &$asset) {
        if($i == $limit) break; else ++$i;
		$asset = explode(" - ", $asset, 2);
		$path = $asset[0];
		return $path;
   }
}

function extractFilename($assets) {
$i = 0;
$limit = 3;
   foreach ($assets as &$asset) {
        if($i == $limit) break; else ++$i;
		$path_pieces = extractPath($assets);
		$path_pieces = explode("/", $path_pieces, 3);
		$third_path_piece = $path_pieces[2];
		return $third_path_piece;
   }
}

function matchCaptionTop($third_path_piece) {
		if (preg_match("/ES1_S05/", $third_path_piece)) {
			return "example show 1 Season 5";
		}
		if (preg_match("/ES1_S04/", $third_path_piece)) {
			return "example show 1 Season 4";
		}
		if (preg_match("/ES1_S03/", $third_path_piece)) {
			return "example show 1 Season 3";
		}
		if (preg_match("/ES1_S02/", $third_path_piece)) {
			return "example show 1 Season 2";
		}
		if (preg_match("/ES1_S01/", $third_path_piece)) {
			return "example show 1 Season 1";
		}
		if (preg_match("/ES2/", $third_path_piece)) {
			return "example show 2";
		}
		if (preg_match("/ES3/", $third_path_piece)) {
			return "example show 3";
		}
		if (preg_match("/iTunes/", $third_path_piece)) {
			return "iTunes Promotion";
		}
		if (preg_match("/ES4/", $third_path_piece)) {
			return "Example Show 4" . "<br>" . "Gallery Photography";
		}
		if (preg_match("/ES5/", $third_path_piece)) {
			return "example show 5";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Logo";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Season 5";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Season 4";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Season 3";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Season 2";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Season 1";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Seasons 1 and 2";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Seasons 1 - 3";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Seasons 1 - 4";
		}
		if (preg_match("/ES6/", $third_path_piece)) {
			return "example show 6 Seasons 1 - 5";
		}
		if (preg_match("/ES7/", $third_path_piece)) {
			return "example show 7";
		}
		if (preg_match("/ASMA/", $third_path_piece)) {
			return "Ad Sales Marketing";
		} else {
		    return " ";
	    }
}

function matchCaptionMiddle($third_path_piece) {
		if (preg_match("/FullCast/", $third_path_piece)) {
			preg_match("/[0-9][0-9](([a-z]|[a-z][a-z])?)/", $third_path_piece, $matches);
			return "Full Cast " . $matches[0];
		}
		if (preg_match("/Pair/", $third_path_piece)) {
			preg_match("/[0-9][0-9]([a-z]?)/", $third_path_piece, $matches);
			return "Pair Portrait " . $matches[0];
		}
		if (preg_match("/James/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "James Portrait " . $matches[0];
		}
		if (preg_match("/Hanson/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Hanson Portrait " . $matches[0];
		}
		if (preg_match("/Weller/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Weller Portrait " . $matches[0];
		}
		if (preg_match("/Mike/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Mike Portrait " . $matches[0];
		}
		if (preg_match("/Jackson/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Jackson Portrait " . $matches[0];
		}
		if (preg_match("/Hernandez/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Hernandez Portrait " . $matches[0];
		}
		if (preg_match("/Fox/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Fox Portrait " . $matches[0];
		}
		if (preg_match("/Larson/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Larson Portrait " . $matches[0];
		}
		if (preg_match("/Fountain/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Fountain Portrait " . $matches[0];
		}
		if (preg_match("/Thomas/", $third_path_piece)) {
			preg_match("/[0-9][0-9]i/", $third_path_piece, $matches);
			return "Thomas Portrait " . $matches[0];
		}
		if (preg_match("/Logo_old/", $third_path_piece)) {
			return "Old Logo";
		}
		if (preg_match("/3Q 2011/", $third_path_piece)) {
			return "3Q 2011";
		}
		if (preg_match("/TCAPromo/", $third_path_piece)) {
			return "TCA Promo";
		}
		if (preg_match("/RainbowEblast/", $third_path_piece)) {
			return "Rainbow E-Blast";
		}
		if (preg_match("/WhiteBkd/", $third_path_piece)) {
			return "White Background";
		}
		if (preg_match("/300x250/", $third_path_piece)) {
			return "300 x 250";
		}
		if (preg_match("/Tsr_OneSheet/", $third_path_piece)) {
			return "One Sheet Teaser";
		}
		if (preg_match("/1Sheet/", $third_path_piece)) {
			return "One Sheet";
		}
		if (preg_match("/2Sheet/", $third_path_piece)) {
			return "Two Sheet";
		}
		if (preg_match("/Logo/", $third_path_piece)) {
			return "Logo";
		}
		if (preg_match("/OffAir30/", $third_path_piece)) {
			if (preg_match("/[0-9][0-9][0-9]H/i", $third_path_piece, $matches)) {
				return "Off-Air 30 Second Spot" . "<br>" . "example-TWD-" . $matches[0];
			} elseif (preg_match("/[0-9][0-9][0-9]/", $third_path_piece, $matches)) {
				return "Off-Air 30 Second Spot" . "<br>" . "Ref" . $matches[0];
			} else {
				return "Off-Air 30 Second Spot" . "<br>" . "No Text";
			}
		}
		if (preg_match("/AdTieIn/", $third_path_piece)) {
			return "Advertiser Tie In";
		}
		if (preg_match("/Recap/", $third_path_piece)) {
			return "Recap";
		}
		if (preg_match("/Activation/", $third_path_piece)) {
			return "Activation";
		}
		if (preg_match("/Trailer/", $third_path_piece)) {
			return "Trailer";
		}
		if (preg_match("/DVDPromo3/", $third_path_piece)) {
			return "Season 3 DVD Promotion";
		}
		if (preg_match("/DVDSndtrk3/", $third_path_piece)) {
			return "Season 3 DVD Soundtrack";
		}
		if (preg_match("/ConPromo/", $third_path_piece)) {
			return "Consumer Promotion";
		}
		if (preg_match("/GP/", $third_path_piece)) {
			return "Gallery Photography";
		}
		if (preg_match("/3Q_2011/", $third_path_piece)) {
			return "3Q 2011";
		}
		if (preg_match("/DSC_6890/", $third_path_piece)) {
			return "DSC_6890";
		} else {
		    return " ";
	    }
}

function matchCaptionBottom($third_path_piece) {
		if (preg_match("/Mike_James/", $third_path_piece)) {
			return "Collage Mike and James";
		}
		if (preg_match("/BookSpread/", $third_path_piece)) {
			return "Collage Book Spread";
		}
		if (preg_match("/Jeremy_Jack/", $third_path_piece)) {
			return "Collage Joan and Lane";
		}
		if (preg_match("/Joan_Peggy/", $third_path_piece)) {
			return "Collage Jeremy and Jack";
		}
		if (preg_match("/Trevor_Milton/", $third_path_piece)) {
			return "Collage Trevor and Milton";
		}
		if (preg_match("/Liston_Gianna_Bill/", $third_path_piece)) {
			return "Collage Liston, Gianna and Bill";
		}
		if (preg_match("/Mike_James_Pete/", $third_path_piece)) {
			return "Collage Roger, Don and Pete";
		}
		if (preg_match("/Clorox/", $third_path_piece)) {
			return "Clorox";
		}
		if (preg_match("/Palm/", $third_path_piece)) {
			return "The Palm";
		}
		if (preg_match("/Bug/", $third_path_piece)) {
			return "Includes example Bug";
		}
		if (preg_match("/Delta/", $third_path_piece)) {
			return "Delta";
		}
		if (preg_match("/Jeopardy/", $third_path_piece)) {
			return "Jeopardy";
		}
		if (preg_match("/Lionsgate/", $third_path_piece)) {
			return "Lionsgate";
		}
		if (preg_match("/DesignWithinReach/", $third_path_piece)) {
			return "Design Within Reach";
		}
		if (preg_match("/BestBuy/", $third_path_piece)) {
			return "Best Buy";
		}
		if (preg_match("/BananaRep/", $third_path_piece)) {
			return "Banana Republic";
		}
		if (preg_match("/BrooksBros/", $third_path_piece)) {
			return "Brooks Brothers";
		}
		if (preg_match("/NY\'sGoneMad/", $third_path_piece)) {
			return "New York's Gone Mad";
		}
		if (preg_match("/DVDBlu/", $third_path_piece)) {
			return "DVD and Blu-Ray";
		}
		if (preg_match("/FinalReel/", $third_path_piece)) {
			return "Final Reel";
		}
		if (preg_match("/WalkonRoleContest/", $third_path_piece)) {
			return "Walk-on Role Contest";
		}	else {
		    return " ";
	    }
}
					
				?> 
       		    </div>
            </div>
            </div>
       </div>
<!-- End Left Panel -->
<!-- Begin Center Panel -->
<div id="container">
<div id="content">
<div>&nbsp;&nbsp;</div>
	<div class="column six" style="padding-top:1px;padding-left:0px;padding-right:0px;">
    <div class="box sidebox store" id="sb-store">
    	<h2><strong>Search the Archive</strong></h2></br>

<!------ BEGIN DATAGRID -------->
 <div id="flashContent">
            <p>
                To view this page ensure that Adobe Flash Player version 
                10.2.0 or greater is installed. 
            </p>
            <script type="text/javascript"> 
                var pageHost = ((document.location.protocol == "https:") ? "https://" : "http://"); 
                document.write("<a href='http://www.adobe.com/go/getflashplayer'><img src='" 
                                + pageHost + "www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a>" ); 
            </script> 
        </div>
<!------ END DATAGRID ---------->
       </div>
       </div>
       </div>
       </div>
<!-- End Center Panel -->
<!-- Begin Right Panel -->
       <div id="container">
 		<div id="content">
        <div>&nbsp;&nbsp;</div>
        <div class="column_b toprightbox" style="margin-top:-18px; margin-left:5px;">
           		<div class="box sidebox store" id="sb-store">
       			<h2><strong>Did You Know?</strong></h2><br>
                <?php 
				    
					include('simple_html_dom.php');
					include('linkcheck.php');
	
					$url = 'http://example.example.com/intranet/Content/CDA/PressReleases.jsp?formstate=bu&NavId=600&OwnerId=0&ArchiveId=null&secondaryCategory=&bu=960&primaryCategory=undefined&cat=11';
					
					// Get List of example Press Releases
					$pageContent = openInitialPage($url);
					
					// Scan List and Generate Links to Three Most Recent Releases
					$links = checkInitialPage($pageContent);
					
					// Iterate Through Releases and Extract Title, Date and Summary Content
					$articles = getArticles($links);  
					
				?> 
                
            <br>
       		</div>
            </div>
            </div>
       </div>
       		</div>
       </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- End Center Panel -->
<!-- Begin Footer -->
<footer id="footer" class="grid">
	<div id="footer" class="wrapper">
		<div class="row">
    		<div class="column three colophon">
        	<p>The assets archive is an internal online respository for the storage of all digital assets.</p>
            <img src="images/daa_example.png" alt="assets archive logo"/>
        </div>
        <dl class="column one nav" role="navigation">
        	<dt>Intranet</dt>
            	<dd><a href="http://example.example.com/intranet/CDA/HomePage.jsp">Home</a></dd>
                <dd><a href="http://example.example.com/intranet/CDA/ContactUs.jsp">Contact</a></dd>
                <dd><a href="http://example.example.com/directory/intranet/employee/home.jsp">Directory</a></dd>
                <dd><a href="http://example.example.com/intranet/CDA/SiteMap.jsp">Site Map</a></dd>
        </dl>
        <dl class="column one staff">
        	<dt>Sections</dt>
            	<dd><a href="http://example.example.com/intranet/Content/CDA/ArticlesDetail.jsp?NavId=561&OwnerId=0&ContentId=5936">About</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=553&OwnerId=0">Awards</a></dd>
                <dd><a href="http://example.example.com/intranet/Content/CDA/ArticlesDetail.jsp?NavId=423&OwnerId=0&ContentId=5789">Resources</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=552&OwnerId=0">Public Relations</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=448&OwnerId=0">HR</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=554&OwnerId=0">Services</a></dd>
                <dd><a href="http://example.example.com/intranet/Content/CDA/ArticlesDetail.jsp?NavId=5400&OwnerId=0&ContentId=69960">Compliance</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=555&OwnerId=0">Development</a></dd>
                <dd><a href="http://example.example.com/intranet/Navigation/CDA/ShowNavigation.jsp?NavId=557&OwnerId=0">Departments</a></dd>
        </dl>
    </div>
    <dl class="row network">
        <dt>Discover Us</dt>
        	<dd class="column one"><a href="http://www.example.com/brand_example" target="_blank"><img src="images/examplelogo.png" /></a></dd>
            <dd class="column one"><a href="http://www.example.com/brand_example" target="_blank"><img src="images/examplelogo.png" /></a></dd>
            <dd class="column one"><a href="http://www.example.com/brand_example" target="_blank"><img src="images/examplelogo.png" /></a></dd>
            <dd class="column one"><a href="http://www.example.com/brand_example" target="_blank"><img src="images/examplelogo.png" /></a></dd>
            <dd class="column one"><a href="http://www.example.com/brand_example" target="_blank"><img src="images/examplelogo.png" /></a></dd>
    </dl>
    <div class="row legal">
    <ul class="column three">
            	<li><a href="http://www.example.com" target="_blank">example.com</a></li>
                <li><a href="http://www.example.com/contact.jsp" target="_blank">Contact Us</a></li>
                <li><a href="http://www.example.com/terms" target="_blank">Terms of Usage</a></li>
                <li><a href="http://www.example.com/privacy" target="_blank">Privacy</a></li>
    </ul>
    <div class="column copyright">
    <p>&copy; 2012</p>
    </div>
    </div>
</div>
</footer>
<!-- End Footer -->
</div>
</body>
</html>
