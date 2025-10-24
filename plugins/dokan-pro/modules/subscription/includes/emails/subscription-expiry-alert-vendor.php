<?php

namespace WeDevs\DokanPro\Modules\ProductSubscription\Emails;

use DokanPro\Modules\Subscription\SubscriptionPack;
use WC_Email;

defined( 'ABSPATH' ) || exit;

/**
 * Subscription Expiry Alert Email to vendor.
 *
 * An email sent to the vendor before their subscription expires.
 *
 * @class       Dokan_Subscription_Expiry_Alert_Vendor
 * @version     1.0.0
 * @since       1.0.0
 * @author      YourName
 * @extends     WC_Email
 */
class SubscriptionExpiryAlertVendor extends WC_Email {


    /**
     * Subscription Object
     *
     * @var null|SubscriptionPack
     */
    public $subscription = null;

    /**
     * Constructor Method
     */
    public function __construct() {
        $this->id             = 'dokan_subscription_expiry_alert_vendor';
        $this->title          = __( 'Dokan Subscription Expiry Alert to Vendor', 'dokan' );
        $this->description    = __( 'This email is sent to vendor before their subscription expires', 'dokan' );
        $this->template_base  = DPS_PATH . '/templates/';
        $this->template_html  = 'emails/dokan-subscription-expiry-alert-vendor.php';
        $this->template_plain = 'emails/plain/dokan-subscription-expiry-alert-vendor.php';
        $this->placeholders   = [
            '{store_name}'         => '',
            '{expiry_date}'        => '',
            '{subscription_title}' => '',
            '{subscription_price}' => '',
        ];

        // Triggers for this email
        add_action( 'dps_send_subscription_expiration_alert_email', array( $this, 'trigger' ), 10, 2 );

        // Uncomment the line below if you want to trigger this email when a subscription is cancelled for testing
        //add_action( 'dokan_subscription_cancelled', array( $this, 'trigger' ), 20, 2 );

        // Call parent constructor
        parent::__construct();

        $this->recipient = 'vendor@ofthe.site';
    }

    /**
     * Get email subject.
     *
     * @return string
     */
    public function get_default_subject() {
        $subject = ( dokan_get_option( 'alert_email_subject', 'dokan_product_subscription' ) );

        return $subject ? $subject : __( 'Subscription Ending Soon', 'dokan' );
    }

    /**
     * Get email heading.
     *
     * @return string
     */
    public function get_default_heading() {
        return __( 'Your Subscription is About to Expire', 'dokan' );
    }

    /**
     * Trigger the sending of this email.
     *
     * @param int $customer_id The customer ID.
     * @param int $product_id The product ID.
     */
    public function trigger( $customer_id, $product_id ) {
        if ( ! $this->is_enabled() ) {
            return;
        }

        $this->setup_locale();
        $vendor = dokan()->vendor->get( $customer_id );
        if ( ! $vendor->get_id() ) {
            return;
        }

        // Ensure the subscription object is set
        $subscription = dokan()->subscription->get( $product_id );
        if ( ! $subscription instanceof SubscriptionPack ) {
            return;
        }

        $this->subscription = $subscription;

        $this->object    = $vendor;
        $this->recipient = method_exists( $vendor, 'get_email' ) ? $vendor->get_email() : '';

        $this->placeholders['{store_name}']         = method_exists( $vendor, 'get_shop_name' ) ? $vendor->get_shop_name() : '';
        $this->placeholders['{expiry_date}']        = method_exists( $subscription, 'get_pack_end_date' ) ? $this->subscription->get_pack_end_date() : '';
        $this->placeholders['{subscription_title}'] = method_exists( $subscription, 'get_package_title' ) ? $this->subscription->get_package_title() : '';
        $this->placeholders['{subscription_price}'] = method_exists( $subscription, 'get_price' ) ? dokan()->email->currency_symbol( $this->subscription->get_price() ) : '';

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
        $this->restore_locale();
    }

    /**
     * Get content html.
     *
     * @return string
     */
    public function get_content_html() {
        return wc_get_template_html(
            $this->template_html,
            array(
                'vendor'             => $this->object,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => false,
                'email'              => $this,
                'subscription'       => $this->subscription,
            ),
            'dokan/',
            $this->template_base
        );
    }

    /**
     * Get content plain.
     *
     * @return string
     */
    public function get_content_plain() {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'vendor'             => $this->object,
                'email_heading'      => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin'      => false,
                'plain_text'         => true,
                'email'              => $this,
                'subscription'       => $this->subscription,
            ),
            'dokan/',
            $this->template_base
        );
    }

    /**
     * Initialise settings form fields.
     */
    public function init_form_fields() {
        // Prepare placeholder text for the form fields
        $placeholder_text  = sprintf( __( 'Available placeholders: %s', 'dokan' ), '<code>' . implode( '</code>, <code>', array_keys( $this->placeholders ) ) . '</code>' );
        $this->form_fields = array(
            'enabled'            => array(
                'title'   => __( 'Enable/Disable', 'dokan' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable this email notification', 'dokan' ),
                'default' => 'yes',
            ),
            'subject'            => array(
                'title'       => __( 'Subject', 'dokan' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_subject(),
                'default'     => '',
            ),
            'heading'            => array(
                'title'       => __( 'Email heading', 'dokan' ),
                'type'        => 'text',
                'desc_tip'    => true,
                'description' => $placeholder_text,
                'placeholder' => $this->get_default_heading(),
                'default'     => '',
            ),
            'additional_content' => array(
                'title'       => __( 'Additional content', 'dokan' ),
                'description' => __( 'Text to appear below the main email content.', 'dokan' ) . ' ' . $placeholder_text,
                'css'         => 'width:400px; height: 75px;',
                'placeholder' => __( 'Thank you!.', 'dokan' ),
                'type'        => 'textarea',
                'default'     => $this->get_default_additional_content(),
                'desc_tip'    => true,
            ),
            'email_type'         => array(
                'title'       => __( 'Email type', 'dokan' ),
                'type'        => 'select',
                'description' => __( 'Choose which format of email to send.', 'dokan' ),
                'default'     => 'html',
                'class'       => 'email_type wc-enhanced-select',
                'options'     => $this->get_email_type_options(),
                'desc_tip'    => true,
            ),
        );
    }
}
