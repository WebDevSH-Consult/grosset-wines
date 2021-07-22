<?php
/**
 * Partial for Story landing page (page-story-landing.php)
 *
 */

for ( $i=1 ; $i<=6 ; $i++ ) {
    if ( get_option('g_story_title_'.$i ) ) {

        if ( $i%2 ) {
            $class = 'bg-grey';
            $bg = 'bg-to-left text-right';
            $first_col_position = 'col-md-6';
            $second_col_position = 'col-md-6';
        } else {
            $class = 'bg-white';
            $bg = 'bg-to-right';
            $first_col_position = 'col-md-6 col-md-push-6';
            $second_col_position = 'col-md-6 col-md-pull-6';
        }
        ?>
    <section class="story-section section-img <?php echo $class ?>" style="background-image: url(<?php echo get_option('g_story_img_'.$i ) ?>)">
        <div class="section-bg <?php echo $bg ?>">
            <div class="container">
                <div class="row">
                    <div class="<?php echo $first_col_position ?>"></div>
                    <div class="<?php echo $second_col_position ?>">
                        <div class="story-content">
                            <h2><?php echo get_home_text('g_story_title_'.$i ) ?></h2>
                            <p><?php echo get_home_text('g_story_text_'.$i ) ?></p>
                            <?php if ( get_option('g_story_url_'.$i ) ) { ?>
                            <p><a href="<?php echo get_option('g_story_url_'.$i ) ?>" class="btn">Read more</a></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }
}
