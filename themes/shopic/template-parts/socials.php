<?php
/**
 * $Desc
 *
 * @version    $Id$
 * @package    wpbase
 * @author     Opal  Team <opalwordpress@gmail.com>
 * @copyright  Copyright (C) 2017 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.pavothemes.com
 * @support  http://www.pavothemes.com/questions/
 */
/**
 * Enable/distable share box
 */

$heading = apply_filters('shopic_social_heading', esc_html__('Share:', 'shopic'));

if (shopic_get_theme_option('social_share')) {
    ?>
    <div class="shopic-social-share">
        <?php if (!is_singular('post')): ?>
            <?php echo '<span class="social-share-header">' . esc_html($heading) . '</span>'; ?>
        <?php endif; ?>
        <?php if (shopic_get_theme_option('social_share_facebook')): ?>
            <a class="social-facebook"
               href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&display=page"
               target="_blank" title="<?php esc_html_e('Share on facebook', 'shopic'); ?>">
                <i class="shopic-icon-facebook"></i>
                <span><?php esc_html_e('Facebook', 'shopic'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (shopic_get_theme_option('social_share_twitter')): ?>
            <a class="social-twitter"
               href="http://twitter.com/home?status=<?php the_title(); ?> <?php the_permalink(); ?>" target="_blank"
               title="<?php esc_html_e('Share on Twitter', 'shopic'); ?>">
                <i class="shopic-icon-twitter"></i>
                <span><?php esc_html_e('Twitter', 'shopic'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (shopic_get_theme_option('social_share_linkedin')): ?>
            <a class="social-linkedin"
               href="http://linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>&amp;title=<?php the_title(); ?>"
               target="_blank" title="<?php esc_html_e('Share on LinkedIn', 'shopic'); ?>">
                <i class="shopic-icon-linkedin"></i>
                <span><?php esc_html_e('Linkedin', 'shopic'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (shopic_get_theme_option('social_share_google-plus')): ?>
            <a class="social-google" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" target="_blank"
               title="<?php esc_html_e('Share on Google plus', 'shopic'); ?>">
                <i class="shopic-icon-google-plus"></i>
                <span><?php esc_html_e('Google+', 'shopic'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (shopic_get_theme_option('social_share_pinterest')): ?>
            <a class="social-pinterest"
               href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&amp;description=<?php echo urlencode(get_the_title()); ?>&amp;; ?>"
               target="_blank" title="<?php esc_html_e('Share on Pinterest', 'shopic'); ?>">
                <i class="shopic-icon-pinterest-p"></i>
                <span><?php esc_html_e('Pinterest', 'shopic'); ?></span>
            </a>
        <?php endif; ?>

        <?php if (shopic_get_theme_option('social_share_email')): ?>
            <a class="social-envelope" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"
               title="<?php esc_html_e('Email to a Friend', 'shopic'); ?>">
                <i class="shopic-icon-envelope"></i>
                <span><?php esc_html_e('Email', 'shopic'); ?></span>
            </a>
        <?php endif; ?>
    </div>
    <?php
}
?>
