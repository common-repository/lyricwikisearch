<?php
/*
Plugin Name: <a href="http://www.franzone.com/products/wordpress-plugins/lyricwiki-search-widget/">Lyric Wiki Search Widget</a>
Description: Adds a sidebar widget to search for song lyrics on the <a href="http://lyricwiki.org/">LyricWiki.org</a> site. For details on installation or setup options please visit <a href="http://www.franzone.com/products/wordpress-plugins/lyricwiki-search-widget/">Lyric Wiki Search Widget</a>.
Author: Jonathan Franzone
Version: 0.8
Author URI: http://www.franzone.com
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_LyricWikiSearch_init() {

  // Check for the required plugin functions. This will prevent fatal
  // errors occurring when you deactivate the dynamic-sidebar plugin.
  if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    return;

  // This is the function that outputs our Lyric Wiki Song of the Day
  function widget_LyricWikiSearch($args) {

    // $args is an array of strings that help widgets to conform to
    // the active theme: before_widget, before_title, after_widget,
    // and after_title are the array keys. Default tags: li and h2.
    extract($args);

    // Each widget can store its own options. We keep strings here.
    $options = get_option('widget_LyricWikiSearch');
    // Be sure you format your options to be valid HTML attributes.
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $buttontext = htmlspecialchars($options['buttontext'], ENT_QUOTES);
    $hideHelp = htmlspecialchars($options['hideHelp'], ENT_QUOTES);

    // Output the widget.
    echo $before_widget . $before_title . $title . $after_title;
    ?>
    <form action="http://lyricwiki.org/Special:Search" id="searchform" target="_blank">
      <input id="searchInput" name="search" type="text" accesskey="f" value="" /><input type="submit" name="fulltext" class="searchButton" value="<?php echo $buttontext; ?>" />
    </form>
    <?php
    if( $hideHelp != 'true' ) {
      echo '<div style="text-align: right">[<a href="http://www.franzone.com/products/wordpress-plugins/lyricwiki-search-widget/">?</a>]</div>';
    }
    echo $after_widget;
  }

  // This is the function that outputs the form to let the users edit
  // the widget's title. It's an optional feature that users cry for.
  function widget_LyricWikiSearch_control() {

    // Get our options and see if we're handling a form submission.
		$options = get_option('widget_LyricWikiSearch');
		if ( !is_array($options) )
			$options = array('title'=>__('LyricWiki Search', 'widgets'), 'buttontext'=>__('Find Lyrics', 'widgets'), 'hideHelp'=>'false');
		if ( $_POST['LyricWikiSearch-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['LyricWikiSearch-title']));
      $options['buttontext'] = strip_tags(stripslashes($_POST['LyricWikiSearch-buttontext']));
			$options['hideHelp'] = !isset($_POST['LyricWikiSearch-hideHelp']) ? 'false' : 'true';
			update_option('widget_LyricWikiSearch', $options);
		}

    // Be sure you format your options to be valid HTML attributes.
    $title = htmlspecialchars($options['title'], ENT_QUOTES);
    $buttontext = htmlspecialchars($options['buttontext'], ENT_QUOTES);
    $hideHelp = htmlspecialchars($options['hideHelp'], ENT_QUOTES);
    if( $hideHelp == '' ) $hideHelp = 'false';
    $selStat = "";
    if( $hideHelp == 'true' ) $selStat = " checked";

		// Here is our little form segment. Notice that we don't need a
		// complete form. This will be embedded into the existing form.
		echo '<p style="text-align:right;"><label for="LyricWikiSearch-title">' . __('Title:') . ' <input style="width: 200px;" id="LyricWikiSearch-title" name="LyricWikiSearch-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="LyricWikiSearch-buttontext">' . __('Button Text:', 'widgets') . ' <input style="width: 200px;" id="LyricWikiSearch-buttontext" name="LyricWikiSearch-buttontext" type="text" value="'.$buttontext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="LyricWikiSearch-hideHelp">' . __('Hide Help:', 'widgets') . ' <input type="checkbox" id="LyricWikiSearch-hideHelp" name="LyricWikiSearch-hideHelp" type="text" value="'.$hideHelp.'" '.$selStat.'/></label></p>';
		echo '<input type="hidden" id="LyricWikiSearch-submit" name="LyricWikiSearch-submit" value="1" />';
  }

	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('Lyric Wiki Search Widget', 'widgets'), 'widget_LyricWikiSearch');

	// This registers our optional widget control form. Because of this
	// our widget will have a button that reveals a 300x100 pixel form.
	register_widget_control(array('Lyric Wiki Search Widget', 'widgets'), 'widget_LyricWikiSearch_control', 300, 130);
}

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'widget_LyricWikiSearch_init');
?>