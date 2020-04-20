<?php
function datalayer_add_woocommerce($order_id){
		//Order Details by order_id
	    $order = wc_get_order( $order_id );

	    //Get Order Data (total price)
	    $order_data = $order->get_data();
	    $total = $order_data['total'];

	    //Item Details
	    $items = $order->get_items();
	    $product_js = [];

	    foreach ( $items as $item_id => $item_data ) {
	    	// Item details One by one
	    	$item_name = $item_data["name"];
	    	$item_quantity = $item_data["quantity"];
	    	$item_total_price = $item_data['total'];
	    	$product_id = $item_data['product_id'];

	    	//Get product details with product id and price
	    	$product = wc_get_product( $product_id );
	    	$item_price = number_format($product->get_price(),2);

	    	//Json format prepare to push to datalayer
	    	$product_js[] = '{name: "' . $item_name . '",price: "' . $item_price . '",quantity: "' . $item_quantity . '"}';
	    }

	    //Datalayer Script
		$datalayer = "<script>
			window.dataLayer = window.dataLayer || [];
			dataLayer.push({
			   'transactionId': '".$order_id."',
			   'transactionTotal': ".$total.",
			   'transactionProducts': [".implode(',', $product_js)."]
			});
		</script>";

		//print datalyer at the footer of woocommerce thank you
		echo $datalayer;
		
}
//Woocommerce Thank You Hook and callback funtion
add_action('woocommerce_thankyou','datalayer_add_woocommerce');
