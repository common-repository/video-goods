<?php

function register_venby_embed_plugin_scripts()
{
    wp_register_script("vg-js", plugins_url("js/scripts.js", dirname(__FILE__)));
}

add_action("wp_enqueue_scripts", "register_venby_embed_plugin_scripts");

function register_venby_embed_plugin_styles()
{
    wp_register_style("vg-css", plugins_url("css/custom.css", dirname(__FILE__)));
}

add_action("wp_enqueue_scripts", "register_venby_embed_plugin_styles");


function view($campaign_id, $width, $height, $type, $trigger_text, $font_color, $bg_color, $btn_border_color, $custom_trigger_text, $show_background, $exit_text, $auto_popup, $custom_button_size, $cart_popup, $background_cart, $links_color)
{
	if(empty($background_cart)){
		$background_cart ='262E2F';
	}else{
		$background_cart = str_replace('#','', $background_cart);
	}

	if(empty($links_color)){
		$links_color ='00a2ff';
	}else{
		$links_color = str_replace('#','', $links_color);
	}

    if ($show_background == 'on') {
        $show_background = 'false';
    } else {
        $show_background = "";
    }

    if ($auto_popup == 'on') {
        $auto_popup = 'true';
    } else {
        $auto_popup = 'false';
    }

    if ($cart_popup == 'on') {
        $cart_popup = 'true';
    }
    $link = '';
    if ($type === 'custom') {
        $link = '<a href="javascript:;" data-video-goods="'.$campaign_id.'">'.$custom_trigger_text.'</a>';
    }

    if($type == 'thumbnail'){
        $script = Venby_Editor_View::getScript($campaign_id);
        return $script.$link;
    } else{




        return '<script type="text/javascript">
                (function() {
                    var venbyConfig = {
                        campaignID:"'.$campaign_id.'",
                        show_background:"'.$show_background.'",
                        background:"'.$show_background.'",
                        image:"",
                        image_file:"",
                        exit_text:"'.$exit_text.'",
                        width:"'.$width.'",
                        autoopen:'.$auto_popup.',
                        height:'.$height.',
                        embed_type:"'.$type.'",
                        font_color:"'.$font_color.'",
                        bg_color:"'.$bg_color.'",
                        btn_border_color:"'.$btn_border_color.'",
                        trigger_text:"'.$trigger_text.'",
                        custom_button_size:"'.$custom_button_size.'",
                        cart_popup: "'.$cart_popup.'",
                      	colors_scheme: \'{"background":"'.$background_cart.'", "link":"'.$links_color.'"}\'
                        
                };
                    var btn_size = venbyConfig.custom_button_size.split("|");
                    var btn_height = btn_size[1];
                    var btn_width = btn_size[0];
                    if(venbyConfig.cart_popup === "true"){
                        venbyConfig.show_background = false;
                    }
                   
                    document.write("<div class=\'video-goods-container\' style=\'color:"+venbyConfig.font_color+"\'  id=\'video-goods-container-"+btoa(JSON.stringify(venbyConfig))+"\'></div>");
                    
                    var vgembed = document.createElement(\'script\'); 
                    vgembed.type = \'text/javascript\'; 
                    vgembed.async = true;                            
                   vgembed.src = (\'https:\' == document.location.protocol ? \'https://\' : \'http://\') + \'venby.tv/buy/js/embed2.1.js\';

var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(vgembed, s);

                    
                        vgembed.onload = function () {
                            new VideoGoodsEmbed(venbyConfig);
                            if(venbyConfig.cart_popup != "true"){
                                document.getElementById("video-goods-container-"+btoa(JSON.stringify(venbyConfig))).childNodes[0].style.color = venbyConfig.font_color;
                                                        
                                 document.getElementById("video-goods-container-"+btoa(JSON.stringify(venbyConfig))).childNodes[0].style.backgroundColor = venbyConfig.bg_color;
                                                        
                                                        if(venbyConfig.btn_border_color) document.getElementById("video-goods-container-"+btoa(JSON.stringify(venbyConfig))).childNodes[0].style.border = "1px solid "+venbyConfig.btn_border_color;
                                                      
                                                        if(venbyConfig.custom_button_size) document.getElementById("video-goods-container-"+btoa(JSON.stringify(venbyConfig))).childNodes[0].style.width = btn_width;
                                                        if(venbyConfig.custom_button_size) document.getElementById("video-goods-container-"+btoa(JSON.stringify(venbyConfig))).childNodes[0].style.height = btn_height;
                            }
                    }                  
                })();
                //]]>
                </script>
                '.$link;
    }
}

function venby_shortcode($atts)
{


    extract(shortcode_atts(array(
        'cid' => '',
        'w' => '',
        'h' => '',
        't' => '',
        'tt' => '',
        'fc' => '',
        'bgc' => '',
        'ctt' => '',
        'brc' => '',
        'sb' => '',
        'et' => '',
        'ap' => '',
        'cbs' => '',
        'cpu' => '',
		'cbc' =>'',
		'clc'=> ''
    ), $atts));


    return view($cid, $w, $h, $t, $tt, $fc, $bgc, $brc, $ctt, $sb, $et, $ap, $cbs, $cpu, $cbc, $clc);
}

add_shortcode('venby', 'venby_shortcode');