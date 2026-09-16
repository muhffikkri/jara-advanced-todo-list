<?php

namespace Tests\Feature;

use App\Http\Requests\StoreListRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreListRequestTest extends TestCase
{
    use RefreshDatabase;

    private function makeRequest(array $data, ?User $user): StoreListRequest
    {
        $request = StoreListRequest::create('/lists', 'POST', $data);
        $request->setContainer($this->app);
        $request->setRedirector($this->app['redirect']);
        $request->setUserResolver(fn () => $user);

        return $request;
    }

    public function test_unauthenticated_user_is_not_authorized(): void
    {
        $this->assertFalse($this->makeRequest([], null)->authorize());
    }

    public function test_authenticated_user_is_authorized(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($this->makeRequest([], $user)->authorize());
    }

    public function test_name_is_required(): void
    {
        $user = User::factory()->create();

        try {
            $this->makeRequest(['name' => '', 'description' => 'Deskripsi'], $user)->validateResolved();
            $this->fail('Validasi seharusnya gagal ketika name kosong.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
        }
    }

    public function test_valid_payload_passes_validation(): void
    {
        $user = User::factory()->create();

        $request = $this->makeRequest([
            'name' => 'Tugas Kuliah',
            'description' => 'Deskripsi daftar',
        ], $user);
        $request->validateResolved();

        $validated = $request->validated();
        $this->assertSame('Tugas Kuliah', $validated['name']);
        $this->assertSame('Deskripsi daftar', $validated['description']);
    }
}
