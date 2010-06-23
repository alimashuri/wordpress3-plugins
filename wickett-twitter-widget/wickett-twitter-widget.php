<?php
/*
Plugin Name: Wickett Twitter Widget
Plugin URI: http://wordpress.org/extend/plugins/wickett-twitter-widget
Description: Display Tweets in the sidebar of your blog
Version: 1.0.2
Author: Automattic Inc.
Author URI: http://automattic.com/
*/

// inits json decoder/encoder object if not already available
global $wp_version;
if ( version_compare( $wp_version, '2.9', '<' ) && !class_exists( 'Services_JSON' ) ) {
	include_once( dirname( __FILE__ ) . '/class.json.php' );
}

if ( !function_exists('wpcom_time_since') ) :
/*
 * Time since function taken from WordPress.com
 */

function wpcom_time_since( $original, $do_more = 0 ) {
        // array of time period chunks
        $chunks = array(
                array(60 * 60 * 24 * 365 , 'year'),
                array(60 * 60 * 24 * 30 , 'month'),
                array(60 * 60 * 24 * 7, 'week'),
                array(60 * 60 * 24 , 'day'),
                array(60 * 60 , 'hour'),
                array(60 , 'minute'),
        );

        $today = time();
        $since = $today - $original;

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];

                if (($count = floor($since / $seconds)) != 0)
                        break;
        }

        $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

        if ($i + 1 < $j) {
                $seconds2 = $chunks[$i + 1][0];
                $name2 = $chunks[$i + 1][1];

                // add second item if it's greater than 0
                if ( (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) && $do_more )
                        $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        }
        return $print;
}
endif;

class Wickett_Twitter_Widget extends WP_Widget {

	function Wickett_Twitter_Widget() {
		$widget_ops = array('classname' => 'widget_twitter', 'description' => __( "Display your tweets from Twitter") );
		$this->WP_Widget('twitter', __('Twitter'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$account = urlencode( $instance['account'] );
		if ( empty($account) ) return;
		$title = apply_filters('widget_title', $instance['title']);
		if ( empty($title) ) $title = __( 'Twitter Updates' );
		$show = absint( $instance['show'] );  // # of Updates to show
		$hidereplies = $instance['hidereplies'];
		$before_timesince = esc_html($instance['beforetimesince']);
		if ( empty($before_timesince) ) $before_timesince = ' ';
		$before_tweet = esc_html($instance['beforetweet']);

		echo "{$before_widget}{$before_title}<a href='" . clean_url( "http://twitter.com/{$account}" ) . "'>{$title}</a>{$after_title}";

		if ( !$tweets = wp_cache_get( 'widget-twitter-' . $this->number , 'widget' ) ) {
			$twitter_json_url = clean_url( "http://twitter.com/statuses/user_timeline/$account.json", null, 'raw' );
			$response = wp_remote_get( $twitter_json_url, array( 'User-Agent' => 'Wickett Twitter Widget' ) );
			$response_code = wp_remote_retrieve_response_code( $response );
			if ( 200 == $response_code ) {
				$tweets = wp_remote_retrieve_body( $response );
				$tweets = json_decode( $tweets);
				$expire = 900;
				if ( !is_array( $tweets ) || isset( $tweets['error'] ) ) {
					$tweets = 'error';
					$expire = 300;
				}
			} else {
				$tweets = 'error';
				$expire = 300;
				wp_cache_add( 'widget-twitter-response-code-' . $this->number, $response_code, 'widget', $expire);
			}

			wp_cache_add( 'widget-twitter-' . $this->number, $tweets, 'widget', $expire );
		}

		if ( 'error' != $tweets ) :
			echo "<ul class='tweets'>\n";
	
			$tweets_out = 0;

			foreach ( (array) $tweets as $tweet ) {
				if ( $tweets_out >= $show )
					break;

				if ( empty( $tweet->text ) || ($hidereplies && !empty($tweet->in_reply_to_user_id)) )
					continue;

				$text = make_clickable(wp_specialchars($tweet->text));
				$text = preg_replace_callback('/(^|\s)@(\w+)/', array($this, '_widget_twitter_username'), $text);
				$text = preg_replace_callback('/(^|\s)#(\w+)/', array($this, '_widget_twitter_hashtag'), $text);

				// Move the year for PHP4 compat
				$created_at = substr($tweet->created_at, 0, 10) . substr($tweet->created_at, 25, 5) . substr($tweet->created_at, 10, 15);

				echo "<li>{$before_tweet}{$text}{$before_timesince}<a href='" . clean_url( "http://twitter.com/{$account}/statuses/" . urlencode($tweet->id) ) . "' class='timesince'>" . str_replace(' ', '&nbsp;', wpcom_time_since(strtotime($created_at))) . "&nbsp;ago</a></li>\n";
				$tweets_out++;
			}
	
			echo "</ul>\n";
		else :
			if ( 401 == wp_cache_get( 'widget-twitter-response-code-' . $this->number , 'widget' ) )
				echo "<p>" . __("Error: Please make sure the Twitter account is <a href='http://help.twitter.com/forums/10711/entries/14016'>public</a>.") . "</p>";
			else
				echo "<p>" . __("Error: Twitter did not respond. Please wait a few minutes and refresh this page.") . "</p>";
		endif;
	
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['account'] = strip_tags(stripslashes($new_instance['account']));
		$instance['account'] = str_replace('http://twitter.com/', '', $instance['account']);
		$instance['account'] = str_replace('/', '', $instance['account']);
		$instance['account'] = str_replace('@', '', $instance['account']);
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['show'] = absint($new_instance['show']);
		$instance['hidereplies'] = isset($new_instance['hidereplies']);
		$instance['beforetimesince'] = $new_instance['beforetimesince'];

		wp_cache_delete( 'widget-twitter-' . $this->number , 'widget' );
		wp_cache_delete( 'widget-twitter-response-code-' . $this->number, 'widget' );

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('account' => '', 'title' => '', 'show' => '5', 'hidereplies' => false) );

		$account = esc_attr($instance['account']);
		$title = esc_attr($instance['title']);
		$show = absint($instance['show']);
		if ( $show < 1 || 20 < $show )
			$show = '5';
		$hidereplies = (bool) $instance['hidereplies'];
		$before_timesince = esc_attr($instance['beforetimesince']);

		echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title:') . '
		<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" />
		</label></p>
		<p><label for="' . $this->get_field_id('account') . '">' . __('Twitter username:') . '
		<input class="widefat" id="' . $this->get_field_id('account') . '" name="' . $this->get_field_name('account') . '" type="text" value="' . $account . '" />
		</label></p>
		<p><label for="' . $this->get_field_id('show') . '">' . __('Maximum number of tweets to show:') . '
			<select id="' . $this->get_field_id('show') . '" name="' . $this->get_field_name('show') . '">';

		for ( $i = 1; $i <= 20; ++$i )
			echo "<option value='$i' " . ( $show == $i ? "selected='selected'" : '' ) . ">$i</option>";

		echo '		</select>
		</label></p>
		<p><label for="' . $this->get_field_id('hidereplies') . '"><input id="' . $this->get_field_id('hidereplies') . '" class="checkbox" type="checkbox" name="' . $this->get_field_name('hidereplies') . '"' . checked( $hidereplies ) . ' />
		' . __('Hide replies') . '
		</label></p>';

		echo '<p><label for="' . $this->get_field_id('beforetimesince') . '">' . __('Text to display between tweet and timestamp:') . '
		<input class="widefat" id="' . $this->get_field_id('beforetimesince') . '" name="' . $this->get_field_name('beforetimesince') . '" type="text" value="' . $before_timesince . '" />
		</label></p>';
	}

	function _widget_twitter_username( $matches ) { // $matches has already been through wp_specialchars
		return "$matches[1]@<a href='" . clean_url( 'http://twitter.com/' . urlencode( $matches[2] ) ) . "'>$matches[2]</a>";
	}

	function _widget_twitter_hashtag( $matches ) { // $matches has already been through wp_specialchars
		return "$matches[1]<a href='" . clean_url( 'http://search.twitter.com/search?q=%23' . urlencode( $matches[2] ) ) . "'>#$matches[2]</a>";
	}

}

add_action( 'widgets_init', 'wickett_twitter_widget_init' );
function wickett_twitter_widget_init() {
	register_widget('Wickett_Twitter_Widget');
}
