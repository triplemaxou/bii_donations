<?php

class woocommerce_order_itemmeta extends global_class {

	protected $meta_id;
	protected $order_item_id;
	protected $meta_key;
	protected $meta_value;

	public static function identifiant() {
		return "meta_id";
	}

}
