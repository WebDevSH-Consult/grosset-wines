<?php

function set_value( $name, $type = 'text', $select_value = '' ) {
    if ( isset( $_POST[$name] ) ) {
        switch( $type ) {
            case 'text': {
                return ' value="' . htmlspecialchars( trim( $_POST[$name] ) ) . '" ';
                break;
            }
            case 'textarea': {
                if ( trim( $_POST[$name] ) !== '' ) {
                    return htmlspecialchars( $_POST[$name] );
                }
                return '';
                break;
            }
            case 'checkbox': {
                return ' checked="checked" ';
                break;
            }
            case 'radio': {
                if( $_POST[$name] == $select_value ){
                    return ' checked="checked" ';
                }
                break;
            }
            case 'select': {
                if ( $_POST[$name] == $select_value ) {
                    return ' selected="selected" ';
                }
                break;
            }
        }
    }
    return '';
}

function form_token() {
    $token = md5( uniqid( "", true ) );
    // Save token and keep for 1 hour
    set_transient( 'token-'.$token, $token, HOUR_IN_SECONDS );
    return $token;
}

function members_form() {

    $token = form_token();

    $form = '
     <form action=""  id="members" method="POST">
        <input type="hidden" name="token" value="' . $token . '">
        <input type="hidden" name="timestamp" value="' . time() . '">
        <fieldset>
	        <div class="form-row">
                <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" ' . set_value( 'first_name' ) . '>
            </div>
            <div class="form-row">
                <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" ' . set_value( 'last_name' ) . '>
            </div>
            <div class="form-row">
                <label for="email">Email address (required)</label>
                <input type="email" id="email" name="email" aria-required="true" required ' . set_value( 'email' ) . '>
            </div>
            <div class="form-row">
                <label for="number">Telephone or mobile number (required)</label>
                <input type="tel" id="number" name="number" aria-required="true" required ' . set_value( 'number' ) . '>
            </div>
            <div class="form-row">
                <label for="address">Postal address</label>
                <input type="text" id="address" name="address" ' . set_value( 'address' ) . '>
            </div>
            <div class="form-row">
                <label for="city">City</label>
                <input type="text" id="city" name="city" ' . set_value( 'city' ) . '>
            </div>
            <div class="form-row">
                <label for="state">State</label>
                <input type="text" id="state" name="state" ' . set_value( 'state' ) . '>
            </div>
            <div class="form-row">
                <label for="postcode">Postcode</label>
                <input type="text" id="postcode" name="postcode" ' . set_value( 'postcode' ) . '>
            </div>
            <div class="form-row form-message">
                <label for="message">Message</label>
                <input type="text" id="message" name="message">
            </div>
            <div class="form-row">
                <p><small><strong>Terms and conditions:</strong> By providing your contact details you subscribe to receive future special offers, wine release information 
                    and items exclusive to members from Grosset Wines and from our agents until such time as you request us to stop. 
                    Should you wish to opt out at any time simply let us know by phone or email. We will provide you with appropriate 
                    contact details every time we contact you.</small></p>
            </div>
            <div class="form-row">
                <input class="btn btn-primary btn-lg" type="submit" alt="Submit" name="member-submit" id="members-submit" value="Submit">
            </div>
        </fieldset>
    </form>
    ';

    return $form . disclaimer();
}

function call_back() {

    $token = form_token();

    $form = '
     <form action=""  id="members" method="POST">
        <input type="hidden" name="token" value="' . $token . '">
        <input type="hidden" name="timestamp" value="' . time() . '">
        <fieldset>
            <div class="form-row">
                <label for="contact_name">Name (optional)</label>
                <input type="text" id="contact_name" name="contact_name" ' . set_value( 'contact_name' ) . '>
            </div>
            <div class="form-row">
                <label for="number">Telephone or mobile number (required)</label>
                <input type="tel" id="number" name="number" aria-required="true" required ' . set_value( 'number' ) . '>
            </div>
            <div class="form-row form-message">
                <label for="message">Message</label>
                <input type="text" id="message" name="message">
            </div>
            <div class="form-row">
                <input class="btn btn-primary btn-lg" type="submit" alt="Submit" name="call-back-submit" id="members-submit" value="Request call back">
            </div>
        </fieldset>
    </form>
    ';

    return $form;

}

add_shortcode( 'call-back-form', 'call_back_shortcode' );
function call_back_shortcode() {

    if ( ! is_admin() && isset( $_POST['call-back-submit'] ) ) {

        // Is spam?
        if (  isset( $_POST['message'] ) ) {
            if ( trim($_POST['message'] ) != '' ) {
                // Spam!!
                return;
            }
        }
        if (  isset( $_POST['token'] ) ) {

            $token = filter_input( INPUT_POST, 'token' );
            $saved_token = get_transient( 'token-'.$token );

            if ( !$saved_token ) {
                // Spam!!
                return;
            } else {
                delete_transient( 'token-'.$token );
            }
        }
        if (  isset( $_POST['timestamp'] ) ) {

            if ( $_POST['timestamp'] + 3 > time() ) {
                // Spam!!
                echo members_form();
                return;
            }
        }

        // Process form
        $number = filter_input( INPUT_POST, 'number' );
        $name = filter_input( INPUT_POST, 'contact_name' );

        $form_content = '
        <p>Someone requested a call back for to purchase wine.</p>
        <p>Name : '.$name.'</p>
        <p>Telephone : '.$number.'</p>
        ';

        echo '<div class="alert alert-success"><p><strong>Thank you! Your request was sent successfully.</strong></p></div>';

        $email_headers = 'From: Grosset Wines <sales@grosset.com.au>';

        add_filter('wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        wp_mail( 'grossetsales@gmail.com', 'Membership call back request', $form_content, $email_headers );
        wp_mail( 'cb.creatistic@gmail.com', 'Membership call back request', $form_content, $email_headers );

    } else {
        return call_back();
    }
}

add_shortcode( 'member-form', 'member_shortcode' );
function member_shortcode() {

    if ( ! is_admin() && isset( $_POST['member-submit'] ) ) {

        // Is spam?
        if (  isset( $_POST['message'] ) ) {
            if ( trim($_POST['message'] ) != '' ) {
                // Spam!!
                return;
            }
        }
        if (  isset( $_POST['token'] ) ) {

            $token = filter_input( INPUT_POST, 'token' );
            $saved_token = get_transient( 'token-'.$token );

            if ( !$saved_token ) {
                // Spam!!
                return;
            } else {
                delete_transient( 'token-'.$token );
            }
        }
        if (  isset( $_POST['timestamp'] ) ) {

            if ( $_POST['timestamp'] + 3 > time() ) {
                // Spam!!
                echo members_form();
                return;
            }
        }

        // Process form
        $name = filter_input( INPUT_POST, 'first_name' );
        $surname = filter_input( INPUT_POST, 'last_name' );
        $email = filter_input( INPUT_POST, 'email' );
        $number = filter_input( INPUT_POST, 'number' );
        $address = filter_input( INPUT_POST, 'address' );
        $city = filter_input( INPUT_POST, 'city' );
        $state = filter_input( INPUT_POST, 'state' );
        $postcode = filter_input( INPUT_POST, 'postcode' );

        $form_content = '
        <p>Name : '.$name.' '.$surname.'</p>
        <p>Email : '.$email.'</p>
        <p>Telephone : '.$number.'</p>
        <p>Address : '.$address.'</p>
        <p>City : '.$city.'</p>
        <p>State : '.$state.'</p>
        <p>Postcode : '.$postcode.'</p>
        ';

        echo '<div class="alert alert-success"><p><strong>Thank you! Your request was sent successfully.</strong></p></div>';

        $email_headers = 'From: Grosset Wines <sales@grosset.com.au>';

        add_filter('wp_mail_content_type', function( $content_type ) {
            return 'text/html';
        });

        wp_mail( 'grossetsales@gmail.com', 'Membership request', $form_content, $email_headers );
        wp_mail( 'cb.creatistic@gmail.com', 'Membership request', $form_content, $email_headers );

        $thanks = thanks( $name );

        wp_mail( $email, 'Thank you for signing up to join the Grosset Wine Club', $thanks, $email_headers );

    } else {
        return members_form();
    }
}

function thanks( $name ) {

    $thanks = '
    <p><img src="https://www.grosset.com.au/wp-content/themes/grosset3/img/Grosset-Logo.png"></p>
    <p>Dear '.$name.',</p>
    <p>Thank you for signing up to join the <strong>Grosset Wine Club</strong>.</p>
    <p>There is currently a high demand to join the Grosset Wine Club so please allow 14 days for your membership to be confirmed. 
    We will notify you as soon as you are signed up. </p>
    <p>In the meantime if you have any queries, please don’t hesitate to call the office on 1800 088 223.</p>
    <p>Warm regards,<br><a href="https://www.grosset.com.au">Grosset Wines</a></p>
    ';

    return $thanks;
}

function disclaimer() {

    $disclaimer = '';

    return $disclaimer;
}

add_shortcode( 'gw_wc_reg_form', 'gw_wc_registration_form' );

function gw_wc_registration_form() {
    if ( is_admin() ) return;
    ob_start();
    if ( is_user_logged_in() ) {
        wc_add_notice( sprintf( __( 'You are currently logged in. If you wish to register with a different account please <a href="%s">log out</a> first', 'bbloomer' ), wc_logout_url() ) );
        wc_print_notices();
    } else {
        ?>
        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

            <?php do_action( 'woocommerce_register_form_start' ); ?>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                </p>

            <?php endif; ?>

            <p class="form-row form-row-first">
                <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
            </p>
            <p class="form-row form-row-last">
                <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
                <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
            </p>
            <p class="form-row form-row-wide">
                <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
                <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
            </p>
            <p class="form-row form-row-wide">
                <label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required />
            </p>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                </p>

            <?php else : ?>

                <p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

            <?php endif; ?>

            <?php do_action( 'woocommerce_register_form' ); ?>

            <p class="woocommerce-FormRow form-row">
                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
            </p>

            <?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>
        <?php
    }
    return ob_get_clean();
}

function gw_wc_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        // Phone input filed which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
    if ( isset( $_POST['billing_first_name'] ) ) {
        //First name field which is by default
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        // First name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        // Last name field which is by default
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        // Last name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        update_user_meta( $customer_id, 'customer_status', 'grosset-club-members' );
    }
}
add_action( 'woocommerce_created_customer', 'gw_wc_fields' );

function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( empty( $_POST['billing_first_name'] ) ) {
        $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
    }
    return $validation_errors;
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );
