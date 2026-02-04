<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category(): void
    {
        Category::create(['name' => 'TestCat']);
        $this->assertDatabaseHas('categories', ['name' => 'TestCat']);
    }
}
