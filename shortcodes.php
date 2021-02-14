<?php
function admin_shortcodes_page(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
    add_menu_page( 
        __( 'Theme Short Codes', 'textdomain' ),
        'Short Codes',
        'manage_options',
        'shortcodes',
        'shortcodes_page',
        'dashicons-book-alt',
        3
    ); 
}
add_action( 'admin_menu', 'admin_shortcodes_page' );
function shortcodes_page(){
	?>
	<div class="wrap">
		<h1>Theme Short Codes</h1>
		<ol>
			<li>[home-url slug=''] <span class="sdetagils">displays home url</span></li>
			<li>[site-identity class='' container_class=''] <span class="sdetagils">displays site identity according to theme option</span></li>
			<li>[site-name link='0'] <span class="sdetagils">displays site name with/without site url</span></li>
			<li>[copyright-symbol] <span class="sdetagils">displays copyright symbol</span></li>
			<li>[this-year] <span class="sdetagils">displays 4 digit current year</span></li>		
			<li>[feature-image wrapper_element='div' wrapper_atts='' height='' width=''] <span class="sdetagils">displays feature image</span></li>		
			<li>[font-awesome class="" container-class=""] <span class="sdetagils">displays feature image</span></li>		
			<li>[blog-teaser class="" gap="NULL/gap-sm/gap-md/gap-lg" posts="3"] <span class="sdetagils">displays feature image</span></li>		
			<li>[mos-embed url="" ratio="32by9/21by9/16by9/4by3/1by1"] <span class="sdetagils">displays Embeds</span></li>		
			<li>[mos-popup url="" icon-class=""] <span class="sdetagils">displays Popup</span></li>		
			<li>[social-menu class="" links=""] <span class="sdetagils">displays Social Icons</span></li>		
		</ol>
	</div>
	<?php
}
function home_url_func( $atts = array(), $content = '' ) {
	$atts = shortcode_atts( array(
		'slug' => '',
	), $atts, 'home-url' );

	return home_url( $atts['slug'] );
}
add_shortcode( 'home-url', 'home_url_func' );
function site_identity_func( $atts = array(), $content = null ) {
	global $forclient_options;
	$logo_url = ($forclient_options['logo']['url']) ? $forclient_options['logo']['url'] : get_template_directory_uri(). '/images/logo.png';
	$logo_option = $forclient_options['logo-option'];
	$html = '';
	$atts = shortcode_atts( array(
		'class' => '',
		'container_class' => ''
	), $atts, 'site-identity' ); 
	
	
	$html .= '<div class="logo-wrapper '.$atts['container_class'].'">';
		if($logo_option == 'logo') :
			$html .= '<a class="logo '.$atts['class'].'" href="'.home_url().'">';
			list($width, $height) = getimagesize($logo_url);
			$html .= '<img class="img-responsive img-fluid" src="'.$logo_url.'" alt="'.get_bloginfo('name').' - Logo" width="'.$width.'" height="'.$height.'">';
			$html .= '</a>';
		else :
			$html .= '<div class="text-center '.$atts['class'].'">';
				$html .= '<h1 class="site-title"><a href="'.home_url().'">'.get_bloginfo('name').'</a></h1>';
				$html .= '<p class="site-description">'.get_bloginfo( 'description' ).'</p>';
			$html .= '</div>'; 
		endif;
	$html .= '</div>'; 
		
	return $html;
}
add_shortcode( 'site-identity', 'site_identity_func' );

function site_name_func( $atts = array(), $content = '' ) {
	$html = '';
	$atts = shortcode_atts( array(
		'link' => 0,
	), $atts, 'site-name' );
	if ($atts['link']) $html .=	'<a href="'.esc_url( home_url( '/' ) ).'">';
	$html .= get_bloginfo('name');
	if ($atts['link']) $html .=	'</a>';
	return $html;
}
add_shortcode( 'site-name', 'site_name_func' );
function copyright_symbol_func() {
	return '&copy;';
}
add_shortcode( 'copyright-symbol', 'copyright_symbol_func' );
function this_year_func() {
	return date('Y');
}
add_shortcode( 'this-year', 'this_year_func' );


function feature_image_func( $atts = array(), $content = '' ) {
	global $mosacademy_options;
	$html = '';
	$img = '';
	$atts = shortcode_atts( array(
		'wrapper_element' => 'div',
		'wrapper_atts' => '',
		'height' => '',
		'width' => '',
	), $atts, 'feature-image' );

	if (has_post_thumbnail()) $img = get_the_post_thumbnail_url();	
	elseif(@$mosacademy_options['blog-archive-default']['id']) $img = wp_get_attachment_url( $mosacademy_options['blog-archive-default']['id'] ); 
	if ($img){
		if ($atts['wrapper_element']) $html .= '<'. $atts['wrapper_element'];
		if ($atts['wrapper_atts']) $html .= ' ' . $atts['wrapper_atts'];
		if ($atts['wrapper_element']) $html .= '>';
		list($width, $height) = getimagesize($img);
		if ($atts['width'] AND $atts['height']) :
			if ($width > $atts['width'] AND $height > $atts['height']) $img_url = aq_resize($img, $atts['width'], $atts['height'], true);
			else $img_url = $img;
		elseif ($atts['width']) :
			if ($width > $atts['width']) $img_url = aq_resize($img, $atts['width']);
			else $img_url = $img;
		else : 
			$img_url = $img;
		endif;
		list($fwidth, $fheight) = getimagesize($img_url);
		$html .= '<img class="img-responsive img-fluid img-featured" src="'.$img_url.'" alt="'.get_the_title().'" width="'.$fwidth.'" height="'.$fheight.'" />';
		if ($atts['wrapper_element']) $html .= '</'. $atts['wrapper_element'] . '>';
	}
	return $html;
}
add_shortcode( 'feature-image', 'feature_image_func' );

function font_awesome_func( $atts = array(), $content = '' ) {
    $html= "";
	$atts = shortcode_atts( array(
		'class' => '',
		'container-class' => '',
	), $atts, 'font-awesome' );
    $html .= '<div class="'.$atts['container-class'].'"><i class="fa fas '.$atts['class'].'"></i></div>';
	return $html;
}
add_shortcode( 'font-awesome', 'font_awesome_func' );

function blog_teaser_func( $atts = array(), $content = '' ) {
    $html= "";
	$atts = shortcode_atts( array(
		'class' => '',
        'gap' => '',
        'posts' => 3,
	), $atts, 'blog-teaser' );
    $args = array(
        'posts_per_page'=>$atts['posts'],
    );
    ob_start();
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) :   
    $n = 0;
    ?>
        <div class="mos-post-wrapper <?php $atts['class'] ?>">
            <div class="mos-post-grid <?php echo $atts['gap'] ?>">
                <?php while ( $query->have_posts() ) : $query->the_post();?>
                    <?php
                    $bg = '';
                    if (has_post_thumbnail()) $bg = get_the_post_thumbnail_url()
                    ?>
                    <div class="mos-post-grid-unit <?php if (!$n) echo 'mos-post-grid-eight mos-post-grid-merge-two-rows'; else echo 'mos-post-grid-four'?>" style="background-image:url(<?php echo $bg ?>);min-height:<?php echo $atts['min-height'] ?>">
                        <div class="wrapper">
                            <div class="post-meta text-white">
                                <div class="author d-inline-block"><i class="fa fa-user"></i> <?php echo get_the_author() ?></div>
                                <div class="date d-inline-block"><i class="fa fa-clock-o"></i> <?php echo get_the_date('M n, Y') ?></div>
                            </div>
                            <h3 class="post-title text-white"><?php echo get_the_title() ?></h3>
                        </div>
                        <a href="<?php echo get_the_permalink() ?>" class="hidden-link">Read More</a>
                    </div>
                    <?php $n++; ?>
                <?php endwhile;?>
            </div>
        </div>
    <?php        
    endif;
    wp_reset_postdata();
    $html = ob_get_clean();
    return $html;
}
add_shortcode( 'blog-teaser', 'blog_teaser_func' );

function social_menu_func( $atts = array(), $content = '' ) {
    $html= "";
	$atts = shortcode_atts( array(
        'class' => '',
		'links' => '',
	), $atts, 'social-menu' );
    $html .='<div class="'.$atts['class'].'">';
    if ($atts['links']) {
        $slices = explode(",", $atts['links']);
        $html .='<ul>';
        foreach($slices as $url){
            $html .='<li>';
            $url = trim($url);
            if (preg_match('/facebook/i', $url)) $html .= '<a href="'.$url.'" target="_blank"><i class="fa fa-facebook"></i></a>';
            elseif (preg_match('/skype/i', $url))$html .= '<a href="'.$url.'"><i class="fa fa-skype"></i></a>';
            elseif (preg_match('/twitter/i', $url))$html .= '<a href="'.$url.'" target="_blank"><i class="fa fa-twitter"></i></a>';
            elseif (preg_match('/linkedin/i', $url))$html .= '<a href="'.$url.'" target="_blank"><i class="fa fa-linkedin"></i></a>';
            elseif (preg_match('/google/i', $url))$html .= '<a href="'.$url.'" target="_blank"><i class="fa fa-google-plus"></i></a>';
            $html .='</li>';
        }
        $html .='</ul>';
    }
    $html .= '</div>';
	return $html;
}
add_shortcode( 'social-menu', 'social_menu_func' );

function mos_embed_func($atts = array(), $content = '') {
	$atts = shortcode_atts( array(
        'url' => '',
		'ratio' => '21by9',
	), $atts, 'mos-embed' );
    ob_start(); ?>
        <div class="embed-responsive embed-responsive-<?php echo $atts['ratio'] ?>">
            <iframe class="embed-responsive-item" src="<?php echo $atts['url'] ?>"></iframe>
        </div>
    <?php $html = ob_get_clean();
    return $html;
}
add_shortcode( 'mos-embed', 'mos_embed_func' );

function mos_popup_func($atts = array(), $content = '') {
	$atts = shortcode_atts( array(
        'url' => '',
        'icon-class' => 'fa-play',
	), $atts, 'mos-popup' );
    ob_start(); ?>
        <div class="popup-btn-wrapper">
            <a data-fancybox="gallery" href="<?php echo $atts['url'] ?>"><i class="fa <?php echo $atts['icon-class'] ?>"></i></a>
        </div>
    <?php $html = ob_get_clean();
    return $html;
}
add_shortcode( 'mos-popup', 'mos_popup_func' );

function highlight_func($atts = array(), $content = '') {
	$atts = shortcode_atts( array(
        'background' => '',
		'color' => '',
	), $atts, 'highlight' );
    ob_start(); ?>
        <span class="highlight" style="background-color:<?php echo $atts['background'] ?>;color:<?php echo $atts['color'] ?>"><?php echo do_shortcode($content) ?></span>
    <?php $html = ob_get_clean();
    return $html;
}
add_shortcode( 'highlight', 'highlight_func' );

function contact_box_func($atts = array(), $content = '') {
	$atts = shortcode_atts( array(
        'title' => '',
		'telephone' => '',
		'email' => '',
		'www' => '',
		'image' => '',
		'animate' => '',
	), $atts, 'contact_box' );
    ob_start(); ?>
        <div class="get_in_touch" style="background-image: url(<?php echo $atts['image'] ?>)">
            <h3><?php echo $atts['title'] ?></h3>
            <div class="get_in_touch_wrapper">
                <ul>
                    <?php if ($atts['telephone']) : ?>
                    <li class="phone phone-1">
                        <span class="icon"><i class="fa fa-phone"></i></span>
                        <span><a href="tel:<?php echo $atts['telephone'] ?>"><?php echo $atts['telephone'] ?></a></span>
                    </li>
                    <?php endif;?>
                    <?php if ($atts['email']) : ?>
                    <li class="email email-1">
                        <span class="icon"><i class="fa fa-envelope"></i></span>
                        <span><a href="mailto:<?php echo $atts['email'] ?>"><?php echo $atts['email'] ?></a></span>
                    </li>
                    <?php endif;?>
                    <?php if ($atts['www']) : ?>
                    <li class="www www-1">
                        <span class="icon"><i class="fa fa-globe"></i></span>
                        <span><a href="mailto:<?php echo $atts['www'] ?>"><?php echo $atts['www'] ?></a></span>
                    </li>
                    <?php endif;?>
                </ul>
            </div>
        </div>
    <?php $html = ob_get_clean();
    return $html;
}
add_shortcode( 'contact_box', 'contact_box_func' );
