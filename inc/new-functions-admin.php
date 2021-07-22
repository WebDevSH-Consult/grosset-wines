<?php
/**
 * Theme options
 */

function grosset_settings_menu() {
    add_menu_page( 'Grosset Wines settings', 'Grosset', 'administrator', 'grosset-settings-page', 'grosset_settings_page', 'dashicons-admin-generic', 21  );
    add_submenu_page( 'grosset-settings-page', 'Homepage settings', 'Home', 'administrator', 'grosset-settings-page/home', 'grosset_home_settings_page');
    add_submenu_page( 'grosset-settings-page', 'Wines landing page settings', 'Wines', 'administrator', 'grosset-settings-page/wines', 'grosset_wines_settings_page');
    add_submenu_page( 'grosset-settings-page', 'Vineyards landing page settings', 'Vineyards', 'administrator', 'grosset-settings-page/vineyards', 'grosset_vineyards_settings_page');
    add_submenu_page( 'grosset-settings-page', 'Story landing page settings', 'Story', 'administrator', 'grosset-settings-page/story', 'grosset_story_settings_page');
    add_action( 'admin_init', 'grosset_settings_page_admin' );
}

function grosset_settings_page_admin() {

    register_setting( 'gw-group', 'home_img' );

    register_setting( 'gw-group', 'g_facebook' );
    register_setting( 'gw-group', 'g_twitter' );
    register_setting( 'gw-group', 'g_instagram' );
    register_setting( 'gw-group', 'g_location' );
    register_setting( 'gw-group', 'g_trustwave' );
    register_setting( 'gw-group', 'google_analytics' );

    for ( $i=1 ; $i<=7 ; $i++ ) {
        register_setting( 'gw-group', 'g_title_'.$i );
        register_setting( 'gw-group', 'g_text_'.$i );
        register_setting( 'gw-group', 'g_button_'.$i );
        register_setting( 'gw-group', 'g_img_url_'.$i );
        register_setting( 'gw-group', 'g_url_'.$i );
    }

    for ( $i=1 ; $i<=12 ; $i++ ) {
        register_setting( 'gw-wines-group', 'g_wine_product_id_'.$i );
        register_setting( 'gw-wines-group', 'g_wine_title_'.$i );
        register_setting( 'gw-wines-group', 'g_wine_text_'.$i );
        register_setting( 'gw-wines-group', 'g_wine_img_'.$i );
        register_setting( 'gw-wines-group', 'g_wine_url_'.$i );
		register_setting( 'gw-wines-group', 'g_wine_cat'.$i );
    }

    for ( $i=1 ; $i<=6 ; $i++ ) {
        register_setting( 'gw-vineyards-group', 'g_vineyards_title_'.$i );
        register_setting( 'gw-vineyards-group', 'g_vineyards_text_'.$i );
        register_setting( 'gw-vineyards-group', 'g_vineyards_img_'.$i );
        register_setting( 'gw-vineyards-group', 'g_vineyards_url_'.$i );
    }

    for ( $i=1 ; $i<=6 ; $i++ ) {
        register_setting( 'gw-story-group', 'g_story_title_'.$i );
        register_setting( 'gw-story-group', 'g_story_text_'.$i );
        register_setting( 'gw-story-group', 'g_story_img_'.$i );
        register_setting( 'gw-story-group', 'g_story_url_'.$i );
    }
}

function grosset_wines_settings_page() {
    if (!current_user_can('administrator'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }
    ?>
    <style>
        .g-admin input[type=text] {
            width: 100%;
            max-width: 480px;
        }
        .g-admin textarea {
            width: 100%;
            max-width: 480px;
            height: 12em;
        }
    </style>
    <div class="wrap g-admin">
        <h1>Wines landing page settings</h1>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'gw-wines-group' ); ?>
            <?php do_settings_sections( 'gw-wines-group' ); ?>

            <h2>Content</h2>
            <?php for ( $i=1 ; $i<=12 ; $i++ ) { ?>
                <hr>
                <h3>Wine <?php echo $i; ?> </h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="g_wine_title_<?php echo $i; ?>">Title</label></th>
                        <td><input type="text" name="g_wine_title_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_wine_title_'.$i) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_wine_text_<?php echo $i; ?>">Text</label></th>
                        <td>
                            <textarea name="g_wine_text_<?php echo $i; ?>"><?php echo esc_attr( get_option('g_wine_text_'.$i) ); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_wine_url_<?php echo $i; ?>">Wine page URL</label></th>
                        <td><input type="text" name="g_wine_url_<?php echo $i; ?>" value="<?php echo get_option('g_wine_url_'.$i); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_wine_img_<?php echo $i; ?>">Image URL</label></th>
                        <td><input type="text" name="g_wine_img_<?php echo $i; ?>" value="<?php echo get_option('g_wine_img_'.$i); ?>" /></td>
                    </tr>
					<tr valign="top">
                        <th scope="row"><label for="category<?php echo $i; ?>">Category</label></th>
                        <td><input type="text" name="category<?php echo $i; ?>" value="<?php echo get_option('category'.$i); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_wine_product_id_<?php echo $i; ?>">Product ID</label></th>
                        <td><input type="text" name="g_wine_product_id_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_wine_product_id_'.$i) ); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            <?php } ?>
        </form>
    </div>
    <?php
}

function grosset_vineyards_settings_page() {
    if (!current_user_can('administrator'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }
    ?>
    <style>
        .g-admin input[type=text] {
            width: 100%;
            max-width: 480px;
        }
        .g-admin textarea {
            width: 100%;
            max-width: 480px;
            height: 12em;
        }
    </style>
    <div class="wrap g-admin">
        <h1>Vineyards landing page settings</h1>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'gw-vineyards-group' ); ?>
            <?php do_settings_sections( 'gw-vineyards-group' ); ?>

            <h2>Content</h2>
            <?php for ( $i=1 ; $i<=6 ; $i++ ) { ?>
                <hr>
                <h3>Vineyard <?php echo $i; ?> </h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="g_vineyards_title_<?php echo $i; ?>">Title</label></th>
                        <td><input type="text" name="g_vineyards_title_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_vineyards_title_'.$i) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_vineyards_text_<?php echo $i; ?>">Text</label></th>
                        <td>
                            <textarea name="g_vineyards_text_<?php echo $i; ?>"><?php echo esc_attr( get_option('g_vineyards_text_'.$i) ); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_vineyards_url_<?php echo $i; ?>">Vineyard page URL</label></th>
                        <td><input type="text" name="g_vineyards_url_<?php echo $i; ?>" value="<?php echo get_option('g_vineyards_url_'.$i); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_vineyards_img_<?php echo $i; ?>">Image URL</label></th>
                        <td><input type="text" name="g_vineyards_img_<?php echo $i; ?>" value="<?php echo get_option('g_vineyards_img_'.$i); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            <?php } ?>
        </form>
    </div>
    <?php
}

function grosset_story_settings_page() {
    if (!current_user_can('administrator'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }
    ?>
    <style>
        .g-admin input[type=text] {
            width: 100%;
            max-width: 480px;
        }
        .g-admin textarea {
            width: 100%;
            max-width: 480px;
            height: 12em;
        }
    </style>
    <div class="wrap g-admin">
        <h1>Story landing page settings</h1>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'gw-story-group' ); ?>
            <?php do_settings_sections( 'gw-story-group' ); ?>

            <h2>Content</h2>
            <?php for ( $i=1 ; $i<=6 ; $i++ ) { ?>
                <hr>
                <h3>Story <?php echo $i; ?> </h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="g_story_title_<?php echo $i; ?>">Title</label></th>
                        <td><input type="text" name="g_story_title_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_story_title_'.$i) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_story_text_<?php echo $i; ?>">Text</label></th>
                        <td>
                            <textarea name="g_story_text_<?php echo $i; ?>"><?php echo esc_attr( get_option('g_story_text_'.$i) ); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_story_url_<?php echo $i; ?>">Story page URL</label></th>
                        <td><input type="text" name="g_story_url_<?php echo $i; ?>" value="<?php echo get_option('g_story_url_'.$i); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_story_img_<?php echo $i; ?>">Image URL</label></th>
                        <td><input type="text" name="g_story_img_<?php echo $i; ?>" value="<?php echo get_option('g_story_img_'.$i); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            <?php } ?>
        </form>
    </div>
    <?php

function grosset_settings_page() {
    if (!current_user_can('administrator'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }
    ?>
    <style>
        .g-admin input[type=text] {
            width: 100%;
            max-width: 480px;
        }
        .g-admin textarea {
            width: 100%;
            max-width: 480px;
            height: 12em;
        }
    </style>
    <div class="wrap g-admin">
        <h1>Grosset Wines theme settings</h1>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'gw-theme-group' ); ?>
            <?php do_settings_sections( 'gw-theme-group' ); ?>

            <h2>General settings</h2>

            <p>Coming soon...</p>
        </form>
    </div>
    <?php
}

function grosset_home_settings_page() {
    if (!current_user_can('administrator'))  {
        wp_die( __('You do not have sufficient pilchards to access this page.')    );
    }
    ?>
    <style>
        .g-admin input[type=text] {
            width: 100%;
            max-width: 480px;
        }
        .g-admin textarea {
            width: 100%;
            max-width: 480px;
            height: 12em;
        }
    </style>
    <div class="wrap g-admin">
        <h1>Grosset Wines homepage settings</h1>
        <form method="post" action="options.php" novalidate="novalidate">
            <?php settings_fields( 'gw-group' ); ?>
            <?php do_settings_sections( 'gw-group' ); ?>

            <h2>Homepage hero banner</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="home_img">Header image URL</label></th>
                    <td><input type="text" name="home_img" value="<?php echo get_option('home_img'); ?>" /></td>
                    <td>Image size 1600px x 900px max</td>
                </tr>
            </table>

            <h2>Homepage sections</h2>
            <?php for ( $i=1 ; $i<=7 ; $i++ ) { ?>
                <hr>
                <h3>Section <?php echo $i; ?> </h3>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="g_title_<?php echo $i; ?>">Title</label></th>
                        <td><input type="text" name="g_title_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_title_'.$i) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_text_<?php echo $i; ?>">Text</label></th>
                        <td>
                            <textarea name="g_text_<?php echo $i; ?>"><?php echo esc_attr( get_option('g_text_'.$i) ); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_button_<?php echo $i; ?>">Button label</label></th>
                        <td><input type="text" name="g_button_<?php echo $i; ?>" value="<?php echo esc_attr( get_option('g_button_'.$i) ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_url_<?php echo $i; ?>">Button URL</label></th>
                        <td><input type="text" name="g_url_<?php echo $i; ?>" value="<?php echo get_option('g_url_'.$i); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="g_img_url_<?php echo $i; ?>">Image URL</label></th>
                        <td><input type="text" name="g_img_url_<?php echo $i; ?>" value="<?php echo get_option('g_img_url_'.$i); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            <?php } ?>

            <h2>Site settings</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="g_location">Location</label></th>
                    <td><input type="text" name="g_location" value="<?php echo get_option('g_location'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="g_facebook">Facebook</label></th>
                    <td><input type="text" name="g_facebook" value="<?php echo esc_attr( get_option('g_facebook') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="g_twitter">Twitter</label></th>
                    <td><input type="text" name="g_twitter" value="<?php echo esc_attr( get_option('g_twitter') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="g_instagram">Instragram</label></th>
                    <td><input type="text" name="g_instagram" value="<?php echo get_option('g_instagram'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="g_trustwave">Trustwave Seal</label></th>
                    <td><input type="text" name="g_trustwave" value="<?php echo esc_attr( get_option('g_trustwave') ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="google_analytics">Google analytics code</label></th>
                    <td><textarea name="google_analytics"><?php echo esc_attr( get_option('google_analytics') ); ?></textarea></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
