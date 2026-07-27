<?php

namespace Tests\Feature;

use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class RsbsaLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_farmer_imported_from_an_rsbsa_dataset_can_log_in_without_a_password(): void
    {
        $import = new UsersImport();
        $import->collection(new Collection([
            new Collection([
                'rsbsa_reference_no' => '4 11 10 001 00045',
                'full_name' => 'Imported Farmer',
                'city_municipality' => 'La Trinidad',
            ]),
        ]));

        $farmer = User::where('name', 'Imported Farmer')->firstOrFail();

        $this->assertSame('4-11-10-001-00045', $farmer->rsbsa_number);
        $this->assertSame('La Trinidad', $farmer->location);

        $this->post('/login', [
            'email' => '4111000100045',
            'login_mode' => 'rsbsa',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($farmer);

        $daAdmin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($daAdmin)
            ->getJson('/admin/api/users')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $farmer->id,
                'rsbsa_number' => '4-11-10-001-00045',
            ]);
    }
}
