<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Goutte\Client;
use Inertia\Inertia;
use App\Models\Leitura;

class ScraperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $client = new Client();

        $url = 'https://liturgia.cancaonova.com/pb/';

        $page = $client->request('GET', $url);


        $leitura1 = $page->filter('#liturgia-1')->html();
        $salmos = $page->filter('#liturgia-2')->html();
        $leitura2 = "";
        $evangelho = $page->filter('#liturgia-4')->html();

        // dd($leitura1);

        $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
        $diasemana_numero = date('w', time());

        if ($diasemana[$diasemana_numero] == "Domingo") {
            $leitura2 = $page->filter('#liturgia-3')->html();
        }

        Leitura::storeData($leitura1, $leitura2, $salmos, $evangelho);

        return Command::SUCCESS;
    }
}
