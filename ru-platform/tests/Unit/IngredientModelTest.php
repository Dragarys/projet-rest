<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_sum_for_ingredient(): void
    {
        $ing = Ingredient::create(['name' => 'Tom', 'unit' => 'kg']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => 20, 'reason' => 'Init']);
        StockMovement::create(['ingredient_id' => $ing->id, 'delta_qty' => -5, 'reason' => 'Use']);

        $sum = StockMovement::where('ingredient_id', $ing->id)->sum('delta_qty');
        $this->assertEquals(15, $sum);
    }
}
