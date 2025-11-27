<?php

namespace App\Console\Commands;

use App\EventPrice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EventSalePlaceParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event_price:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse event places for sale';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$events = [
    		1 => 'OTAwfDk2ODAwfDE1Njg4M3wxNTM3MDIxODAwMDAw'
	    ];

    	foreach($events as $event_id => $yandex_id) {
		    $json = file_get_contents('https://widget.tickets.yandex.ru/api/tickets/v1/sessions/' . $yandex_id . '/hallplan/async?clientKey=bb40c7f4-11ee-4f00-9804-18ee56565c87');
		    $array = json_decode($json, true);

		    $event_prices = [];

		    $places = [];
		    $results = DB::table('scheme_place')
		                 ->select('scheme_place.id as id', 'scheme_place.sector_id as sector_id', 'scheme_place.svg_row as svg_row', 'scheme_place.svg_place as svg_place')
		                 ->leftJoin('scheme_sector', 'scheme_sector.id', '=', 'scheme_place.sector_id')
		                 ->leftJoin('scheme', 'scheme.id', '=', 'scheme_sector.scheme_id')
		                 ->leftJoin('event', 'event.scheme_id', '=', 'scheme.id')
		                 ->where('event.status', 1)
		                 ->where('scheme.status', 1)
		                 ->where('scheme_sector.status', 1)
		                 ->where('scheme_place.status', 1)
		                 ->where('event.id', $event_id)
		                 ->get();

		    foreach ($results as $result) {
			    $places[$result->sector_id . '_' . $result->svg_row . '_' . $result->svg_place] = $result->id;
		    }

		    echo '[' . date('Y-m-d H:i:s') . "] OK\tEvent # " . $event_id;

		    if (isset($array['result']['hallplan'])) {
			    foreach ($array['result']['hallplan']['levels'] as $category) {
				    $sector = DB::table('scheme_sector')->select('id')->where('origin_id', $category['id'])->where('status', 1)->first();
				    if ($sector) {
					    foreach ($category['seats'] as $seat) {
						    $svg_row = isset($seat['seat']['row']) ? $seat['seat']['row'] : '1';
						    $svg_place = isset($seat['seat']['place']) ? $seat['seat']['place'] : '1';

						    if (isset($places[$sector->id . '_' . $svg_row . '_' . $svg_place])) {
							    $price = isset($seat['priceInfo']['price']['value']) ? intval($seat['priceInfo']['price']['value']/100) : 0;

							    $coef = 1;
							    if (5000 > $price && $price <= 10000) $coef = 1.7;
							    if (10000 > $price && $price <= 50000) $coef = 1.5;
							    if (50000 > $price) $coef = 1.3;

							    $event_prices[] = [
								    'event_id' => $event_id,
								    'place_id' => $places[$sector->id . '_' . $svg_row . '_' . $svg_place],
								    'price' => $price * $coef,
								    'status' => 1,
								    'source' => 'yandex'
							    ];
						    }
					    }
				    }
			    }
			    EventPrice::withTrashed()->where('event_id', $event_id)->where('source', 'yandex')->forceDelete();
			    DB::table('event_price')->insert($event_prices);

			    echo "\tCount: " . count($event_prices);
		    } else {
			    echo "\tEmpty";
		    }
		    echo "\n";
	    }
    }
}
