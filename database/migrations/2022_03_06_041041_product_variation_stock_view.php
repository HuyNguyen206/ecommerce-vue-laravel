<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductVariationStockView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("CREATE VIEW product_variation_stock_view AS SELECT
product_variations.product_id AS product_id,
product_variations.id AS product_variation_id,
COALESCE ( stocks_custom.quantity, 0 ) AS quantity,
COALESCE ( product_variation_order.quantity, 0 ) AS quantity_orderd,
COALESCE ( stocks_custom.quantity, 0 ) - COALESCE ( product_variation_order.quantity, 0 ) AS quantity_left,
CASE WHEN COALESCE ( stocks_custom.quantity, 0 ) - COALESCE ( product_variation_order.quantity, 0 ) > 0 THEN
	TRUE ELSE FALSE
	END in_stock
FROM
	product_variations
	LEFT JOIN ( SELECT stocks.product_variation_id AS id, sum( stocks.quantity ) AS quantity FROM stocks GROUP BY stocks.product_variation_id ) AS stocks_custom USING ( id )
	LEFT JOIN ( SELECT product_variation_order.product_variation_id AS id, sum( product_variation_order.quantity ) AS quantity FROM product_variation_order GROUP BY product_variation_order.product_variation_id ) AS product_variation_order USING ( id );");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS product_variation_stock_view");
    }
}
