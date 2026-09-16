<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hizb;
use App\Models\Juz;
use App\Models\Sourate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class QuranDataSeeder extends Seeder
{
    use WithoutModelEvents;

    private const API_BASE = 'https://api.quran.com/api/v4';

    /**
     * Seed the Quran structure (114 sourates, 30 juzs, 60 hizbs)
     * from the Quran.com API (French metadata).
     */
    public function run(): void
    {
        $this->seedSourates();
        $this->seedJuzs();
        $this->seedHizbs();
    }

    private function seedSourates(): void
    {
        $data = $this->get('/chapters', ['language' => 'fr']);

        foreach ($data['chapters'] as $chapter) {
            Sourate::updateOrCreate(
                ['number' => $chapter['id']],
                [
                    'name_arabic' => $chapter['name_arabic'],
                    'name_complex' => $chapter['name_complex'],
                    'name_simple' => $chapter['name_simple'],
                    'name_french' => $chapter['translated_name']['name'],
                    'verses_count' => $chapter['verses_count'],
                    'revelation_place' => $chapter['revelation_place'],
                    'revelation_order' => $chapter['revelation_order'],
                    'bismillah_pre' => (bool) $chapter['bismillah_pre'],
                ],
            );
        }

        $this->command?->info('Sourates: ' . Sourate::count() . ' importees.');
    }

    private function seedJuzs(): void
    {
        $data = $this->get('/juzs', ['language' => 'fr']);

        $seen = [];
        foreach ($data['juzs'] as $juz) {
            if (isset($seen[$juz['juz_number']])) {
                continue;
            }
            $seen[$juz['juz_number']] = true;

            Juz::updateOrCreate(
                ['number' => $juz['juz_number']],
                [
                    'first_verse_id' => $juz['first_verse_id'],
                    'last_verse_id' => $juz['last_verse_id'],
                    'verses_count' => $juz['verses_count'],
                    'verse_mapping' => $juz['verse_mapping'],
                ],
            );
        }

        $this->command?->info('Juzs: ' . Juz::count() . ' importes.');
    }

    private function seedHizbs(): void
    {
        $juzRange = [];
        $hizbGroup = [];

        for ($juzNumber = 1; $juzNumber <= 30; $juzNumber++) {
            $page = 1;
            do {
                $data = $this->get(
                    "/verses/by_juz/{$juzNumber}",
                    ['per_page' => 700, 'page' => $page],
                );

                foreach ($data['verses'] as $verse) {
                    $juzRange[$juzNumber]['keys'][] = $verse['verse_key'];
                    $hizbGroup[$verse['hizb_number']]['entries'][] = $verse;
                }

                $pages = $data['pagination']['total_pages'] ?? 1;
                $page++;
            } while ($page <= $pages);
        }

        foreach ($juzRange as $juzNumber => $range) {
            Juz::where('number', $juzNumber)->update([
                'first_verse_key' => $range['keys'][0],
                'last_verse_key' => end($range['keys']),
            ]);
        }

        foreach ($hizbGroup as $number => $group) {
            $entries = $group['entries'];
            $first = $entries[0];
            $last = end($entries);

            Hizb::updateOrCreate(
                ['number' => $number],
                [
                    'juz_id' => Juz::where('number', (int) ceil($number / 2))->value('id'),
                    'first_verse_id' => $first['id'],
                    'last_verse_id' => $last['id'],
                    'first_verse_key' => $first['verse_key'],
                    'last_verse_key' => $last['verse_key'],
                    'verses_count' => count($entries),
                ],
            );
        }

        $this->command?->info('Hizbs: ' . Hizb::count() . ' importes.');
    }

    private function get(string $path, array $query = []): array
    {
        try {
            $response = Http::retry(3, 500)->timeout(60)->get(self::API_BASE . $path, $query);

            $response->throw();
        } catch (RequestException $e) {
            throw new \RuntimeException(
                "Quran.com API request echoue ({$path}) : {$e->getMessage()}",
                previous: $e,
            );
        }

        return $response->json();
    }
}