<!--<div id="footer" class="container">

	<div class="row">

		<?php //dynamic_sidebar( 'va-footer' ); ?>

	</div>

</div>

<div id="post-footer" class="container">

	<div class="row">

	

		<div id="theme-info">&copy; Vantage Theme, business directory software created by <a href="http://www.appthemes.com" target="_blank">AppThemes</a>, powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>.</div>

	</div>

</div>

--> 



<?php appthemes_before_sidebar_widgets( 'va-footer' ); ?>



		<?php dynamic_sidebar( 'va-footer' ); ?>



		<?php appthemes_after_sidebar_widgets( 'va-footer' ); ?>

  

      

   <section class="footer-part-start">

      <div class="container">

          <div class="row">   

          

          <div class="col-xs-12">

           <div class="footer-part">

           <ul>

           <?php wp_nav_menu( array(

			'container' => false,

			'theme_location' => 'footer',

			'fallback_cb' => false

		) ); ?>

           </ul>

           </div>

          </div> 

          </div>

       </div>

   </section> 

   

  <!--- <section class="footer-social-start">

      <div class="container">

          <div class="row">   

          

          <div class="col-xs-6 bottom_re">

           <span class="copy-right-text"><p> <a href="http://wyhomesearch.com/">© Copyright 2017 -wyhomesearch.com</a></p></span>

           </div>

           <div class="col-xs-6 bottom_re">

           <div class="footer-part social">

           <ul>

            <li><a href="https://www.facebook.com/pages/WY-HOMES-Properties/108888812478933"><i class="fa fa-facebook"></i></a></li>

            <!--<li><a href="#"><i class="fa fa-google-plus"></i></a></li>-->

            <!---<li><a href="https://twitter.com/WYHOMES"><i class="fa fa-twitter"></i></a></li>-->

             <!--<li><a href="#"><i class="fa fa-instagram"></i></a></li>

              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>-->

            

            

           </ul>

           </div>

           </div>

          </div> 

          </div>

       </div>

   </section> 

   

  

   

 