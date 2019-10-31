<?php 
add_action('mep_event_details','mep_ev_details');


function mep_ev_details(){
global $post, $event_meta;  
// the_content();

$content_event = get_post($post->ID);
$content = $content_event->post_content;
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);
echo $content;

$mep_event_day = get_post_meta($post->ID, 'mep_event_day', true);

if ( $mep_event_day ){
      echo '<div class="mep-day-details-section">';
    echo '<h4>Event Days</h4>';
  foreach ( $mep_event_day as $field ) {

    ?>
      <div class="mep-day-title">
        <?php echo $field['mep_day_title']; ?>
      </div>
      <div class="mep-day-details">
        <p><?php echo $field['mep_day_content']; ?></p>
      </div>
    <?php
 
  }
    echo '</div>';
}

}