<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       ShippingMethod::insert([
           [
               'name' => 'UPS',
               'price' => 1000
           ],
           [
               'name' => 'Royal Mail 1st Class',
               'price' => 2000
           ],
           [
               'name' => 'Royal Mail 2st Class',
               'price' => 3000
           ]
       ]);
    }
}
