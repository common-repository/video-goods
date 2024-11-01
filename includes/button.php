<?php


class Venby_Editor_View
{
    public static function getCampaignsScripts($apikey)
    {

        $venby_api_attr =  array(
            "method" => "POST",
            "timeout"=> 30,
            "headers" => array(
                "API-KEY"=>$apikey,
                "cache-control"=>"no-cache"
            )
        );

        $response = wp_remote_request("https://venby.tv/api/getCampaignsScripts", $venby_api_attr);

        if ($response) {
            return json_decode($response['body']);
        } else {
            return false;
        }
    }

    public static function getTitles(){
        $select = json_decode(get_option('ops_getcampaignsscripts_venby'), true);
        $titles = $select['titles'];
        return $titles;
    }

    public static function getScript($id){
        $select = json_decode(get_option('ops_getcampaignsscripts_venby'), true);
        $scripts = $select['scripts'];
        return $scripts[$id];
    }


    public static function getInfo(){
      $venby_apikey =  get_option('ops_apikey_venby');
      return self::getCampaignsScripts($venby_apikey);
    }

    public static function add_hooks($screen)
    {
        if (isset($screen->base) && 'post' === $screen->base) {
            add_action('admin_notices', array(__CLASS__, 'handle_editor_view_js'));
            add_action('admin_head', array(__CLASS__, 'admin_head'));
            add_action('media_buttons', array(__CLASS__, 'admin_head'));
        }

    }

    public static function admin_head()
    {
        remove_action('media_buttons', 'venby_media_button', 999);
        add_action('media_buttons', array(__CLASS__, 'venby_media_button'), 999);
    }

    public static function venby_media_button()
    {
        $title = __('Add Video', 'vg');
        $logo = plugins_url('images/venbyID-icon_grey.svg', dirname(__FILE__));
        ?>
        <button type="button" id="insert-vg" class="button" title="<?php echo esc_attr($title); ?>">
            <img src="<?php echo $logo ?>" height="18">
            Add Campaign
        </button>

        <?php
    }

    public static function mce_external_plugins($plugin_array)
    {
        $plugin_array['venby_form'] = plugin_dir_url(__FILE__) . "../js/mce.js";
        return $plugin_array;
    }

    public static function mce_buttons($buttons)
    {
        $size = sizeof($buttons);
        $buttons1 = array_slice($buttons, 0, $size - 1);
        $buttons2 = array_slice($buttons, $size - 1);
        return array_merge(
            $buttons1,
            array('vg'),
            $buttons2
        );
    }

    public static function handle_editor_view_js()
    {
        add_filter('mce_external_plugins', array(__CLASS__, 'mce_external_plugins'));
        add_filter('mce_buttons', array(__CLASS__, 'mce_buttons'));

        wp_enqueue_script('vg-editor-view', plugins_url('js/editor-view.js', dirname(__FILE__)), array('wp-util', 'jquery', 'quicktags'), false, true);
        wp_enqueue_style('grunion-editor-ui', plugins_url('css/style.css', dirname(__FILE__)));


        if (!empty(get_option('ops_apikey_venby'))) {
            $titles = self::getTitles();
            $vg_logo = plugins_url('images/venbyID-icon.svg', dirname(__FILE__));
            ?>
            <?php if (count($titles)) { ?>
                <div class="vg_overlay" style="display: none;">
                    <div class="vg_overlay_content">
                        <div class="vg_title"><img src="<?php echo $vg_logo ?>" height="21" alt=""></div>
                            <div class="wrapper_vg_content">
                            <form name="vg-edit-form" id="vg-edit-form" action="#">
                                <div class="information_box">

                                    <div class="form-group">
                                        <div class="title_form_element">Insert a campaign</div>
                                        <div class="message"><p class="bg-danger"></p></div>
                                        <select name="cid">
                                            <option value="0">Select campaign</option>
                                            <?php foreach ($titles as $id => $name) { ?>
                                                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group pb-0">
                                        <select size="1" id="embed_type_select" name="t">
                                            <option class="embed_type_tytle" selected disabled>Select trigger action
                                            </option>
                                            <option class="embed-type" value="thumbnail">Image Trigger
                                            </option>
                                            <option class="embed-type" value="trigger">Button Trigger </option>
                                            <option class="embed-type" value="custom">Link Trigger</option>
                                        </select>
                                        <div class="learn_about_triger">
                                            <a href="//venby.tv/start/help/#triggers">LEARN ABOUT TRIGGER ACTIONS</a>
                                        </div>
                                    </div>
                                    <div class="dimensions share_row" data-show="thumbnail">
                                        <h5>Size Your Image</h5>
                                        <div class="unit">
                                            <label>width px </label>
                                            <input type="text" value="300" class="vg_size" name="w" id="width">
                                        </div>
                                        <div class="unit">
                                            <label>height px</label>
                                            <input type="text" value="200" class="vg_size" name="h" id="height">
                                        </div>
                                    </div>
                                    <div class="trigger_text share_row" data-show="custom">
                                        <h5>Custom link click trigger</h5>
                                        <input type="text" name="ctt" placeholder="Custom link">
                                    </div>

                                    <div class="trigger_text share_row row_left" data-show="trigger">
                                        <h5>Custom Button Font Color</h5>
                                        <input type="text" name="fc" value="#fff" class="color-field">
                                    </div>
                                    <div class="trigger_text share_row row_right" data-show="trigger">
                                        <h5>Custom Button Background Color</h5>
                                        <input type="text" name="bgc" value="#222" class="color-field">

                                        <select size="1" id="custom_button_size" name="cbs">
                                            <option class="embed_type_tytle" selected disabled>Select Custom Button Size
                                            </option>
                                            <option class="embed-type" value="114px|36px">Small Size</option>
                                            <option class="embed-type" value="130px|46px">Standard Size</option>
                                            <option class="embed-type" value="175px|53px">Large Size</option>
                                            <option class="embed-type" value="207px|61px">Super Size</option>
                                        </select>
                                    </div>
                                    <div class="trigger_text share_row" id="btn-border-color111" data-show="trigger">
                                        <h5>Custom Button Border Color</h5>
                                        <input type="text" name="brc" value="" class="color-field">
                                    </div>
                                    <div class="trigger_text share_row" data-show="trigger">
                                        <h5>Add Custom Button Text</h5>
                                        <input type="text" name="tt" id="custom_trigger_text" placeholder="Custom text">
                                    </div>
                                    <div class="share_row active">
                                        <div class="exit_text">
                                            <h5>Customize Exit Button Text</h5>
                                            <input type="text" name="et" placeholder="EXIT"
                                                   class="ng-pristine ng-valid ng-touched">
                                        </div>
                                    </div>
                                    <div class="trigger_text share_row active row_left" style="margin-bottom: 10px">

                                        <label class="on-off-mode">
                                            <input type="checkbox" name="ap"/>
                                            <span>Activate pop-up on page load</span>
                                        </label>
                                        <label class="on-off-mode mt-10">
                                            <input id="cpu-true" type="checkbox" name="cpu"/>
                                            <span>Activate shopping cart pop-up</span>
                                        </label>
                                        <label class="on-off-mode mt-10">
                                            <input id="sb-true" type="checkbox" name="sb"/>
                                            <span>Hide background image</span>
                                        </label>
                                    </div>
                                    <div class="trigger_text share_row active row_right" style="margin-bottom: 10px">
                                        <h5 style="margin-top: 0">Custom Cart Background Color</h5>
                                        <input type="text" name="cbc" value="#222" class="color-field">
                                        <h5 style="margin-top: 0">Custom Links Color</h5>
                                        <input type="text" name="clc" value="00a2ff" class="color-field">
                                    </div>
                                </div>
                                <button title="Submit" class="button submit-vg-edit-form" id="submit-form" type="button">
                                    Submit
                                </button>
                            </form>
                                <script>
                                    function true_cart_popup() {

                                        if(jQuery('#cpu-true').prop('checked')){
                                            jQuery('#sb-true').prop('checked', 'checked');
                                            jQuery('#sb-true').prop('disabled', 'disabled');
                                        }


                                        jQuery('#cpu-true').on('change', function () {
                                            if(jQuery('#cpu-true').prop('checked')){
                                                jQuery('#sb-true').prop('checked', 'checked');
                                                jQuery('#sb-true').prop('disabled', 'disabled');
                                                console.log( jQuery('#sb-true').val() );
                                            }else{
                                                jQuery('#sb-true').prop('checked', false);
                                                jQuery('#sb-true').val('false');
                                                jQuery('#sb-true').prop('disabled', false);
                                                console.log(22222);
                                            }
                                        });
                                    }
                                    true_cart_popup();
                                </script>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
        <?php
    }

}

add_action( 'admin_enqueue_scripts', 'venby_backend_scripts');
if ( ! function_exists( 'venby_backend_scripts' ) ){
    function venby_backend_scripts($hook) {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker');
        wp_enqueue_script( 'wp-color-picker');
    }
}

add_action('current_screen', array('Venby_Editor_View', 'add_hooks'));