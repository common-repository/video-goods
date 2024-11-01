<?php
/**
 *
 *  Element Description: VC Venby.tv Box
 *
 */
class VenbyTv extends WPBakeryShortCode {
    public $id_company;
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_venby_mapping' ) );
        add_shortcode( 'vc_venby', array( $this, 'vc_venby_html' ) );
    }
    public static function getTitles(){
        $select = json_decode(get_option('ops_getcampaignsscripts_venby'), true);
        $titles = $select['titles'];
        $titles_no_key = [];
        foreach($titles as $value){
          $titles_no_key[$value] = $value;
        }
        return $titles_no_key;
    }
    public static function getScriptId(){
        $select = json_decode(get_option('ops_getcampaignsscripts_venby'), true);
        $current_id_key_array= key($select['titles']);
        return $current_id_key_array;
    }
    // Element Mapping
    public function vc_venby_mapping() {
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }

        // Map the block with vc_map()
        vc_map(
            array(
                'name' => __('Venby', 'text-domain'),
                'base' => 'vc_venby',
                'category' => __('Venby', 'text-domain'),
                'icon' => plugins_url('images/venbyID-icon.svg', dirname(__FILE__)),
                'admin_enqueue_js' => plugins_url( 'js/vc-remove-scripts.js', dirname(__FILE__) ) ,
                'params' => array(
                    // #General settings
                    // title company
                    array(
                        'type' => 'dropdown',
                        'holder' => 'div',
                        'class' => 'form-group',
                        'heading' => __( 'Insert a campaign', 'js_composer' ),
                        'param_name' => 'cid',
                        'value' => $this->getTitles(),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'General settings',
                    ),
                    //Select trigger action
                    array(
                        'type' => 'dropdown',
                        'holder' => 'div',
                        'class' => 'form-group pb-0',
                        'heading' => __( 'Select trigger action', 'js_composer' ),
                        'param_name' => 't',
                        'value' => array(
							__( 'Select trigger action',  "text-domain"  ) => '',
                            __( 'Image Trigger',  "text-domain"  ) => 'thumbnail',
                            __( 'Button Trigger ',  "text-domain"  ) => 'trigger',
                            __( 'Link Trigger',  "text-domain"  ) => 'custom',
                        ),
                        //'description' => __( 'Box Text', 'text-domain' ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'General settings',

                    ),
                    // Exit Button Text
                    array(
                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'exit_text',
                        'heading' => __( 'Customize Exit Button Text', 'js_composer' ),
                        'param_name' => 'et',
                        'value' => __( "EXIT", "js_composer" ),
                        'admin_label' => false,
                        'weight' => 0,
                        'group' => 'General settings',
                        'save_always' => true,
                    ),
                    // Activate pop-up on page load
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Activate pop-up on page load', 'js_composer' ),
                        'param_name' => 'ap',
                        'group' => 'General settings',
                        'edit_field_class' => 'vc_col-sm-6',
                    ),
                    // Activate shopping cart pop-up
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Activate shopping cart pop-up', 'js_composer' ),
                        'param_name' => 'cpu',
                        'group' => 'General settings',
                        'edit_field_class' => 'vc_col-sm-6',
                        'dependency' => array(
                            'callback' => 'true_cart_popup',
                        ),
                    ),
					// Custom Cart Background Color
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Custom Cart Background Color', 'js_composer' ),
						'param_name' => 'cbc',
						'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
						'value' => '#262E2F',
						'dependency' => array(
							'element' => 'cpu',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_col-sm-6 colorpicker-field-el-bloc',
					),
					// Custom Links Color
					array(
						'type' => 'colorpicker',
						'heading' => __( 'Custom Links Color', 'js_composer' ),
						'param_name' => 'clc',
						'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
						'value' => '#00a2ff',
						'dependency' => array(
							'element' => 'cpu',
							'not_empty' => true
						),
						'edit_field_class' => 'vc_col-sm-6 colorpicker-field-el-bloc',
					),
                    // Hide background image
                    array(
                        'type' => 'checkbox',
                        'heading' => __( 'Hide background image', 'js_composer' ),
                        'param_name' => 'sb',
                        'group' => 'General settings',
                        'edit_field_class' => 'vc_col-sm-6',

                    ),
                    // hide element ID
                    array(
                        'type' => 'el_id',
                        'param_name' => 'el_id',
                        'value' => $this->getScriptId(),
                        'group' => 'General settings',
                        'edit_field_class' => 'vc_col-sm-1',
                        'save_always' => true,
                    ),

                    // #Custom link click trigger
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Custom link click trigger', 'js_composer' ),
                        'param_name' => 'ctt',
                        'value' => __( "Custom link", "js_composer" ),
                        'group' => 'Link Trigger',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'custom' ),

                        ),
                        'save_always' => true,
                    ),
                    // #Image triger
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Width px', 'js_composer' ),
                        'param_name' => 'w',
                        'class' => 'qwqww',
                        'value' => 300,
                        'group' => 'Image triger',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'thumbnail' ),
                            'callback' => 'proportion_value'
                        ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Height px', 'js_composer' ),
                        'param_name' => 'h',
                        'value' => 200,
                        'group' => 'Image triger',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'thumbnail' ),
                        ),
                    ),
                    // #Button Trigger
                    // Custom Button Font Color
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Custom Button Font Color', 'js_composer' ),
                        'param_name' => 'fc',
                        'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                        'value' => '#fffff',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'trigger' ),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                        'group' => 'Button Trigger',
                    ),
                    // Custom Button Background Color
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Custom Button Background Color', 'js_composer' ),
                        'param_name' => 'bgc',
                        'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                        'value' => '#00000',

                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'trigger' ),
                            'callback' => 'remove_bc'
                        ),
                        'edit_field_class' => 'vc_col-sm-6 colorpicker-field-el-bloc',

                        'group' => 'Button Trigger',
                    ),
                    // Custom Button Border Color
                    array(
                        'type' => 'colorpicker',
                        'heading' => __( 'Custom Button Border Color', 'js_composer' ),
                        'param_name' => 'brc',
                        'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                        'value' => '#00000',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'trigger' ),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                        'group' => 'Button Trigger',
                    ),
                    // Select Custom Button Size
                    array(
                        'type' => 'dropdown',
                        'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
                        'heading' => __( 'Select Custom Button Size', 'js_composer' ),
                        'param_name' => 'cbs',
                        'value' => array(
                            __( 'Small Size',  "text-domain"  ) => '114px|36px',
                            __( 'Standard Size ',  "text-domain"  ) => '130px|46px',
                            __( 'Large Size',  "text-domain"  ) => '175px|53px',
                            __( 'Super Size',  "text-domain"  ) => '207px|61px',
                        ),
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'trigger' ),
                        ),
                        'edit_field_class' => 'vc_col-sm-6',
                        'group' => 'Button Trigger',

                    ),
                    // Add Custom Button Text
                    array(
                        'type' => 'textfield',
                        'heading' => __( 'Add Custom Button Text', 'js_composer' ),
                        'param_name' => 'tt',
                        'value' => 'Custom text',
                        'dependency' => array(
                            'element' => 't',
                            'value' => array( 'trigger' ),
                        ),
                        'edit_field_class' => 'vc_col-sm-12',
                        'group' => 'Button Trigger',
                        'save_always' => true,
                    ),
                ),
                'save_always' => true,
            )
        );

    }

    // Element HTML
    function view($campaign_id, $campain_title, $width, $height, $type, $trigger_text, $font_color, $bg_color, $btn_border_color, $custom_trigger_text, $show_background, $exit_text, $auto_popup, $custom_button_size, $cart_popup, $background_cart, $links_color)
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
		
        if ($show_background == 'true') {
            $show_background = 'false';
        } else {
            $show_background = "";
        }
        if(empty($auto_popup)){
            $auto_popup = 'false';
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
                        autoopen:"'.$auto_popup.'",
                        height:"'.$height.'",
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
                        venbyConfig.show_background = true;
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

    public function vc_venby_html( $atts ) {
        // Params extraction
        extract(
            shortcode_atts(
                array(
                    'el_id' => '',
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
                ),
                $atts
            )
        );
        return $this->view($el_id, $cid, $w, $h, $t, $tt, $fc, $bgc, $brc, $ctt, $sb, $et, $ap, $cbs, $cpu, $cbc, $clc);
    }

} // End Element Class
// Element Class Init
new VenbyTv();
