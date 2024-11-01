<?php

require_once('button.php');

function venby_options_panel()
{
    add_options_page(__('Settings') . ': ' . 'Venby', 'Venby', 'edit_themes', 'venby', 'start');
}

add_action('admin_menu', 'venby_options_panel');


if( empty( get_option( 'ops_apikey_venby' ) ) ) {
    add_action( 'admin_notices', 'venby_admin_notice' );
}
function venby_admin_notice() {
    ?>
    <div class="notice notice-warning is-dismissible go-to-settings" >
        <p><?php _e( 'Venby plugin requires activation. '); ?> <a href="<?=admin_url( 'options-general.php?page=venby' )?>">Settings</a></p>
    </div>


    <?php
}

function start()
{
    //api key validation
    if(isset($_POST['save-apikey'])){
        $venby_new_apikey = sanitize_text_field($_POST['apikey_venby']);
        $venby_validation_response = Venby_Editor_View::getCampaignsScripts($venby_new_apikey);


        if($venby_validation_response->type == 'success'){
            $venby_apikey = stripslashes(sanitize_text_field($_POST['apikey_venby']));
            update_option('ops_apikey_venby', $venby_apikey);
            update_option('ops_getcampaignsscripts_venby', json_encode($venby_validation_response));
            $venby_notice_class='notice-success';
            $venby_notice_text='API key was successfully updated. ';
        }else{
            $venby_notice_class='notice-error';
            $venby_notice_text='API key not valid. ';
        }
    }

    $venby_apikey =  get_option('ops_apikey_venby');

    ?>

    <div class="wrap">
        <?php if(isset($venby_notice_class) && isset($venby_notice_text)) { ?>
            <div class="notice <?=$venby_notice_class?>"><p><?=$venby_notice_text?></p></div>
        <?php } ?>
        <section>
        <div class="container">
          <div class="cta">
            <h1 id="oauth_content">
              <h1>Create your digital popup shop. Sell more. Faster.</h1>

              <div class="cta__desc">
                What is Venby? A new kind of simple eCommerce. Sell online with seamless, customizable pop ups.<br> We're eCommerce Anywhere.
              </div>

                <form method="post" action="<?php echo get_admin_url();?>options-general.php?page=venby&type=setting">
                    <table class="form-table">
                        <tr>
                            <th><label for="apikey"></label>API key</th>
                            <td>
                                <input id="apikey" name="apikey_venby" class="regular-text large-text" type="text" value="<?=$venby_apikey?>" required>
                            </td>
                        </tr>
                    </table>
                    <div>
                        <input name="save-apikey" type="submit" class="button button-primary button-large" value="Save API Key"/>
                    </div>
                </form>
                <hr>
            </div>
              <div class="cta__desc">
                <div>
                <br/>
                  Need a Venby.tv account? <a href="https://venby.tv/start/signup" target="_blank">Get started</a>.
                <br/>
                Version 1.7.1
                <br/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script>
            if('<?=$venby_notice_class?>' == 'notice-success'){
                document.querySelector('.go-to-settings').style.display = 'none';
            }
    </script>
<?php
}