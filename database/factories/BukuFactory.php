<?php

namespace Database\Factories;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idKategori' => Kategori::factory(),
            'judul' => ucwords(fake()->words(3, true)),
            'penulis' => fake()->name(),
            'penerbit' => fake()->company(),
            'tahunTerbit' => fake()->numberBetween(1990, 2026),
            'harga' => fake()->numberBetween(50, 200) * 1000,
            'stok' => 2,
            'kondisi' => 'Baik',
            'jumlahHalaman' => fake()->numberBetween(120, 600),
            'rak' => fake()->randomElement(['F', 'U', 'S']).'-'.str_pad((string) fake()->numberBetween(1, 20), 2, '0', STR_PAD_LEFT),
        ];
    }

    /**
     * Buku tanpa eksemplar tersedia.
     */
    public function habis(): static
    {
        return $this->state(fn (array $attributes): array => [
            'stok' => 0,
        ]);
    }
}
