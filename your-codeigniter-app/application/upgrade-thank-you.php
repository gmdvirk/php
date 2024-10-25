<?php
/**
* Template Name: Upgrade Thank You Page
*
* @package WordPress
* @subpackage Twenty_Nineteen_Child
* @since Twenty Fourteen 1.0
*/
get_header();
?>
 
  
   <!-- Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<div style="background-color: #fff; padding-top: 3%; padding-bottom: 2%;">
  <div class="container">
    <div class="row">
      <div class="one-column">

       <h3 align="center"><?php the_field('heading_title');?></h3>
       <p><?php the_field('second_paragraph'); ?></p>
  <h4 align="center" style="color: #C00;"><?php the_field('heading_second');?></h4>
  <br />
  
  
      </div>
    </div>
    <!-- columns should be the immediate child of a .row -->
   <?php 
    query_posts(array( 
        'post_type' => 'upgrade_seo_guide',
        'showposts' => -1,
        'orderby'=>'title',
        'order' => 'ASC',
         
    ) );  
?>
<?php while (have_posts()) : the_post(); ?>
<div class="row">
    <div class="four columns"><img class="imgresponsive" src="<?php echo get_the_post_thumbnail_url();?>" width="300"></div>
    <div class="eight columns">
      <h5 align="center"><?php echo the_title(); ?></h5>
      <p><?php the_content();?></p>
      <p align="center"><a href="<?php the_field('download_link');?>"><img class="imgresponsive" src="images/download-button.png" width="630"></a></p>
<p>&nbsp;</p>
    </div>
  </div>
 
  <p align="center"><img class="imgresponsive" src="<?php echo get_stylesheet_directory_uri();?>/images/divider.png" width="960" height="2"> </p>
   <?php endwhile;
		wp_reset_query();
	?>
 
  <div class="row">
  
  
 
      <div class="one-column">
  <h4 align="center" style="color: #C00;"><?php the_field('heading_second');?></h4>
	<?php the_field('offer_pragraph'); ?>
   <h4 align="center"><strong><?php the_field('seo_heading');?></strong></h4><br>
	 <p align="center"><a href="/upgrade"><img class="imgresponsive" src="<?php the_field('seo_image');?>" width="750" ></a></p>
	<?php the_field('seo_paragraph');?>
<br />
      </div>
	  
	  
	  
    </div>
  </div>
</div>
<?php get_footer(); ?>
