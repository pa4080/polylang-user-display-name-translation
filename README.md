# WordPress : Polylang plugin Add on

Translate the user's display name from its description. 

At the moment the WP plugin Polylang doesn't support translation of the users display names. The following funcions will replace the user's display name with the content of an HTM comment, placed within the user's description field (that can be translated). The comment should have the following format.

Additional notes, instructions, and references are presented in the plugin body.

## Installation

1. Download the plugin file into a sub directory located in your Wordpress `plugins/` directory. Let's call this directory `site-plugins`.

    ````bash
    cd <your-site-dir>/wp-content/plugins/
    mkdir site-plugins
    cd site-plugins
    wget -qO polylang-user-display-name-translation.php https://raw.githubusercontent.com/pa4080/polylang-user-display-name-translation/master/polylang-user-display-name-translation.php
    ````
2. Go to your admin's panel and activate the plugin.

3. Go to your users profile and add the transpation tags within the usres descriprion fields of the different languages. These tahs should have syntax as this:

    ````
    <!-- dnt: The Display Name Translation to be Displayed -->
    ````
  
    ![Examples of usages 1.](./images/polylang-user-display-name-translation.png)
    
4. Now go to your site and check the result.

    ![Examples of usages 1.](./images/polylang-user-display-name-translation-result.png)


