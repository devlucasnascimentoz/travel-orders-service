<?php

namespace Tests\Feature;

use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_creates_a_travel_order()
    {
        $data = [
            'requester_name' => 'John Doe',
            'destination' => 'Paris',
            'start_date' => now()->addDays(5)->format('Y-m-d'),
            'end_date' => now()->addDays(10)->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/travel-orders', $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'requester_name' => 'John Doe',
                    'destination' => 'Paris',
                ]
            ]);

        $this->assertDatabaseHas('travel_orders', $data);
    }

    /** @test */
    public function it_lists_only_authenticated_user_orders()
    {
        TravelOrder::factory()->count(2)->create(['user_id' => $this->user->id]);
        TravelOrder::factory()->create(); // Pedido de outro usuário

        $response = $this->getJson('/api/travel-orders');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data'); // Só os pedidos do usuário autenticado
    }

    /** @test */
    public function it_filters_travel_orders_by_status()
    {
        TravelOrder::factory()->create(['user_id' => $this->user->id, 'status' => 'aprovado']);
        TravelOrder::factory()->create(['user_id' => $this->user->id, 'status' => 'solicitado']);

        $response = $this->getJson('/api/travel-orders?status=aprovado');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_filters_travel_orders_by_destination()
    {
        TravelOrder::factory()->create(['user_id' => $this->user->id, 'destination' => 'Paris']);
        TravelOrder::factory()->create(['user_id' => $this->user->id, 'destination' => 'London']);

        $response = $this->getJson('/api/travel-orders?destination=Paris');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_shows_a_travel_order()
    {
        $order = TravelOrder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/travel-orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'destination' => $order->destination,
                ]
            ]);
    }

    /** @test */
    public function it_denies_status_update_by_requester()
    {
        $order = TravelOrder::factory()->create(['user_id' => $this->user->id]);

        $response = $this->patchJson("/api/travel-orders/{$order->id}/status", [
            'status' => 'aprovado'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_allows_status_update_by_other_users()
    {
        $order = TravelOrder::factory()->create();
        $admin = User::factory()->create();

        $this->actingAs($admin);
        $response = $this->patchJson("/api/travel-orders/{$order->id}/status", [
            'status' => 'aprovado'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => 'aprovado'
                ]
            ]);
    }

    /** @test */
    public function it_cancels_an_approved_order_if_allowed()
    {
        $order = TravelOrder::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'aprovado',
            'start_date' => now()->addDays(4)
        ]);

        $response = $this->postJson("/api/travel-orders/{$order->id}/cancel");

        $response->assertStatus(204);
        $this->assertDatabaseHas('travel_orders', ['id' => $order->id, 'status' => 'cancelado']);
    }

    /** @test */
    public function it_prevents_cancelling_non_approved_orders()
    {
        $order = TravelOrder::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'solicitado'
        ]);

        $response = $this->postJson("/api/travel-orders/{$order->id}/cancel");

        $response->assertStatus(400);
    }

    /** @test */
    public function it_prevents_cancelling_approved_orders_outside_allowed_period()
    {
        $order = TravelOrder::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'aprovado',
            'start_date' => now()->addDays(2) // Menos de 3 dias para a viagem
        ]);

        $response = $this->postJson("/api/travel-orders/{$order->id}/cancel");

        $response->assertStatus(400);
    }
}
