<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Supplier::factory(5)->create();
        PurchaseOrder::factory(10)->create();
    }
}