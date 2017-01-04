<?php

class woocommerce_order_items extends global_class {

	protected $order_item_id;
	protected $order_item_name;
	protected $order_item_type;
	protected $order_id;

	public static function identifiant() {
		return "order_item_id";
	}

}
