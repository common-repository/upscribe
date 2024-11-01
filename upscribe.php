<?php
/*
Plugin Name: Upscribe
Plugin URI: https://upscri.be
Description: Embeddable newsletter signup forms
Version: 0.7
Author: Upscribe
Author URI: https://upscri.be
*/

/**
 * Enable embedding
 */
function uppl_enable_embed() {
  wp_oembed_add_provider('https://upscri.be/*', 'https://upscri.be/oembed');
}
add_action('init', 'uppl_enable_embed');

/**
 * Add snippet JS
 */
add_action('wp_head', function () {
  ?>
  <script>window.upsettings = {'api_key': '<?php echo get_option("upscribe_api_key") ?>'}</script>
  <script>(function(){var w=window;var up=w.Upscribe;if(typeof up==="function"){up('reattach_activator');up('update',upsettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Upscribe=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://upscri.be/js/snippet.js';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if( w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>
  <?php
}, 100);

/**
 * Register options
 */
add_action('admin_init', function() {
  add_option('upscribe_api_key', false);
  register_setting('upscribe_options_group', 'upscribe_api_key');
});

/**
 * Save API key
 */
add_action('add_option', function($option_name, $option_value) {
  if ($option_name == 'upscribe_api_key') :
    update_option($option_name, $option_value);
  endif;
}, 10, 2);

/**
 * Add settings page for saving upscribe_api_key
 */
add_action('admin_menu', function() {
  add_menu_page(
    __("Upscribe",'uppl'),
    __("Upscribe",'uppl'),
    'read',
    'upscribe',
    function(){
      $api_key = get_option('upscribe_api_key');

      // Allow auto-set (maybe add later)
      if (!empty($_REQUEST['upscribe_api_key'])) :
        $api_key = $_REQUEST['upscribe_api_key'];
        update_option('upscribe_api_key', $api_key);
      endif;
    ?>
      <div>
        <?php screen_icon(); ?>
        <br/>
        <br/>
        <br/>
        <h1>Upscribe API Key</h1>
        <form method="post" action="options.php">

          <?php settings_fields( 'upscribe_options_group' ); ?>

          <p>You can grab your API key on your Upscribe <a href="https://app.upscri.be/account/integrations?wp-plugin-retrieve-key=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>" target="_blank">account page</a>.</p>

          <table>
            <tr valign="middle">
              <th scope="row"><label for="upscribe_api_key">API Key</label></th>
              <td><input type="text" id="upscribe_api_key" name="upscribe_api_key" value="<?php echo $api_key ?>" /></td>
            </tr>
          </table>

          <?php submit_button() ?>

        </form>
      </div>
    <?php
    },
    'dashicons-email',
    '90'
  );
}, 100);
