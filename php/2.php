<?php

/**
 * Custom functions / External files
 */

require_once 'includes/custom-functions.php';


/**
 * Add support for useful stuff
 */

if ( function_exists( 'add_theme_support' ) ) {

    // Add support for document title tag
    add_theme_support( 'title-tag' );

    // Add Thumbnail Theme Support
    add_theme_support( 'post-thumbnails' );
    // add_image_size( 'custom-size', 700, 200, true );

    // Add Support for post formats
    // add_theme_support( 'post-formats', ['post'] );
    // add_post_type_support( 'page', 'excerpt' );

    // Localisation Support
    load_theme_textdomain( 'barebones', get_template_directory() . '/languages' );
}


/**
 * Hide admin bar
 */

 add_filter( 'show_admin_bar', '__return_false' );


/**
 * Remove junk
 */

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'start_post_rel_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


/**
 * Remove comments feed
 *
 * @return void
 */

function barebones_post_comments_feed_link() {
    return;
}

add_filter('post_comments_feed_link', 'barebones_post_comments_feed_link');


/**
 * Enqueue scripts
 */

function barebones_enqueue_scripts() {
    // wp_enqueue_style( 'fonts', '//fonts.googleapis.com/css?family=Font+Family' );
    // wp_enqueue_style( 'icons', '//use.fontawesome.com/releases/v5.0.10/css/all.css' );
    wp_enqueue_style( 'styles', get_stylesheet_directory_uri() . '/style.css?' . filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_script( 'scripts', get_stylesheet_directory_uri() . '/js/scripts.min.js?' . filemtime( get_stylesheet_directory() . '/js/scripts.min.js' ), [], null, true );
}

add_action( 'wp_enqueue_scripts', 'barebones_enqueue_scripts' );


/**
 * Add async and defer attributes to enqueued scripts
 *
 * @param string $tag
 * @param string $handle
 * @param string $src
 * @return void
 */

function defer_scripts( $tag, $handle, $src ) {

	// The handles of the enqueued scripts we want to defer
	$defer_scripts = [
        'SCRIPT_ID'
    ];

    // Find scripts in array and defer
    if ( in_array( $handle, $defer_scripts ) ) {
        return '<script type="text/javascript" src="' . $src . '" defer="defer"></script>' . "\n";
    }
    
    return $tag;
} 

add_filter( 'script_loader_tag', 'defer_scripts', 10, 3 );


/**
 * Add custom scripts to head
 *
 * @return string
 */

function add_gtag_to_head() {

    // Check is staging environment
    if ( strpos( get_bloginfo( 'url' ), '.test' ) !== false ) return;

    // Google Analytics
    $tracking_code = 'UA-*********-1';
    
    ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_code; ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo $tracking_code; ?>');
        </script>
    <?php
}

add_action( 'wp_head', 'add_gtag_to_head' );



/**
 * Remove unnecessary scripts
 *
 * @return void
 */

function deregister_scripts() {
    wp_deregister_script( 'wp-embed' );
}

add_action( 'wp_footer', 'deregister_scripts' );


/**
 * Remove unnecessary styles
 *
 * @return void
 */

function deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}

add_action( 'wp_print_styles', 'deregister_styles', 100 );


/**
 * Register nav menus
 *
 * @return void
 */

function barebones_register_nav_menus() {
    register_nav_menus([
        'header' => 'Header',
        'footer' => 'Footer',
    ]);
}

add_action( 'after_setup_theme', 'barebones_register_nav_menus', 0 );


/**
 * Nav menu args
 *
 * @param array $args
 * @return void
 */

function barebones_nav_menu_args( $args ) {
    $args['container'] = false;
    $args['container_class'] = false;
    $args['menu_id'] = false;
    $args['items_wrap'] = '<ul class="%2$s">%3$s</ul>';

    return $args;
}

add_filter('wp_nav_menu_args', 'barebones_nav_menu_args');


/**
 * Button Shortcode
 *
 * @param array $atts
 * @param string $content
 * @return void
 */

function barebones_button_shortcode( $atts, $content = null ) {
    $atts['class'] = isset($atts['class']) ? $atts['class'] : 'btn';
    return '<a class="' . $atts['class'] . '" href="' . $atts['link'] . '">' . $content . '</a>';
}

add_shortcode('button', 'barebones_button_shortcode');



add_shortcode( 'foobar', 'foobar_shortcode' );

function foobar_shortcode( $atts ){
	return '????????????! ?? ??????????????.';
}





/**
 * TinyMCE
 *
 * @param array $buttons
 * @return void
 */

function barebones_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    $buttons[] = 'hr';

    return $buttons;
}

add_filter('mce_buttons_2', 'barebones_mce_buttons_2');


/**
 * TinyMCE styling
 *
 * @param array $settings
 * @return void
 */

function barebones_tiny_mce_before_init( $settings ) {
    $style_formats = [
        // [
        //     'title'    => '',
        //     'selector' => '',
        //     'classes'  => ''
        // ],
        // [
        //     'title' => 'Buttons',
        //     'items' => [
        //         [
        //             'title'    => 'Primary',
        //             'selector' => 'a',
        //             'classes'  => 'btn btn--primary'
        //         ],
        //         [
        //             'title'    => 'Secondary',
        //             'selector' => 'a',
        //             'classes'  => 'btn btn--secondary'
        //         ]
        //     ]
        // ]
    ];

    $settings['style_formats'] = json_encode($style_formats);
    $settings['style_formats_merge'] = true;

    return $settings;
}

add_filter('tiny_mce_before_init', 'barebones_tiny_mce_before_init');


/**
 * Get post thumbnail url
 *
 * @param string $size
 * @param boolean $post_id
 * @param boolean $icon
 * @return void
 */

function get_post_thumbnail_url( $size = 'full', $post_id = false, $icon = false ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $thumb_url_array = wp_get_attachment_image_src(
        get_post_thumbnail_id( $post_id ), $size, $icon
    );
    return $thumb_url_array[0];
}


/**
 * Add Front Page edit link to admin Pages menu
 */

function front_page_on_pages_menu() {
    global $submenu;
    if ( get_option( 'page_on_front' ) ) {
        $submenu['edit.php?post_type=page'][501] = array( 
            __( 'Front Page', 'barebones' ), 
            'manage_options', 
            get_edit_post_link( get_option( 'page_on_front' ) )
        ); 
    }
}

add_action( 'admin_menu' , 'front_page_on_pages_menu' );



// ????????????????

add_shortcode( 'sql', 'sql_shortcode' );

function sql_shortcode( $atts ){
$servername = "localhost";
$username = "u1810140_wp202";
$password = "y8SS(p26O@";
$dbname = "u1810140_wp202";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
	
	
// echo "Connected successfully".$servername.", ".$username.", ".$password.",".$dbname.",";
$conn->set_charset("utf8");

	?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://rohh.ru/API/ro.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BETFM</title>
</head>
<body>
  <style>
    :root{
      --h0: 64px;
      --h1: 48px;
      --h2: 32px;
      --black:#111;
      --white:#eee;
      --f0:18px;
      --f1:16px;
      --f2:14px;
      --brand-c:#5e1;
      --brand-c1:#070;
      --upperspacing:calc(1px * 1);
      --spacing:calc(1px * 0.6);
      --width_h:1048px;
      --width_v:90vw;
      --s0:16px;
      --s1:8px;
      --s2:4px;
      --s3:2px;
      --s4:1px;
    }
    html{font-family: Roboto; font-size: var(--f0); background-color: var(--black); color: var(--white); width: 100vw;letter-spacing: var(--spacing); overflow-x: hidden; display: flex; justify-content: space-around; line-height: 1.4;}
    body{width: var(--width_h); height: auto; display: flex; align-items: center; justify-content: space-around; flex-direction: column;}

    button, a{cursor: pointer;}
    a:any-link{color: inherit; text-decoration: inherit;}

    img.icon{width: 24px; height:24px; background-color: transparent;  border-radius: 4px; display: block; margin: 0 4px 0 -8px;}

      /* a:not([style*="background-color: var(--brand-c)"]){box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c); } */
      header a:not(.brand):not(.bet):hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c);}
      header a.brand:hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--white);}

      .landing a:not(.brand):not(.bet):hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c);}
      .landing a.brand:hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--white);}

      
      header a.bet{background-color: transparent; color: transparent;}
      .bet>.body>.prognose>.line>a:hover>svg{filter: drop-shadow(0 0 var(--s3) rgba(0, 0, 0, 1));}
      .bet>.body>.prognose>.line>a:hover>svg>polyline{fill:var(--brand-c); stroke:var(--black);}
      body footer>a:hover{box-shadow: 0 0 0 0; opacity: 1; border: none;}

    @media screen and (max-width:1280px) {
        #screen4{display: none;}
        :root{
          --width_h:900px;
        }
    }
    @media screen and (max-width:1050px) {
        #screen3{display: none;}
        :root{
          --width_h:840px;
        }
    }
    @media screen and (max-width:820px) {
        #screen2{display: none;}
        :root{
          --f0:16px;
          --f1:14px;
          --f2:12px;
          --width_h:640px;
        }
    }
    @media screen and (max-width:660px) {
        #screen1{display: none;}
        :root{
          --f0:16px;
          --f1:14px;
          --f2:12px;
          --width_h:500px;
        }
    }
  </style>
  <!DOCTYPE html>
<html lang="ru">
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://rohh.ru/API/ro.css">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BETFM</title>
  <meta name="description" content="?????????????????? ???????????????? ???????????????????? ???????????? ???? ??????????.">

  <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
  m[i].l=1*new Date();
  for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
  k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(90884451, "init", {
       clickmap:true,
       trackLinks:true,
       accurateTrackBounce:true
  });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/90884451" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</head>
<body class="advertising">
  <style>
    :root{
      --h0: 64px;
      --h1: 48px;
      --h2: 32px;
      --black:#111;
      --white:#eee;
      --f0:18px;
      --f1:16px;
      --f2:14px;
      --brand-c:#5e1;
      --brand-c1:#070;
      --upperspacing:calc(1px * 1);
      --spacing:calc(1px * 0.6);
      --width_h:1096px;
      --width_v:90vw;
      --s0:16px;
      --s1:8px;
      --s2:4px;
      --s3:2px;
      --s4:1px;
    }
    html{font-family: Roboto; font-size: var(--f0); background-color: var(--black); color: var(--white); width: 100vw;letter-spacing: var(--spacing); overflow-x: hidden; display: flex; justify-content: space-around; line-height: 1.4;}
    body{width: 100%; height: auto; display: flex; align-items: center; justify-content: space-around; flex-direction: column;}
    article{display:flex; align-items: center; flex-direction: column; width: 100vw; height: auto;}

    header{width: var(--width_h);}
    /* body.advertising{
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-image: url(https://altermama.ru/wp-content/uploads/2013/08/brendirovanie-saita-primer1.jpg);
    }
    article{
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-image: url(https://altermama.ru/wp-content/uploads/2013/08/brendirovanie-saita-primer1.jpg);
    } */

    script, style{display: none !important;}

    button, a{cursor: pointer;}
    a:any-link{color: inherit; text-decoration: inherit;}

    img.icon{width: 24px; height:24px; background-color: transparent;  border-radius: 4px; display: block; margin: 0 4px 0 -8px;}

      /* a:not([style*="background-color: var(--brand-c)"]){box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c); } */
      header a:not(.brand):not(.bet):hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c);}
      header a.brand:hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--white);}

      .landing a:not(.brand):not(.bet):hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--brand-c);}
      .landing a.brand:hover{box-shadow: 0 0 var(--s1) var(--s3); opacity: 1; border: var(--s3) solid ; color: var(--white);}

      
      header a.bet{background-color: transparent; color: transparent;}
      .bet>.body>.prognose>.line>a:hover>svg{filter: drop-shadow(0 0 var(--s3) rgba(0, 0, 0, 1));}
      .bet>.body>.prognose>.line>a:hover>svg>polyline{fill:var(--brand-c); stroke:var(--black);}
      body footer>a:hover{box-shadow: 0 0 0 0; opacity: 1; border: none;}
      .phone{display: none !important;}

    @media screen and (max-width:1280px) {
        #screen4{display: none;}
        :root{
          --width_h:900px;
        }
    }
    @media screen and (max-width:1050px) {
        #screen3{display: none;}
        :root{
          --width_h:840px;
        }
    }
    @media screen and (max-width:820px) {
        #screen2{display: none;}
        :root{
          --f0:16px;
          --f1:14px;
          --f2:12px;
          --width_h:640px;
        }
    }
    @media screen and (max-width:660px) {
        #screen1{display: none;}
        :root{
          --f0:16px;
          --f1:14px;
          --f2:12px;
          --width_h:500px;
        }
    }
    
    @media screen and (max-width:100vh) {
        #screen1{display: flex !important;}
        #screen2{display: flex !important;}
        :root{
          --f0:14px;
          --f1:13px;
          --f2:12px;
          --width_h:100vw;
        }
        .desktop{display: none !important;}
        .phone{display: flex !important;}
        header>.upper{justify-content: space-around ; height: 32px;}
        .menu{display: none !important;}
        header>.lower>a>img{display: none;}
        header>.lower{background-color: var(--black);}
        header>.lower>a.rate{background-color: var(--black); color: var(--white); font-weight: 400;}
        header>.lower>a{padding: 0;}
        header>.lower>a:hover{box-shadow: none;}
        main>.landing{display: none;}
        main>.advertising1{height: min-content;}
        main>.advertising1>img{width: 320px; height: 50px;}
        .prognoseoftheday{height: var(--h1); background-color:var(--brand-c); width: 100vw; color: var(--black); font-weight: 600; display: flex; align-items: center;    justify-content: space-between;}
        .prognoseoftheday>img{width: calc(100vw - 150px);}
        .prognoseoftheday > a{padding: 8px 16px; margin: 0 8px; display: flex; align-items: center; justify-content: space-around; border-radius: 4px; color: var(--white); background-color: #111;}
        .main>.sport>.header{background-color: var(--black); color: var(--white);}
        .main>.sport>.header>h2>svg{display: none;}
        .main>.sport>.content{width: 100%;}
        .content>.advertising2,
        .content>.advertising4{display: none;}
        .main>.sport>.content>.bet{width:100%;}
        .content>.bet>.body>p{padding: 0 8px; font-size: 13px;}
        main>.content>.main{width: 95vw;}
        .content>.bet>.body>.prognose{display: flex; flex-direction: column; align-items: center; width: 100%;}
        .content>.bet>.body>.prognose>.line>a>svg>polyline{height: 48px; fill: #5e1;}
        .content>.bet>.body>.prognose>.line>a>svg>text{fill: #111;}
        .content>.bet>.body>.prognose>.line>a{ height: 48px;}
        .content>.bet>.body>.prognose>.line{background-color: #11e; color: var(--white); font-size: var(--f2);width: 100vw;}
        .content>.bet>.body>.prognose>.line>h3{color: var(--white); font-size: var(--f0);}
        body>header{margin:0}
        .upper>.logo>h2{margin: 0;}
        main>.footer>.description{display: none;}
        main>.footer{justify-content: space-around;}
        main>.content{justify-content: space-around;}
    }
    @media screen and (max-width:100vh) and (max-width:375px){
        #screen1{display: flex;}
        #screen2{display: none !important;}
    }
    @media screen and (max-width:100vh) and (max-width:330px){
        #screen1{display: none !important;}
        #screen2{display: none !important;}
    }
  </style>
  <header>
    <style>
      header{display: flex; align-items: center; justify-content: space-between; width: inherit; flex-direction: column;  margin: calc(var(--h0) * 2) 0 0 0;}
      header >*{display: flex; align-items: center;  width: var(--width_h); background-color: var(--black);}
      .upper{height: var(--h0); font-size: var(--f1);justify-content: space-between;}
      .lower{height: var(--h1);background-color: var(--brand-c1);justify-content: center;font-size: var(--f2);}

      .upper>.menu{display: flex; align-items: center;}
      .upper>.menu>.nav{display: flex; width: auto; height: var(--h1); background-color: var(--black);}
      .upper>.menu>.nav>a{display: flex; align-items: center; justify-content: space-around; background-color: var(--black); padding: 0 var(--s0);}
      .lower>a{text-transform: uppercase; background-color: var(--black); height: inherit;display: flex; align-items: center; justify-content: space-around; color: var(--white); height: 60%; padding: var(--s2) var(--s0); border-radius: var(--s1); letter-spacing: var(--upperspacing); font-weight: 400; margin: var(--s1);}
      .lower>a.rate{background-color: var(--brand-c); color: var(--black); font-weight: 800;}
      .upper>.logo{color: var(--brand-c); margin: 0 var(--s0)}

      .menu>.social{margin: 0 var(--s0);}
      .menu>.social>a{height: auto; width: auto; display: flex; align-items: center; justify-content: space-around;}

@media screen and (max-width:100vh) {
    
    header a:not(.brand):not(.bet):hover{box-shadow: unset; opacity: 1; border: unset; border-bottom: var(--s3) solid ; color: var(--brand-c);}
    header a.brand:hover{box-shadow: unset; opacity: 1; border: unset; border-bottom: var(--s3) solid ; color: var(--white);}
}
    </style>
    <div class="upper">
      <div class="logo" onclick="location.reload()"><h2 class="name">BETFM</h2></div>
      <div class="menu">
        <div class="social">
          <a href="https://t.me/"><svg fill="#1af" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 72 72" width="48px" height="48px"><path d="M36,12c13.255,0,24,10.745,24,24S49.255,60,36,60S12,49.255,12,36S22.745,12,36,12z M44.279,45.783	c0.441-1.354,2.51-14.853,2.765-17.513c0.077-0.806-0.177-1.341-0.676-1.58c-0.603-0.29-1.496-0.145-2.533,0.229	c-1.421,0.512-19.59,8.227-20.64,8.673c-0.995,0.423-1.937,0.884-1.937,1.552c0,0.47,0.279,0.734,1.047,1.008	c0.799,0.285,2.812,0.895,4.001,1.223c1.145,0.316,2.448,0.042,3.178-0.412c0.774-0.481,9.71-6.46,10.351-6.984	c0.641-0.524,1.152,0.147,0.628,0.672c-0.524,0.524-6.657,6.477-7.466,7.301c-0.982,1.001-0.285,2.038,0.374,2.453	c0.752,0.474,6.163,4.103,6.978,4.685c0.815,0.582,1.641,0.846,2.398,0.846S43.902,46.94,44.279,45.783z"/></svg></a>
        </div>
        <div class="nav">
          <a href="">?????????????? ????????????????????</a>
          <a href="">????????????????</a>
          <a href="">??????????????????</a>
        </div>
      </div>
    </div>
    <nav class="lower">
        <a href="#football"><img class="icon" src="https://rohhthone.github.io/betfm/icon1.svg" alt="icon"> ????????????</a><a href="#hockey"><img class="icon screen0" src="https://rohhthone.github.io/betfm/icon2.svg" alt="icon">????????????</a><a href="#basketball" id="screen1" ><img class="icon" src="https://rohhthone.github.io/betfm/icon6.svg" alt="icon">??????????????????</a><a href="#tennis" id="screen2" ><img class="icon" src="https://rohhthone.github.io/betfm/icon3.svg" alt="icon">????????????</a><a href="#fighting" id="screen3" ><img class="icon" src="https://rohhthone.github.io/betfm/icon4.svg" alt="icon">???????? ?? ??????</a><a href="#cybersport" id="screen4" ><img class="icon" src="https://rohhthone.github.io/betfm/icon5.svg" alt="icon">????????????????????</a><a class="rate brand" href="#express"><span class="icon"></span>??????????????????</a>
    </nav>
    <div class="phone prognoseoftheday">
      <a class="a">?????????????? ??????</a>
      <img src="https://rohhthone.github.io/betfm/1.svg">
    </div>
  </header>
  <main>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='football'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  ?>
<div class="sport" id="football">
  <div class="header">
    <h2>
      <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="240 240 600 600" width="32px" height="32px"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M613.78,336.1c35.33,14.46,66.54,36.42,90.84,66.16-1.94,3.38-4.06,6-5,8.95q-11.28,34.53-22.08,69.19c-1.2,3.84-3.23,5.82-7,7.25q-29.92,11.49-59.64,23.54c-3.3,1.33-5.69,1.35-8.75-.86Q575.49,491,548.45,472.15c-2.76-1.93-3.92-3.93-3.89-7.38.16-23.5,0-47,.24-70.48a9.85,9.85,0,0,1,3.52-6.81c19.35-14.6,39-28.85,58.36-43.39C609.3,342.12,611.15,339.12,613.78,336.1Z"/><path class="cls-1" d="M373.41,402.52c18-25.15,65.84-59.76,93-66.65-2,5.14,1.22,7.37,4.76,9.86,19.51,13.75,38.86,27.73,58.37,41.49a8,8,0,0,1,3.83,7.44q-.24,35,0,69.94c0,3.38-.93,5.48-3.75,7.46-18.16,12.7-36.13,25.69-54.35,38.31a9.42,9.42,0,0,1-7.52.87Q437,499.51,406.55,487a11.15,11.15,0,0,1-5.59-6.08c-7.86-23.7-15.33-47.53-23.08-71.28C377.13,407.37,375.27,405.44,373.41,402.52Z"/><path class="cls-1" d="M716,651.61c-3.46-1.15-5.92-2.66-8.39-2.67q-36.95-.27-73.89,0c-3.53,0-5.88-.91-8.16-3.77-11.9-15-24-29.79-36.19-44.54-2-2.47-2.48-4.54-1.42-7.63,7.32-21.48,14.34-43.07,21.76-64.52a11,11,0,0,1,5.31-5.78c19.9-8.77,40-17.16,59.92-25.85,3-1.32,5-1,7.64,1,19.73,14.72,39.58,29.28,59.4,43.89,2.82,2.09,5.45,5.41,9.81-.19C749.91,583.21,738.08,619.66,716,651.61Z"/><path class="cls-1" d="M328.31,545.11c2.66-1.08,5-1.55,6.76-2.83q30.06-22,59.88-44.21c3-2.22,5.24-2.52,8.65-1.06,20.17,8.62,40.5,16.89,60.65,25.56a10.62,10.62,0,0,1,5.39,5.65q10.51,32.37,20.27,65a9.52,9.52,0,0,1-1.62,7.4q-17.81,22.5-36.23,44.51a11.08,11.08,0,0,1-7.46,3.55c-24.63.27-49.26.09-73.89.27-2.6,0-5.19,1.47-8.47,2.47C344.37,628.77,327.14,574.94,328.31,545.11Z"/><path class="cls-1" d="M595.53,738.85c-37.48,12-75.59,11.65-112.8,0,.18-2.87,1-5.33.36-7.32-7.24-22.7-14.52-45.39-22.25-67.93-1.71-5-.9-8.28,2.28-12.2,11.7-14.41,23.13-29,34.51-43.71,2.28-2.95,4.65-4.29,8.52-4.26,22,.19,44,0,65.94.27a11.89,11.89,0,0,1,8,3.83c12.72,15,25,30.45,37.63,45.57,2.36,2.84,2.74,5.19,1.59,8.65-7.73,23.14-15.26,46.35-22.75,69.56C595.85,733.36,595.93,735.65,595.53,738.85Z"/><path class="cls-1" d="M539.81,479c18.82,14.12,37.33,27.93,55.68,41.94,1.14.87,1.85,3.62,1.4,5.06-6.62,20.92-13.54,41.74-20.21,62.63-.93,2.92-2.39,3.75-5.28,3.74q-32.4-.15-64.8,0c-2.88,0-4.36-.78-5.29-3.72-6.67-20.9-13.6-41.71-20.18-62.63A6.2,6.2,0,0,1,483,520.6C501.68,506.71,520.53,493,539.81,479Z"/><path class="cls-1" d="M704.14,660.1c2.86,4.3,1.92,7.36-1.08,10.91-23.74,28-53,48.36-86.92,62-1.85.75-4.76.25-6.55-.77-.87-.49-.83-3.69-.3-5.39q9.75-31.41,19.9-62.7c.54-1.65,2.79-3.9,4.26-3.92C657.1,660,680.76,660.1,704.14,660.1Z"/><path class="cls-1" d="M751.84,524.86c-.48.78-1.24,3.39-2.92,4.28-1.35.72-4.21-.09-5.72-1.17-17.53-12.59-34.87-25.43-52.42-38-3-2.16-3.53-4-2.38-7.47,6.72-20.07,13.18-40.23,19.87-60.32.57-1.71,2.17-4.35,3.24-4.33a7.66,7.66,0,0,1,5.28,3c19.37,27.53,30,58.32,33.84,91.61C751,516,751.33,519.63,751.84,524.86Z"/><path class="cls-1" d="M411.57,660.09c10.6,0,21.21.14,31.81-.08,3.29-.07,4.65,1.13,5.58,4.11,6.59,21.11,13.36,42.17,19.92,63.3.52,1.69.49,4.48-.54,5.48s-3.78.64-5.41,0c-33.54-13.64-62.52-33.7-85.95-61.47a23.21,23.21,0,0,1-2.82-3.56c-2.08-3.74.18-7.69,4.45-7.72C389.6,660,400.58,660.09,411.57,660.09Z"/><path class="cls-1" d="M538.26,323.21a208.55,208.55,0,0,1,55,7.33c2.18.59,3.92,2.82,5.86,4.3-1.28,1.64-2.26,3.7-3.88,4.86-17.12,12.18-34.4,24.13-51.48,36.36-3,2.11-4.9,2.16-7.87,0-17.24-12.33-34.66-24.41-51.93-36.69-1.46-1-3.58-3.61-3.21-4.29a9.39,9.39,0,0,1,4.9-4.39A191.61,191.61,0,0,1,538.26,323.21Z"/><path class="cls-1" d="M330.18,531.45c-.88-3.08-2.12-5.23-2-7.32,1.95-36.85,12.57-71,32.44-102.17,1.22-1.91,3.82-2.94,5.77-4.38,1.27,1.85,3.05,3.53,3.74,5.58,6.57,19.75,12.94,39.56,19.41,59.34.84,2.56,1.66,4.63-1.36,6.79C369.24,502.87,350.47,516.66,330.18,531.45Z"/></svg>
      ????????????
    </h2>
    <button><span class="up"></span></button>
    <button><span class="down"></span></button>
  </div>
	<div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone" style="width: auto">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='hockey'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  ?>
        <div class="sport" id="hockey">
          <div class="header">
            <h2><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="300 300 600 600"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M517.61,448.68c57.56.78,108.14,5.95,156.84,23.13,18.32,6.46,35.87,14.6,50.91,27.27,8.18,6.89,15,14.84,18.19,25.3,5,16.58-3.94,28-14.94,38.31-16.57,15.52-36.94,24.45-57.88,32.2-39.17,14.51-79.83,21.8-121.4,23.81-56.6,2.75-112.25-2.79-166.13-21.4-23.09-8-45.57-17.26-64-34a75.4,75.4,0,0,1-8.69-9.18c-12-15.23-10.71-32.14,3.13-47.2,15.39-16.75,35.21-26.24,56-34,33.82-12.66,69.08-18.85,104.92-21.84C490.92,449.74,507.42,449.23,517.61,448.68Zm19.7,142.89.17,1.23c23.36-2.86,46.85-5,70.07-8.78,24.63-4,48.64-10.85,70.29-23.9,13.09-7.9,24.3-17.59,26.27-34.19.7-5.87-2-10-7-10.58-5.19-.65-9.07,2.27-9.71,8.18a20.84,20.84,0,0,1-8.53,15c-6.44,4.72-13.13,9.41-20.34,12.75-30,13.87-62.12,19-94.7,21.65-13.17,1.09-26.42,1.08-39.63,1.75-6,.31-9.24,3.76-8.94,9,.28,4.94,3.56,7.73,9.42,7.85C528.87,591.64,533.09,591.57,537.31,591.57Z"/><path class="cls-1" d="M744.72,561.24c0,24,.52,46.42-.19,68.82-.42,12.9-9.44,21.47-19,28.79-19.41,14.79-42,22.89-65.2,29.14-55.37,14.88-111.89,18.36-168.9,15.65-39.39-1.87-78.28-7.19-116-19-18.89-5.91-37.16-13.3-53.21-25.3-13.19-9.87-21.16-22-20.33-39.47.82-17.22.17-34.52.19-51.78,0-1.83.15-3.66.26-6,33,36.06,76.79,48.16,121.65,56.58a540.24,540.24,0,0,0,209.87-2c26.57-5.44,52.3-13.6,76.21-26.72C723,582.83,734.7,574.07,744.72,561.24Z"/></svg>????????????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='tennis'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // output data of each row
  ?>
        <div class="sport" id="tennis">
          <div class="header">
            <h2>
          <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"  width="32px" height="32px"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M72.8,584C62.4,432.54,109.05,301.74,220.77,197c104-97.52,229.15-135.47,369.82-123.47C558.63,219,626.1,321.7,728,408.76c50,42.71,108.3,71.19,175.15,77.37a235.51,235.51,0,0,0,51.72-1.31c16.31-2.13,32.19-7.63,48.25-11.64,46.59,298.34-209.89,573.57-522.6,531,6-25.58,14.7-51.08,17.36-77.21,5.26-51.65-9.8-100-34.53-144.58C404.6,676.52,320.1,602,199.47,574.42c-38.31-8.76-76.58-6.41-113.64,8-3.07,1.2-6.29,2-9.47,2.94C75.94,585.51,75.34,585,72.8,584Z"/><path class="cls-1" d="M451.71,908.14c-.46,30-4.72,54.27-20.34,75.67-5.35,7.33-10.49,10.22-19.52,7.35Q155,909.68,83.88,649.88c-3.08-11.31,2.6-15,10.59-19.07,30.35-15.28,62.62-16.57,94.86-10.32,122.67,23.78,239.39,144,260.23,267C451,895.75,451.31,904.16,451.71,908.14Z"/><path class="cls-1" d="M642.86,82.77c78,16.7,145.78,52,204.77,103.27C915.76,245.27,963,318.71,989.48,405.45c4.35,14.23.65,20.12-12.09,24.94-36.13,13.67-72.85,13.26-108.33.84-124.32-43.52-206.54-127.4-237.64-257C624.08,143.64,626.55,112.2,642.86,82.77Z"/></svg>????????????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='boi'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // output data of each row
  ?>
    <div class="sport" id="fighting">
          <div class="header">
            <h2>
          
          <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080" width="32px" height="32px"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M793.35,435.76H746.66v18.16c0,75.31.4,150.62-.25,225.92-.4,45.33-35.24,82.39-81.13,88.43-41.95,5.52-84.8-22.06-97.17-63.72-3.36-11.29-4.57-23.59-4.64-35.45-.41-71.48-.21-143-.21-214.46V436H516.85V454c0,74.21.26,148.43-.12,222.64-.21,41-25.92,75.57-64,87.78-36.67,11.76-77.07-1.15-100.81-32.36-13.79-18.11-18.75-38.8-18.67-61.35.28-72,.11-144.07.11-216.1V436H286.69v18.12c0,66.58.27,133.16-.1,199.73-.27,50.14-25.75,84.66-70,96.35-53.16,14-110-25.16-112.07-80.08-2.24-58.86-1.06-117.84-1.18-176.77-.12-62.76-.48-125.52.14-188.27.53-53.6,35.79-92.54,85.69-95.6,31-1.91,57.06,9.27,76,33.67,7.6,9.77,15.15,13.88,27.23,13,13-1,26.07-.22,39.88-.22,1.56-5.35,3-9.88,4.2-14.48A91.31,91.31,0,0,1,425.58,173c41.74.19,78.64,28.73,87.75,69.75,2.53,11.39,7.49,14.44,17.63,13.23a19.68,19.68,0,0,1,4.91,0c17.92,2.55,28.32-1.23,34.26-22.23,11.23-39.65,49.19-62.66,90.76-60.55,38.74,2,73.22,29.68,82.16,68.25,2.89,12.49,8.43,16.22,19.87,14.52a12.78,12.78,0,0,1,3.27,0c17.59,2.14,28.08-.62,34.12-22.21,11.81-42.27,55.73-66.41,98.88-59.89,42.24,6.39,75.59,41.61,76.66,85.2,1.55,62.72.8,125.49.86,188.24.06,77.49.5,155-.31,232.47-.45,42.8-29.89,77.21-71.23,86.54-40.24,9.08-82.22-9.16-100.23-46.81-7.25-15.14-10.82-33.37-11.1-50.29-1.19-70.92-.5-141.87-.49-212.81Z"/><path class="cls-1" d="M964.54,790.88C981.8,846.65,940,904.66,881.76,906.32c-55.1,1.57-110.27.57-165.41.57-39.31,0-78.67,1.09-117.91-.65-58.6-2.59-99.46-62.63-80.78-118.14,4-11.79,13.21-21.8,21.22-34.55,30.62,39.59,67.88,61.53,116,61.6s85.6-21.73,115.33-60.3c23.67,30.83,52.7,51.36,90.5,58.18C897.93,819.76,932,811.4,964.54,790.88Z"/></svg>???????? ?? ??????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='cybersport'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // output data of each row
  ?>
    <div class="sport" id="cybersport">
          <div class="header">
            <h2>
            <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"  width="32px" height="32px"><defs><style>.cls-1{fill:#5e1;}</style></defs><path class="cls-1" d="M58.9,630c8-128.67,53.61-240,154.26-325,32.62-27.54,70.21-45.61,114-48.13,20.75-1.2,40.22,3.58,59.2,11.23,102.5,41.3,205.2,41.66,307.57.15,58-23.5,109.53-8.56,158.06,24.39,54,36.7,92,87.32,120.82,145.17,32.22,64.66,50.16,132.84,48.17,205.45-1.06,38.76-8.45,76.35-27.81,110.55-35.33,62.4-98.06,84.59-165.22,59.2-30.74-11.62-57.17-29.9-81.83-51.19-45.51-39.27-97.65-64.58-157.42-73C496,675.77,412.94,698.5,340.38,757.71,315.5,778,290.62,798,260.48,810.15c-83.26,33.47-158.25-1.63-185.92-87.3a281.18,281.18,0,0,1-10.24-41.27C61.59,664.79,60.67,647.71,58.9,630Zm301-169.41c-.25-5-.45-7.77-.51-10.57-.32-13.85,1-27.95-1.24-41.49-4-24.45-27.54-41.27-52.1-39.26a49.05,49.05,0,0,0-45.14,47.13c-.52,14.11-.1,28.24-.1,43.73-12.9,0-24.57-.56-36.16.18-8.73.56-18,1.3-25.91,4.56-20.95,8.59-32.25,31-28.24,53.07,4.23,23.18,22.81,39.76,46.72,40.78,14.1.6,28.24.11,43.53.11,0,15-.18,28.81,0,42.57.24,15.08,6.33,27.57,18.06,37,15.53,12.54,33,14.86,51,7S358.11,623,359,603.09c.64-14.41.13-28.87.13-44.11,11.16,0,20.73.32,30.26-.09,8.83-.38,18-.29,26.36-2.61,21.83-6,36.73-28.6,34.57-50.43-2.43-24.66-20.76-43.64-44.79-45.07C390.72,459.9,375.81,460.62,359.85,460.62Zm550.64,47.69c-.1-27.36-22.59-49.68-49.68-49.31-26.72.37-48.75,22.55-48.88,49.21a49.4,49.4,0,0,0,49.43,49.49C889.18,557.73,910.59,536.2,910.49,508.31ZM722,598.61a49.19,49.19,0,0,0,48.57,50.12c27,.29,49.53-21.87,49.91-49.08.37-26.65-21.4-48.94-48.19-49.36C744.73,549.87,722.39,571.33,722,598.61Zm49.36-131A49.26,49.26,0,1,0,722,418.36,48.95,48.95,0,0,0,771.4,467.64ZM679.79,557.7c27.25.34,49.33-21.46,49.75-49.1C730,482.23,708,459.7,681.17,459s-49.88,21.73-50.46,48.77S652.43,557.35,679.79,557.7Z"/></svg>????????????????????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='basketball'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  ?>
        <div class="sport" id="basketball">
          <div class="header">
            <h2><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="0 0 1200 1200"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M553.08,71.42c118.41,4.37,220.28,46.91,308.28,127.15-83.58,94.64-146.5,198.66-155.85,328.27H553.08Z"/><path class="cls-1" d="M553,552.63H705c2.75,132.4,66,236.91,153,330-56.79,67-207.51,128.65-305,124.9Z"/><path class="cls-1" d="M227.85,189.9c86.25-74.75,185-114.41,298.91-118.48V526.64H391.69C382,393.36,315.74,287,227.85,189.9Z"/><path class="cls-1" d="M392.23,552.83h134.5v453.82c-67.52,13.8-228.6-48.68-296-115.28C321.86,796.63,388.85,690,392.23,552.83Z"/><path class="cls-1" d="M367.59,553c-3.8,130.85-68,231.34-154.57,321.17C138.88,818.72,63.55,647.1,72.16,553Z"/><path class="cls-1" d="M70.92,526.77c5.71-124.47,51.33-230.35,138.9-319.86C293.42,298.73,356.6,399.17,367,526.77Z"/><path class="cls-1" d="M875.4,865.06C793.06,777,733,678.81,729.67,552.88H1006.8C1023.64,622.61,944,812.6,875.4,865.06Z"/><path class="cls-1" d="M730.31,526.67c9.48-122.5,68.87-220.21,148.44-310.54,82,87.86,124.55,190.8,130,310.54Z"/></svg>??????????????????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

<?php
$sql = "SELECT * FROM sport_table WHERE sport='express'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  ?>
        <div class="sport" id="express">
          <div class="header">
            <h2><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="0 0 1080 1080"><defs><style>.cls-1{fill:#111;}</style></defs><path class="cls-1" d="M902.45,504.1c21,24.37,39.55,50.34,39.76,84.44.15,25-10.25,44-34,54.54a224.32,224.32,0,0,1-73.88,19.27c-25.13,2-50.31,3.46-75.45,5.38a145.3,145.3,0,0,0-18.36,3c-1.73.36-3.17,2.12-4.74,3.24,1.22,1.68,2.07,4,3.71,4.92a103.8,103.8,0,0,0,15.39,7.07c44.57,15.33,89.22,30.4,133.78,45.72,38.87,13.37,77.66,27,116.47,40.49a11.32,11.32,0,0,1,5.42,3.42l-31.35-9.41q-205-61.3-410-122.63Q392.54,590.62,215.92,537.59q-93.43-28-186.88-56c-2.55-.77-6.42-.7-4.19-6l37.54,6.52Q173.1,501.34,283.8,520.52c57.22,10,114.37,20.39,171.6,30.34,21.3,3.7,37.83,15.76,53.83,29,6,5,12,10.19,17.91,15.32a111,111,0,0,0,39.26,22.14c81.11,26,164,39.34,249.3,36.37,24.89-.87,49.62-3.08,73.55-10.61a122.2,122.2,0,0,0,12.35-4.5c28.82-12.52,41-35.87,35.1-66.76-4.23-22.24-14.81-41.45-27.93-59.49C906.73,509.54,904.56,506.84,902.45,504.1Z"/><path class="cls-1" d="M896.16,545.39c-11,3.17-21.79,7-32.93,9.37-30.91,6.49-62.15,5.62-93.33,2.3-70.27-7.48-138.16-24-202.08-54.81-25.84-12.44-51.28-25.69-76.93-38.51-49.14-24.56-101.37-35.05-156-32.43q-92,4.41-183.85,10.53c-39,2.58-77.91,6.57-116.86,9.92-.71.06-1.43,0-2.17,0,1-7.67,5.55-12.8,12.5-14,78.93-14.16,157.83-28.44,236.79-42.34q49.11-8.64,98.45-15.89c28.26-4.11,56.46-4.92,83.55,6.87,17,7.39,31.33,18.75,45.82,29.81,25.4,19.4,50.7,38.95,75.57,59,35.15,28.39,74.75,47.2,118.42,58.47,42.66,11,85.77,18.08,129.88,18.08a252.31,252.31,0,0,0,31.68-2.1c10.44-1.34,20.73-3.84,31.08-5.84Z"/><path class="cls-1" d="M623.86,325.84c20.06-.5,42.54,4.83,64,16.89C741.06,372.66,791,407.05,836,448.42c8.91,8.19,7.85,12-3.93,15.51-34.48,10.18-68.62,7.3-102.53-2.87-55-16.48-104.81-43.34-151.69-75.91-13.61-9.46-26.31-20.23-39.22-30.66-4.5-3.64-4.4-6.4.66-9.32a117.67,117.67,0,0,1,21.16-10C579.58,328.71,599.43,325.8,623.86,325.84ZM719.4,442.32a779.07,779.07,0,0,1-149.06-88.14c12-4.07,23.88-7.81,36-10.18s24.39-3.38,36.6-5c-23.8-3.34-69.53,4.83-83.67,14.84C608.2,391.77,660.55,422.87,719.4,442.32Z"/><path class="cls-1" d="M68.5,421.5c21.33-6.4,42.56-13.15,64-19.12,96.09-26.75,192.17-53.53,288.42-79.69a675.7,675.7,0,0,1,70.69-15.09c29.55-4.73,59.46-3.53,89.14-.39,17.56,1.85,35,5.1,52.23,8.59-6.51,0-13,.09-19.55,0-32.39-.52-63.33,4.74-91.37,22.19a12.42,12.42,0,0,1-7.79,1.74c-42.54-6.62-84.79-3.12-126.43,5.61-47.89,10-95.38,22-142.94,33.6C187,393,129.29,407.55,71.48,421.89c-.86.21-1.75.32-2.63.48Z"/><path class="cls-1" d="M801.77,539.14a354.85,354.85,0,0,1-74.83-10.93c-6.73-1.68-13.39-3.7-20.16-5.23-4.53-1-6.27-4-6.78-8.11-.73-5.88,1.84-11.39,6.78-13.86,5.7-2.85,11.32-2.57,16.51,1.31a55.28,55.28,0,0,1,4.19,3.89c-1.61-6.91-8.7-11.2-16.77-10.5-10.33.89-16.24,8.73-16.92,23.17-2-.57-3.82-1-5.57-1.63-7.5-2.89-14.9-6.07-22.48-8.71-4.16-1.45-5.72-4.25-6-8.17-.42-6.32,1.74-11.56,7.19-15,5.92-3.74,12.19-3.92,18.16-.31,2.89,1.75,5.19,4.46,7.38,6.41-1-6.89-8.93-12.47-18.2-12.27-11.78.26-19.08,8.27-21,23.63A279.41,279.41,0,0,1,602,471.07c.07-.5.15-1,.22-1.5,5.31-.54,10.63-1.56,15.94-1.55,13.51,0,27.13-.45,40.48,1.18,10.15,1.23,20.74,3.86,29.77,8.53,37.3,19.28,74.09,39.56,111.06,59.49A26.42,26.42,0,0,1,801.77,539.14Z"/><path class="cls-1" d="M436.62,519.74,56.05,473.93l.26-2.08c127.17,12.49,254.07,27.33,380.48,46.21Z"/><path class="cls-1" d="M1055.71,723.87l-197.58-54,.16-2.22c5.46,0,11.32-1.3,16.34.2,60,17.86,119.87,36.13,179.77,54.31a7.58,7.58,0,0,1,1.42.77Z"/></svg>??????????????????</h2>
            <button><span class="up"></span></button>
            <button><span class="down"></span></button>
          </div>
          <div class="content">
<?php
  while($row = $result->fetch_assoc()) {
?>
<div onclick="toggleBet(this)" class="bet">
<div class="image icon onlyphone">
        <style>
            .image.icon.onlyphone{display: none;}
        @media screen and (max-width:100vh) {
            .active>.image.icon.onlyphone{display: flex; align-items: center; justify-content: center; gap: 4em; width: 100%; height: auto;margin: 16px 0;}
            .active>.image.icon.onlyphone>img{width: 48px; height: auto;}
        }
        </style>
        <img src="https://seeklogo.com/images/R/Real_Madrid_Club_de_Futbol-logo-60682932F8-seeklogo.com.png" alt="" class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cc/Georgia_National_Football_Team_Logo_2022.svg/2048px-Georgia_National_Football_Team_Logo_2022.svg.png" alt="" class="logo">
</div>
<div class="header">
        <div class="title"><?= $row["team"] ?></div>
        <div class="coefficient"><?= $row["coef"] ?></div>
        <div class="date">
			<?php
		  $dateBet = date_create($row["date"]);
		  echo date_format($dateBet,"d.m.Y"); 
			
			?></div>
        <div class="time">
		  <?php
		  $timeBet = date_create($row["time"]);
		  echo date_format($timeBet,"H:i"); 
			
			?>
		  </div>
        <button><span class="down"></span></button>
      </div>
      <div class="body">
        <p><?= $row["description"] ?></p>
        <div class="prognose">
          <h2>?????????????? BET FM</h2>
          <div class="line">
            <h3><?= $row["prognose"] ?></h3>
            <a href="" class="bet desktop">
              <svg viewBox="0 0 500 100" fill="white">
                <polyline points="0,100 50,0 500,0 500,100 0,100"/><text x="150" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
            <a href="" class="bet phone">
              <svg viewBox="0 0 200 100" fill="white" style="width: 148px;height:48px">
                <polyline points="-50,100 0,0 500,0 500,100 0,100"></polyline><text x="0" y="60" fill="black">?????????????? ????????????</text>
              </svg>
            </a>
          </div>
        </div>
        <div class="action"><button><span class="up"></span></button></div>
      </div>
    </div>
	  <?php
  }
	?>
		
		</div>
</div>
<?php
}
?>

</div>
      <div class="advertising2">
        <style>.advertising2{ width: auto; height: auto;}.advertising2>img{width: 300px; height: 500px; object-fit: cover; margin: 0 var(--s0);}</style>
        <img src="images/image_12.png" alt="">
      </div>
      <div class="advertising4">
        <style>.advertising4{width: 100%; height: auto;}.advertising4>img{width: 100%; height: 200px; object-fit: cover; margin: var(--s0) 0;}</style>
        <img src="images/image_10.png" alt="">
      </div>
    </div>
    <div class="footer">
      <style>
      .footer{width: 100%; border-top: var(--s2) solid var(--white); height: auto; box-sizing: border-box; padding: var(--h2); display: flex; justify-content: space-between; font-size: 0.8em;}
      .footer>.icon>a>img{width: var(--h2);}
      .footer>.icon{display: flex; flex-direction: column;}
      .footer>.description{display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--s2);}
      </style>
      <div class="icon">
        <a><img loading="lazy" src="https://rohhthone.github.io/betfm/images/image_14.png" alt=""></a>
        <a href="https://www.gamblingtherapy.org/ru"><img loading="lazy" src="https://rohhthone.github.io/betfm/images/image_15.png" alt="" ></a>
      </div>
      <div class="description">
        <a href="#football">????????????</a>
        <a href="#basketball">??????????????????</a>
        <a href="#tennis">????????????</a>
        <a href="#cybersport">????????????????????</a>
        <a href="#fighting">???????? ?? ??????</a>
        <a href="#express">????????????????</a>
        <a href="#hockey">????????????</a>
      </div>
    </div>
    <div class="info" style="text-align: center; font-size: 0.8em;">
      ?? 2022 ????????????.??????

?????? ?????????????????? ?????? ?????? ???????????? 18 ??????.<br>
???????????????? ???? ?????????? ???????????????????????????? ???? ?????????????????????????? ??????????????????, ?????? ???????????????? ???????????????? ???????????????????????????? ???? ???????????? ???????????????? ?????????????????? ????????????.<br>
?????? ?????????????????????????? ???????????????????? ?? ?????????? ???????????? ???????????????????????? ???????????? ???? Betfm<br><br>

?????????????????????????? ?? ?????????????????????? ?????? ????. ??? ????XX-XXXXX<br><br>

?????? ?????????? ?? ??????????????????: info@betfm.ru
    </div>
  </main>
  <footer>
    <style>footer{max-width: 100vw; min-width: 100%; background-color: #111; color: #eee; align-items: center; justify-content: center; display: flex; height: 48px;} footer>a, footer>a:any-link{all: unset; cursor: pointer; font-size: 19px; padding: 0 4px 0 4px; color: #eee important!;} footer>a:hover{transition: 0.3s; color: #11e;}</style>
    <a href="https://rohh.ru/">rohh</a>
    <a href="https://rohh.ru/privacy">privacy</a>
  </footer>
  <script>
    function toggleBet(e){
      var el=e;
      el.classList.toggle("active");
    }
    function ok(){
        document.getElementsByClassName('sport')[0].classList.toggle('first');
        console.log('work');
        console.log(document.getElementsByClassName('sport')[0]);
    }

    document.onload = ok();
  </script>
  <style>
    .sport.first::before{display: none !important;}
  </style>
</body>
</html>



<?php
}