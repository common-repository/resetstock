<?php

/*
Plugin Name:  Resetstock
Description:  Gives Admins only the opportunity to reset to zero simple product stock in front-end Woocommerce shop page.
Version:      1.0
License:      GPL v2 or later
Author:       Mehdi TEMANI
*/

function product_zero_stock_admin_button(){
    global $product;
  
    if( wc_current_user_has_role('administrator') ) :

        $id = $product->get_id();
        $sku = $product->get_sku();
        $sku = (!empty($sku)) ? $sku : 'ND';
        echo '<form method="POST" action="">
                    <button type="submit" class="reset-stock" style="position:relative;" name="reset-stock-'.esc_html($id).'">Set Stock To 0 for id: '.esc_html($id).' and Sku: '.esc_html($sku).'</button>';
                    wp_nonce_field('', 'my_nounce');
        echo  '</form>';
        
        if( isset($_POST['reset-stock-'.$id]) && isset($_REQUEST['my_nounce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['my_nounce'])), '')){
            $product->set_stock_quantity(0); // Reset stock quantity
            $product->set_stock_status('outofstock'); // Set stock status "Out of stock"
            $product->save(); // Sync and save data
        }
    endif;
}

add_action( 'woocommerce_shop_loop_item_title', 'product_zero_stock_admin_button' );






