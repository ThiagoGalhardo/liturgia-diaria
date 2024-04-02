<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Inertia\Inertia;
use App\Models\Liturgy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScraperController extends Controller
{
    public function index()
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $liturgy_today = Liturgy::where("day", "=", $today_date)->first();

        if (empty($liturgy_today)) {
            $liturgy_today = $this->scraper();
        }

        return Inertia::render('Liturgy', compact('liturgy_today'));
    }

    public function scraper()
    {
        $today_date = Carbon::now()->format('Y-m-d');
        $liturgy1 = $liturgy2 = $liturgypsalms = $liturgygospel = "";

        try {
            $client = new Client();
            $url = 'https://liturgia.cancaonova.com/pb/';
            $page = $client->request('GET', $url);

            if ($page->filter('#liturgia-1')->count() > 0) {
                $liturgy1 = $page->filter('#liturgia-1')->html();
                $liturgy1 = $this->removeEmbeddedAudio($liturgy1);
            }


            if ($page->filter('#liturgia-2')->count() > 0) {
                $liturgypsalms = $page->filter('#liturgia-2')->html();
                $liturgypsalms = $this->removeEmbeddedAudio($liturgypsalms);
            }

            if ($page->filter('#liturgia-3')->count() > 0) {
                $liturgy2  = $page->filter('#liturgia-3')->html();
                $liturgy2 = $this->removeEmbeddedAudio($liturgy2);
            }

            if ($page->filter('#liturgia-4')->count() > 0) {
                $liturgygospel = $page->filter('#liturgia-4')->html();
                $liturgygospel = $this->removeEmbeddedAudio($liturgygospel);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao fazer scraping: {$e->getMessage()}");
        }

        if (!Liturgy::where("day", "=", $today_date)->exists()) {
            $saveLiturgy = new Liturgy();
            $saveLiturgy->day = $today_date;
            $saveLiturgy->liturgy1 = $liturgy1;
            $saveLiturgy->liturgy2 = $liturgy2;
            $saveLiturgy->liturgypsalms = $liturgypsalms;
            $saveLiturgy->liturgygospel = $liturgygospel;
            $saveLiturgy->save();
        }

        return Liturgy::where("day", "=", $today_date)->first();
    }

    public function removeEmbeddedAudio($liturgy)
    {
        $pattern = '/<div class="embeds-audio">.*?<\/div>/s';
        return preg_replace($pattern, '', $liturgy);
    }
}
