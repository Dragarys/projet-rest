<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMovementModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_movement_creation_and_sum(): void
    {
        $ing = Ingredient::create(['name' => 'Rice', 'unit' => 'kg']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => 5, 'reason' => 'Add']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => -2, 'reason' => 'Used']);

        $this->assertDatabaseHas('stock_movements', ['ingredient_id' => $ing->id, 'delta_qty' => 5]);
        $this->assertDatabaseHas('stock_movements', ['ingredient_id' => $ing->id, 'delta_qty' => -2]);
    }
}
