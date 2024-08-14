<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Inertia\Inertia;
use App\Models\Liturgy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $cacheKey = "liturgy_{$today}";

        // Use data from cache, if it exists
        $liturgy_today = Cache::remember($cacheKey, Carbon::now()->endOfDay(), function () use ($today) {
            $threeDaysLater = Carbon::now()->addDays(3);

            // Check if we have liturgies for the next 3 days
            $liturgiesCount = Liturgy::whereBetween('day', [$today, $threeDaysLater->format('Y-m-d')])->count();

            if ($liturgiesCount <= 3) {
                $this->scraper();
            }

            return Liturgy::where("day", $today)->first();
        });

        return Inertia::render('Liturgy', compact('liturgy_today'));
    }

    public function scraper()
    {
        $client = new Client();
        $url = 'https://liturgia.cancaonova.com/pb/';
        $page = $client->request('GET', $url);

        $calendarHtml = $page->filter('#wp-calendar')->html();


        $dom = new \DOMDocument();
        @$dom->loadHTML($calendarHtml);
        $xpath = new \DOMXPath($dom);

        $links = $xpath->query("//a[contains(@href, 'liturgia')]");

        foreach ($links as $link) {
            $href = $link->getAttribute('href');

            // Extract date from URL
            preg_match('/sDia=(\d+)&sMes=(\d+)&sAno=(\d+)/', $href, $matches);
            if (count($matches) == 4) {
                $date = "{$matches[3]}-{$matches[2]}-{$matches[1]}";

                // Check if we already have this date in our database
                if (!Liturgy::where('day', $date)->exists()) {
                    $liturgyData = $this->scrapeLiturgyPage($href);

                    // Ensure all required fields are present
                    $liturgyData['day'] = $date;
                    $liturgyData['liturgy1'] = $liturgyData['liturgy1'] ?? '';
                    $liturgyData['liturgypsalms'] = $liturgyData['liturgypsalms'] ?? '';
                    $liturgyData['liturgygospel'] = $liturgyData['liturgygospel'] ?? '';

                    try {
                        $liturgyDataBase = new Liturgy();
                        $liturgyDataBase->day = $date;
                        $liturgyDataBase->liturgy1 = $liturgyData['liturgy1'];
                        $liturgyDataBase->liturgy2 = $liturgyData['liturgy2'];
                        $liturgyDataBase->liturgypsalms = $liturgyData['liturgypsalms'];
                        $liturgyDataBase->liturgygospel = $liturgyData['liturgygospel'];
                        $liturgyDataBase->save();

                        Log::info("Created liturgy data for date: $date");
                    } catch (\Exception $e) {
                        Log::error("Failed to create liturgy for date $date: " . $e->getMessage());
                    }
                }
            }
        }
    }

    private function scrapeLiturgyPage($url)
    {
        $client = new Client();
        $page = $client->request('GET', $url);

        $liturgy1 = $page->filter('#liturgia-1')->count() > 0 ? $this->removeEmbeddedAudio($page->filter('#liturgia-1')->html()) : '';
        $liturgy2 = $page->filter('#liturgia-3')->count() > 0 ? $this->removeEmbeddedAudio($page->filter('#liturgia-3')->html()) : '';
        $liturgypsalms = $page->filter('#liturgia-2')->count() > 0 ? $this->removeEmbeddedAudio($page->filter('#liturgia-2')->html()) : '';
        $liturgygospel = $page->filter('#liturgia-4')->count() > 0 ? $this->removeEmbeddedAudio($page->filter('#liturgia-4')->html()) : '';

        return [
            'liturgy1' => $liturgy1,
            'liturgy2' => $liturgy2,
            'liturgypsalms' => $liturgypsalms,
            'liturgygospel' => $liturgygospel,
        ];
    }

    public function removeEmbeddedAudio($liturgy)
    {
        $pattern = '/<div class="embeds-audio">.*?<\/div>/s';
        return preg_replace($pattern, '', $liturgy);
    }
}
