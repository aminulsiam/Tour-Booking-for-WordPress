<?php 
add_action('mep_event_location','mep_ev_location');
add_action('mep_event_location_ticket','mep_ev_location_ticket');






function mep_ev_location_cart($event_id,$event_meta){
$location_sts = get_post_meta($event_id,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $event_id, 'mep_org' );
$org_id = $org_arr[0]->term_id;

       echo get_term_meta( $org_id, 'org_location', true ); ?>,<?php if(get_term_meta( $org_id, 'org_street', true )){ ?><?php echo get_term_meta( $org_id, 'org_street', true ); ?>, <?php }  if(get_term_meta( $org_id, 'org_city', true )){ ?> <?php echo get_term_meta( $org_id, 'org_city', true ); ?>, <?php } if(get_term_meta( $org_id, 'org_state', true )){ echo get_term_meta( $org_id, 'org_state', true ); ?>, <?php } if(get_term_meta( $org_id, 'org_postcode', true )){ ?> <?php echo get_term_meta( $org_id, 'org_postcode', true ); ?>, <?php } if(get_term_meta( $org_id, 'org_country', true )){ ?> <?php echo get_term_meta( $org_id, 'org_country', true ); ?> <?php } 
}else{
?>
           <?php echo $event_meta['mep_location_venue'][0]; ?>, <?php if($event_meta['mep_street'][0]){ ?><?php echo $event_meta['mep_street'][0]; ?>, <?php }  if($event_meta['mep_city'][0]){ ?> <?php echo $event_meta['mep_city'][0]; ?>, <?php } if($event_meta['mep_state'][0]){ ?> <?php echo $event_meta['mep_state'][0]; ?>, <?php } if($event_meta['mep_postcode'][0]){ ?> <?php echo $event_meta['mep_postcode'][0]; ?>, <?php } if($event_meta['mep_country'][0]){ ?> <?php echo $event_meta['mep_country'][0]; ?> <?php } 
         
     }

}




function mep_ev_location_ticket($event_id,$event_meta){
$location_sts = get_post_meta($event_id,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $event_id, 'mep_org' );
$org_id = $org_arr[0]->term_id;
?>
 		<?php echo get_term_meta( $org_id, 'org_location', true ); ?>,
          <?php if(get_term_meta( $org_id, 'org_street', true )){ ?><?php echo get_term_meta( $org_id, 'org_street', true ); ?>, <?php } ?> 
          <?php if(get_term_meta( $org_id, 'org_city', true )){ ?> <?php echo get_term_meta( $org_id, 'org_city', true ); ?>, <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_state', true )){ ?> <?php echo get_term_meta( $org_id, 'org_state', true ); ?>, <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_postcode', true )){ ?> <?php echo get_term_meta( $org_id, 'org_postcode', true ); ?>, <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_country', true )){ ?> <?php echo get_term_meta( $org_id, 'org_country', true ); ?> <?php } 
}else{
?>
		 <?php echo $event_meta['mep_location_venue'][0]; ?>,
          <?php if($event_meta['mep_street'][0]){ ?><?php echo $event_meta['mep_street'][0]; ?>, <?php } ?> 
          <?php if($event_meta['mep_city'][0]){ ?> <?php echo $event_meta['mep_city'][0]; ?>, <?php } ?>
          <?php if($event_meta['mep_state'][0]){ ?> <?php echo $event_meta['mep_state'][0]; ?>, <?php } ?>
          <?php if($event_meta['mep_postcode'][0]){ ?> <?php echo $event_meta['mep_postcode'][0]; ?>, <?php } ?>
          <?php if($event_meta['mep_country'][0]){ ?> <?php echo $event_meta['mep_country'][0]; ?> <?php } 
         
	}

}


function mep_ev_location(){
global $post,$event_meta;	

$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
?>
 		  <p><?php echo get_term_meta( $org_id, 'org_location', true ); ?>,</p>
          <?php if(get_term_meta( $org_id, 'org_street', true )){ ?><p><?php echo get_term_meta( $org_id, 'org_street', true ); ?>,</p> <?php } ?> 
          <?php if(get_term_meta( $org_id, 'org_city', true )){ ?> <p><?php echo get_term_meta( $org_id, 'org_city', true ); ?>,</p> <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_state', true )){ ?> <p><?php echo get_term_meta( $org_id, 'org_state', true ); ?>,</p> <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_postcode', true )){ ?> <p><?php echo get_term_meta( $org_id, 'org_postcode', true ); ?>,</p> <?php } ?>
          <?php if(get_term_meta( $org_id, 'org_country', true )){ ?> <p><?php echo get_term_meta( $org_id, 'org_country', true ); ?></p> <?php } 
}else{
?>
		 <p><?php echo $event_meta['mep_location_venue'][0]; ?>,</p>
          <?php if($event_meta['mep_street'][0]){ ?><p><?php echo $event_meta['mep_street'][0]; ?>,</p> <?php } ?> 
          <?php if($event_meta['mep_city'][0]){ ?> <p><?php echo $event_meta['mep_city'][0]; ?>,</p> <?php } ?>
          <?php if($event_meta['mep_state'][0]){ ?> <p><?php echo $event_meta['mep_state'][0]; ?>,</p> <?php } ?>
          <?php if($event_meta['mep_postcode'][0]){ ?> <p><?php echo $event_meta['mep_postcode'][0]; ?>,</p> <?php } ?>
          <?php if($event_meta['mep_country'][0]){ ?> <p><?php echo $event_meta['mep_country'][0]; ?></p> <?php } 
         
	}

}




add_action('mep_event_location_venue','mep_ev_venue');
function mep_ev_venue(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_location', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_location_venue'][0]; ?></span>
<?php
}
}


add_action('mep_event_location_street','mep_ev_street');
function mep_ev_street(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_street', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_street'][0]; ?></span>
<?php
}
}


add_action('mep_event_location_city','mep_ev_city');
function mep_ev_city(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_city', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_city'][0]; ?></span>
<?php
}
}


add_action('mep_event_location_state','mep_ev_state');
function mep_ev_state(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_state', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_state'][0]; ?></span>
<?php
}
}


add_action('mep_event_location_postcode','mep_ev_postcode');
function mep_ev_postcode(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_postcode', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_postcode'][0]; ?></span>
<?php
}
}


add_action('mep_event_location_country','mep_ev_country');
function mep_ev_country(){
global $post,$event_meta;	
$location_sts = get_post_meta($post->ID,'mep_org_address',true);
if($location_sts){
$org_arr = get_the_terms( $post->ID, 'mep_org' );
$org_id = $org_arr[0]->term_id;
	echo "<span>".get_term_meta( $org_id, 'org_country', true )."</span>";
}else{
?>
	<span><?php echo $event_meta['mep_country'][0]; ?></span>
<?php
}
}