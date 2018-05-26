<?php
/**
 * Plugin Name: Site plugins: Translate the user's display name from its description - Polylang plugin add on
 * Description: Currently Polylang can't translate the user's meta data. But it can translate the user's description. So with this plugin we can use a HTML comment (with special format) within the user's description field to translate the user's display name. The format of the comment should be:    &#60;!-- dnt: The Display Name Translation -->
 * Author: Spas Spasov (c) 2018
 * Text Domain: polylang-user-display-name-translation
**/


/*
	References: 
	        https://www.makeuseof.com/tag/how-to-create-wordpress-widgets/
                https://premium.wpmudev.org/blog/create-a-wordpress-widget/

	Make the plugin available for translation:
	        https://ulrich.pogson.ch/load-theme-plugin-translations
	        https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	        https://developer.wordpress.org/reference/functions/plugin_dir_path/

	How to translate usernames in multilingual WordPress websites - Polylang hack:
	       https://www.khalidalnajjar.com/translate-usernames-multilingual-wordpress-websites/
	       https://wordpress.stackexchange.com/questions/178466/change-comment-author-display-name
	       https://memberium.com/how-to-customize-the-user-display-name/

	Към момента Polylang не поддържа превод на мета данните за потребителите
	Долните функции заменят името на потребителя (което се показва)
	със съдържанието на HTML коментар, поместен в описанието на потребителя (което може да се превежда),
	който (коментар) трябва да има формат, като тоизи представен долу.
  
 	At the moment the WP plugin Polylang doesn't support translation of the users display names. 
  The following funcions will replace the user's display name with the content of an HTM comment, 
  placed within the user's description field (that can be translated). 
  The comment should have the below format.

	Български (Bulgarian):
		<!-- dnt: ИТБИ -->

	English:
		<!-- dnt: ITFE -->

	Русский (Russina):
		<!-- dnt: ИТБЭ -->
*/

function polylang_user_display_name_translation_textdomain() {
	$domain = 'polylang-user-display-name-translation';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	// wp-content/languages/plugin-name/plugin-name-de_DE.mo
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	// wp-content/plugins/plugin-name/languages/plugin-name-de_DE.mo
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/' );
}
add_action( 'init', 'polylang_user_display_name_translation_textdomain' );

/* We do not need additional css at the moment
function polylang_user_display_name_translation_style() {
 	wp_enqueue_style( 'polylang-user-display-name-translation',
        plugins_url( 'polylang-user-display-name-translation.css', __FILE__ ),
        array(),
        'all'
    );
}
add_action( 'wp_enqueue_scripts', 'polylang_user_display_name_translation_style' );
*/


function replace_post_author_display_name_with_first_line_ot_its_description( $author ) {
    // get_the_author_meta( 'user_description' )
    // $display_name_from_the_description = trim(wp_strip_all_tags(strtok( get_the_author_meta( 'description' ), "\n")));

    $the_description = get_the_author_meta( 'description' );

    preg_match("/(<!-- dnt:)(.*)(-->)/", $the_description, $translated_name);

    if ( $translated_name[1] == '<!-- dnt:' && $translated_name[3] == '-->' ) {
		$display_name_from_the_description = trim(wp_strip_all_tags( $translated_name[2] ));
	}

    if ( !empty( $display_name_from_the_description ) ) {
	    $author = $display_name_from_the_description;
    } else {
        $author = trim(wp_strip_all_tags( get_the_author_meta( 'display_name' ) ));
    }
    return $author;
}
add_filter( 'the_author', 'replace_post_author_display_name_with_first_line_ot_its_description', 10, 1);


function replace_comment_author_display_name_with_first_line_ot_its_description( $author = '' ) {
    //$display_name_from_the_description = trim(wp_strip_all_tags(strtok( $user->description, "\n")));

    $comment = get_comment( $comment_ID );    /* Get the comment ID from WP_Query */
    if ( !empty( $comment->comment_author ) ) {
        if ( !empty( $comment->user_id ) ) {
            $user = get_user_by( 'ID', $comment->user_id );

            $the_description = $user->description;

            preg_match("/(<!-- dnt:)(.*)(-->)/", $the_description, $translated_name);

            if ( $translated_name[1] == '<!-- dnt:' && $translated_name[3] == '-->' ) {
				$display_name_from_the_description = trim(wp_strip_all_tags( $translated_name[2] ));
			}

            if ( !empty( $display_name_from_the_description  ) ) {
                $author = $display_name_from_the_description;
            } else {
                $author = $comment->comment_author;
            }

        } else {
            $author = $comment->comment_author;
        }
    } else {
        $author = __('Anonymous');
    }

    return $author;
}
add_filter('get_comment_author', 'replace_comment_author_display_name_with_first_line_ot_its_description', 10, 1);
?>
