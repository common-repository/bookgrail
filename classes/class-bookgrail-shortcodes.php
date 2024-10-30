<?php
/**
 * BG_Shortcodes class.
 *
 * @class 		BG_Shortcodes
 * @version		1.0.0
 * @package		Bookgrail/Classes
 * @category            Class
 * @author 		Bookgrail Ltd
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BG_Shortcodes {

	public function __construct() {
            add_shortcode( 'bg_buy_button', array( $this, 'buy_button' ) );
            add_shortcode( 'bg_price', array( $this, 'price' ) );
	}

	/**
	 * bg_price shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public function price( $atts ) {

            wp_enqueue_script('bookgrail-init');

            extract(shortcode_atts(array(
                    'isbn' => false,
                    'container' => 'span'
            ), $atts));                        
         
            if ( !empty($isbn) ) :                
                return "<$container data-price='$isbn'></$container>";
            endif;
            
        }
        
	/**
	 * bg_buy_button shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public function buy_button( $atts ) {
            
            global $bookgrail;
            
            wp_enqueue_script('bookgrail-init');
            
            extract(shortcode_atts(array(
                    'isbn' => false,
                    'button_text' => 'Add to basket',
                    'container' => 'button',
                    'classes' => '',
                    'before' => '',
                    'after' => ''
            ), $atts));                        
            
            if ( !empty($isbn) ) :
                
                $theme_color = $bookgrail->options["theme_color"];
            
                ob_start();
            
                echo $before;
                ?>
                <<?php echo $container; ?> data-cart-open='#bg-cart-<?php echo $isbn; ?>' data-theme-color='<?php echo $theme_color; ?>' data-cart-add='<?php echo $isbn; ?>' class='<?php echo $classes; ?>'><?php echo $button_text; ?></<?php echo $container; ?>>
                
                <div id="bg-cart-<?php echo $isbn; ?>" style="display:none; z-index: 1; position:fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 2px solid <?php echo $theme_color; ?>" >
                    <div style="position: absolute; right: 5px; top:5px;"><button data-cart-close="#bg-cart-<?php echo $isbn; ?>" title="close"><strong>X</strong></button></div>                                
                </div>
                <?php                
                echo $after;
                
                return ob_get_clean();
                
            endif;
	}


}

?>