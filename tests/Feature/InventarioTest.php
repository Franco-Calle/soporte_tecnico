<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CatalogoItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class InventarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_alerta_stock_minimo_lista_items_con_stock_critico(): void
    {
        $admin = User::factory()->admin()->create();

        CatalogoItem::factory()->bien()->create(['nombre' => 'Pantalla escasa', 'stock' => 1, 'stock_minimo' => 1]);
        CatalogoItem::factory()->bien()->create(['nombre' => 'RAM cero', 'stock' => 0, 'stock_minimo' => 1]);
        CatalogoItem::factory()->bien()->create(['nombre' => 'Cargador abundante', 'stock' => 20, 'stock_minimo' => 2]);

        $response = $this->actingAs($admin)->get('/inventario');

        $response->assertOk();
        $response->assertSee('Pantalla escasa');
        $response->assertSee('RAM cero');
        $response->assertSee('Alerta de stock minimo');
    }

    public function test_entrada_incrementa_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $item = CatalogoItem::factory()->bien()->create(['stock' => 3]);

        $this->actingAs($admin)->post('/inventario', [
            'catalogo_item_id' => $item->id,
            'tipo' => 'entrada',
            'cantidad' => 5,
            'motivo' => 'Compra',
        ])->assertRedirect();

        $item->refresh();
        $this->assertSame(8, $item->stock);
    }
}
