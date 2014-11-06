<?php
/*
Plugin Name: Twitter button plus
Plugin URI: http://wpdevart.com/wordpress-twitter-plugin/
Description: Twitter button plus is nice and useful tool to show Twitter tweet button on your website. 
Version: 1.0
Author: twitter widget ninjas 
Author URI: http://wpdevart.com/wordpress-twitter-plugin
*/

// Global params
$wtbp_vers	= "1.0";
$get_pro_version_js='To use this feature upgrade to Pro version for only 4.99$ !';
$get_pro_version_subtitle='Pro feature!';
$wtbp_params	= array("wtbp_tweet_count"				=>	"none",
						"wtbp_tweet_lang"				=>	"en",
						"wtbp_tweet_via"				=>	"",
						"wtbp_tweet_related"			=>	"",
						"wtbp_tweet_related_desc"		=>	"",
						"wtbp_tweet_text"				=>	"page",
						"wtbp_tweet_text_value"			=>	"",
						
						"wtbp_tweet_display_entry"		=>	"yes",
						"wtbp_tweet_display_page"		=>	"",
						"wtbp_tweet_display_home"		=>	"",
						"wtbp_tweet_position"			=>	"before"
						);

// Define General Options
add_option("wtbp_tweet_count",			$wtbp_params["wtbp_tweet_count"], 			'Tweet Count box position');
add_option("wtbp_tweet_lang", 			$wtbp_params["wtbp_tweet_lang"], 				'Tweet Button language');
add_option("wtbp_tweet_via", 				$wtbp_params["wtbp_tweet_via"], 				'Screen name of the user to attribute the Tweet to');
add_option("wtbp_tweet_related",			$wtbp_params["wtbp_tweet_related"],			'Twitter Related account');
add_option("wtbp_tweet_related_desc",		$wtbp_params["wtbp_tweet_related_desc"],		'Twitter Related account description');
add_option("wtbp_tweet_text", 			$wtbp_params["wtbp_tweet_text"], 				'Tweet text Default');
add_option("wtbp_tweet_text_value", 		$wtbp_params["wtbp_tweet_text_value"], 		'Your Tweet text value');

add_option("wtbp_tweet_display_entry", 	$wtbp_params["wtbp_tweet_display_entry"], 	'On all entries');
add_option("wtbp_tweet_display_page", 	$wtbp_params["wtbp_tweet_display_page"], 		'On all pages');
add_option("wtbp_tweet_display_home", 	$wtbp_params["wtbp_tweet_display_home"], 		'On Home');
add_option("wtbp_tweet_position", 		$wtbp_params["wtbp_tweet_position"], 			'Tweet button Position');

function wtbp_getConfigtbp() {
	// get config options into array var
	global $wtbp_params;
    static $config;
    if (empty($config)) {
		foreach( $wtbp_params as $option => $default) {
			$config[$option] = get_option($option);
		}
    }
    return $config;
}
function wtbp_getButtontbp() {
	global $post;
	
	$config = wtbp_getConfigtbp();
	$option = "?url=".urlencode(get_permalink());
	// count param func
	if ($config['wtbp_tweet_count'] != "horizontal") { 
		$option.= "&amp;count=".$config['wtbp_tweet_count'];
	}
	// language param func
	if ($config['wtbp_tweet_lang'] != "en") { 
		$option .= "&amp;lang=".$config['wtbp_tweet_lang'];
	}
	// via param func
	if ($config['wtbp_tweet_via'] != "") {
		$option .= '&amp;via='.urlencode($config['wtbp_tweet_via']);
	}
	// related param func
	if ($config['wtbp_tweet_related'] != "") {
		$option .= '&amp;related='.urlencode($config['wtbp_tweet_related']);
		if ($config['wtbp_tweet_related_desc'] != "") {
			$option .= ':'.urlencode($config['wtbp_tweet_related_desc']);
		}
	}
	// text param func
	if ($config['wtbp_tweet_text'] == "page") {
		$option .= '&amp;text='.$post->post_title.' - '.get_bloginfo('name');
	}
	if ($config['wtbp_tweet_text'] == "entry") {
		$option .= '&amp;text='.$post->post_title;
	}
	if ($config['wtbp_tweet_text'] == "blog") {
		$option .= '&amp;text='.get_bloginfo('name');
	}
	if ($config['wtbp_tweet_text'] == "custom") {
		$option .= '&amp;text='.$config['wtbp_tweet_text_value'];
	}
	
	return "<a href=\"http://twitter.com/share".$option."\" class=\"twitter-share-button\">Tweet</a>";	
}
function wtbp_addButtontbp($content) {
	$button = wtbp_getButtontbp();
	$config = wtbp_getConfigtbp();
	
	if (substr_count($content, '<!--wtbp_tweetbuttonplus-->') > 0) {
		$content = str_replace('<!--wtbp_tweetbuttonplus-->', $button, $content);
	}
	
	if ($config['wtbp_tweet_display_page'] == "" && is_page()) {
		return $content;
	}
	if ($config['wtbp_tweet_display_entry'] == "" && is_single()) {
		return $content;
	}
	if ($config['wtbp_tweet_display_home'] == "" && is_home()) {
		return $content;
	}
	
	if ($config['wtbp_tweet_position'] == "after") {
		$content = $content."<p>".$button."</p>";
	}
	if ($config['wtbp_tweet_position'] == "before") {
		$content = "<p>".$button."</p>".$content;
	}
	return $content;
}
function wtbp_tweetbuttonplus() {
	$button = wtbp_getButtontbp();
	echo $button;
}
function wtbp_showConfigPagetbp() {
	// update general options
	global $wtbp_vers, $wtbp_params,$get_pro_version_subtitle,$get_pro_version_js;
	
	if (isset($_POST['wtbp_update'])) {
		check_admin_referer();
		foreach( $wtbp_params as $option => $default ) {
			$wtbp_param = trim($_POST[$option]);
			if ($wtbp_param == "") {
				$wtbp_param = $default;
			}
			update_option($option, $wtbp_param);
		}
		echo "<div class='updated'><p><strong>Twitter button plus options updated</strong></p></div>";
	}
	$wtbp_config = wtbp_getConfigtbp();
?>
		<form method="post" action="options-general.php?page=twitter-tweets-button.php">
		<div class="wrap">
<style>.wrap h2{padding:0px;}
.pro_subtitle_span{
	color:#0074A1;
	font-weight:bold;
}</style>
<h2></h2>
<h2 style="float:left;
    color: #0074A1;
">Twitter button plus Options</h2>

<h2><a href="http://wpdevart.com/wordpress-twitter-plugin" style="float:right;color: #0074a2;font-weight: bold;text-decoration: none;">Upgrade to pro version!</a></h2>


		  <table class="form-table">
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_count">Twitter Button Style</label>
                    </th>
                    <td id="tdcount">
					    <div style="float:left; width: 100px; height: 100px; background:url(<?php echo WP_PLUGIN_URL; ?>/twitter-tweets-button/images/none-count.png) no-repeat 0px 30px;">
                            <input type="radio" name="wtbp_tweet_count" id="wtbp_tweet_count_n" value="none" checked="checked"/>
                            <label for="wtbp_tweet_count_n">No count</label>
                        </div>
						<div style="float:left; width: 225px; height: 100px; background:url(<?php echo WP_PLUGIN_URL; ?>/twitter-tweets-button/images/horizontal-button.png) no-repeat 0px 30px;">
                            <input type="radio" name="wtbp_tweet_count" id="wtbp_tweet_count_h" value="horizontal" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;"/>
                            <label for="wtbp_tweet_count_h" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;">Horizontal count <span class='pro_subtitle_span'><?php echo $get_pro_version_subtitle ?></span></label>
                        </div>
                        <div style="float:left; width: 200px; height: 100px; background:url(<?php echo WP_PLUGIN_URL; ?>/twitter-tweets-button/images/vertical-button.png) no-repeat 0px 30px;">
                            <input type="radio" name="wtbp_tweet_count" id="wtbp_tweet_count_v" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;" value="vertical"/>
                            <label for="wtbp_tweet_count_v" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;">Vertical count <span class='pro_subtitle_span'><?php echo $get_pro_version_subtitle ?></span></label>
                        </div>
                        </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_lang">Twitter Language</label>
                    </th>
                    <td>
                        <select name="wtbp_tweet_lang" id="wtbp_tweet_lang" style="width:283px;">
                            <option value="en"<?php if ($wtbp_config["wtbp_tweet_lang"] == "en") { echo " selected=\"selected\""; } ?>>English</option>
                            <option value="fr"<?php if ($wtbp_config["wtbp_tweet_lang"] == "fr") { echo " selected=\"selected\""; } ?>>French</option>
                            <option value="de"<?php if ($wtbp_config["wtbp_tweet_lang"] == "de") { echo " selected=\"selected\""; } ?>>German</option>
                            <option value="es"<?php if ($wtbp_config["wtbp_tweet_lang"] == "es") { echo " selected=\"selected\""; } ?>>Spanish</option>
                            <option value="ja"<?php if ($wtbp_config["wtbp_tweet_lang"] == "ja") { echo " selected=\"selected\""; } ?>>Japanese</option>
                        </select>
                        <br />
                        <span style="font-size: 13px;font-style: italic;color: #0074A1;">Choose language for your Twitter Button !</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_via">Twitter Account</label>
                    </th>
                    <td>
                        <input type="text" name="wtbp_tweet_via" id="wtbp_tweet_via" value="<?php echo $wtbp_config["wtbp_tweet_via"]; ?>" style="width:270px;" />
                        <br />
                        <span style="font-size: 13px;font-style: italic;color: #0074A1;">Name of the user to attribute the Tweet. Write without "@"!</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_related">Related Twitter Account</label>
                    </th>
                    <td>
                        @ <input type="text" name="wtbp_tweet_related" id="wtbp_tweet_related" value="<?php echo $wtbp_config["wtbp_tweet_related"]; ?>" style="width:100px;" /> 
                        Description <input type="text" name="wtbp_tweet_related_desc" value="<?php echo $wtbp_config["wtbp_tweet_related_desc"]; ?>" style="width:300px;" /><br />
                        <span style="font-size: 13px;font-style: italic;color: #0074A1;">Related Twitter accounts for users to follow after they share</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_text">Tweet text</label>
                    </th>
                    <td>
                    	<div>
                            <input type="radio" name="wtbp_tweet_text" id="wtbp_tweet_text_page" value="page"<?php if ($wtbp_config["wtbp_tweet_text"] == "page") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_text_page">Page Title</label> <em style="font-size: 13px;font-style: italic;color: #0074A1;">("Current Post Title - Your Blog Title")</em>
                        </div> 
                        <div>
                            <input type="radio" name="wtbp_tweet_text" id="wtbp_tweet_text_entry" value="entry"<?php if ($wtbp_config["wtbp_tweet_text"] == "entry") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_text_entry">Entry Title</label> <em style="font-size: 13px;font-style: italic;color: #0074A1;">(Example: "Current Post Title")</em>
                        </div>
                        <div>
                            <input type="radio" name="wtbp_tweet_text" id="wtbp_tweet_text_blog" value="blog"<?php if ($wtbp_config["wtbp_tweet_text"] == "blog") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_text_blog">Blog Title</label> <em style="font-size: 13px;font-style: italic;color: #0074A1;">(Example: "Your Blog Title")</em>
                        </div>    
                        <div>
                            <input type="radio" name="wtbp_tweet_text" id="wtbp_tweet_text_custom" value="none" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;" />
                            <label for="wtbp_tweet_text_custom" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;">Your own text</label>
                            <input type="text" name="wtbp_tweet_text_value" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;" style="width:195px;" />    <span class='pro_subtitle_span'><?php echo $get_pro_version_subtitle ?></span> 
                        </div>
						<br />
                        <span style="font-size: 13px;font-style: italic;color: #0074A1;">Choose one or write your own text !</span>
                    </td>
                </tr>
            </table>
            <table class="form-table">
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_display">Where to Display</label>
                    </th>
                    <td id="tdcount">
                    	<div>
                            <input type="checkbox" name="wtbp_tweet_display_entry" id="wtbp_tweet_display_entry" value="yes"<?php if ($wtbp_config["wtbp_tweet_display_entry"] == "yes") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_display_entry">In All Entries</label>
                        </div>
                        <div>
                            <input type="checkbox" name="wtbp_tweet_display_page" id="wtbp_tweet_display_page" value="yes"<?php if ($wtbp_config["wtbp_tweet_display_page"] == "yes") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_display_page">In All Pages</label>
                        </div>
                        <div>
                            <input type="checkbox" name="wtbp_tweet_display_home" id="wtbp_tweet_display_home" value="yes"<?php if ($wtbp_config["wtbp_tweet_display_home"] == "yes") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_display_home">In Home Page</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row" valign="top">
                        <label for="wtbp_tweet_position">Tweet Button Position</label>
                    </th>
                    <td>
                        <div>
                            <input type="radio" name="wtbp_tweet_position" id="wtbp_tweet_position_before" value="before"<?php if ($wtbp_config["wtbp_tweet_position"] == "before") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_position_before">Display Before Content</label>
                        </div>
                        <div>
                            <input type="radio" name="wtbp_tweet_position" id="wtbp_tweet_position_after" value="after" onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;" />
                            <label for="wtbp_tweet_position_after"onClick="alert('<?php echo $get_pro_version_js  ?>'); return false;">Display After Content <span class='pro_subtitle_span'><?php echo $get_pro_version_subtitle ?></span></label>
                        </div>
                        <div>
                            <input type="radio" name="wtbp_tweet_position" id="wtbp_tweet_position_none" value="none"<?php if ($wtbp_config["wtbp_tweet_position"] == "none") { echo " checked=\"checked\""; } ?> />
                            <label for="wtbp_tweet_position_none">None</label>
                        </div>
                    </td>
                </tr>
            </table>
            <p class="submit">
                  <input name="wtbp_update" value="Save Changes" type="submit" class="button-primary" />
            </p>
           		
		</div>
		</form>
<?php
}
function wtbp_addMenutbp() {
	// adding menu options
	add_options_page('Twitter button plus Options', 'Twitter button plus', 8, basename(__FILE__), 'wtbp_showConfigPagetbp');
}
function wtbp_addHeadertbp() {
	// adding header elements
	global $wtbp_vers;
	echo "\n<script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script>\n";
}

add_filter('the_content', 'wtbp_addButtontbp');
add_action('wp_head', 'wtbp_addHeadertbp');
add_action('admin_menu', 'wtbp_addMenutbp');
?>