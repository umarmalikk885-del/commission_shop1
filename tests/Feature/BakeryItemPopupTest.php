<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BakeryItemPopupTest extends TestCase
{
    public function test_items_table_row_uses_stock_select_click_handler(): void
    {
        $response = $this->get('/bakery');
        $response->assertStatus(200);
        $response->assertSee('onclick="openStockSelect(this)"', false);
    }

    public function test_item_type_input_passes_event_to_item_type_modal(): void
    {
        $response = $this->get('/bakery');
        $response->assertStatus(200);
        $response->assertSee('onclick="openItemTypeModal(this, 0, event)"', false);
    }

    public function test_bakery_view_contains_both_item_and_stock_modals(): void
    {
        $response = $this->get('/bakery');
        $response->assertStatus(200);
        $response->assertSee('id="itemTypeModal"', false);
        $response->assertSee('id="stockSelectModal"', false);
    }
}

