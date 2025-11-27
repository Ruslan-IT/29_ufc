<?php

namespace App\Http\Controllers\Admin;

use App\Location;
use App\Scheme;
use App\Http\Requests\RequestScheme;
use App\Http\Controllers\Controller;
use App\SchemePlace;
use App\SchemeSector;
use Intervention\Image\Facades\Image;

class SchemeController extends Controller
{
    public function index()
    {
	    $data = [];
	    $data['page_title'] = 'Список схем площадок';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Схемы площадок',
			    'link' => false
		    ]
	    ];

	    $data['schemes'] = Scheme::all();

	    return view('admin.scheme.index', $data);
    }

    public function create()
    {
    	$data = [];
    	$data['page_title'] = 'Добавить схему площадки';

    	$data['breadcrumbs'] = [
    		0 => [
    		    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Схемы площадок',
			    'link' => url('admin/schemes')
		    ],
		    2 => [
			    'title' => 'Создать',
			    'link' => false
		    ]
	    ];

	    $data['scheme_image'] = '';
	    $data['locations'] = [];
	    foreach (Location::where('status', '1')->get() as $location) {
		    $data['locations'][$location->id] = $location->name;
	    }
	    $data['cancel_link'] = url('admin/schemes');

        return view('admin.scheme.create', $data);
    }

    public function store(RequestScheme $request)
    {
	    $scheme = new Scheme();
	    $scheme->name = $request->input('name');
	    $scheme->status = $request->input('status') ? $request->input('status') : 0;
	    $scheme->location_id = $request->input('location_id');
	    $scheme->image_width = (int) $request->input('image_width');
	    $scheme->image_height = (int) $request->input('image_height');
	    if ($request->file('scheme_image')) {
		    $image = Image::make($request->file('scheme_image'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('scheme_image')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $scheme->scheme_image = $filename;
	    }
	    $scheme->save();

	    return redirect('admin/schemes')->with('message_success', 'Успешно додано!');
    }

    public function show(Scheme $scheme)
    {
	    $data = [];
	    $data['page_title'] = $scheme->name;

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Схемы площадок',
			    'link' => url('admin/schemes')
		    ],
		    2 => [
			    'title' => 'Инфо',
			    'link' => false
		    ]
	    ];

	    $sectors = SchemeSector::where('scheme_id', $scheme->id)->where('status', 1)->get();
	    $data['sector_count'] = count($sectors);
	    $data['place_count'] = 0;
	    foreach ($sectors as $sector) {
	    	$place = SchemePlace::where('sector_id', $sector->id)->where('status', 1)->get();
		    $data['place_count'] += count($place);
	    }

	    $data['scheme'] = $scheme;

	    $data['parse_link'] = url('admin/schemes/' . $scheme->id . '/parse_info');

	    return view('admin.scheme.show', $data);
    }

    public function edit(Scheme $scheme)
    {
	    $data = [];
	    $data['page_title'] = 'Изменить схему площадки';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Схемы площадок',
			    'link' => url('admin/schemes')
		    ],
		    2 => [
			    'title' => 'Изменить',
			    'link' => false
		    ]
	    ];

	    $data['scheme'] = $scheme;
	    $data['locations'] = [];
	    foreach (Location::where('status', '1')->get() as $location) {
		    $data['locations'][$location->id] = $location->name;
	    }
	    $data['scheme_image'] = $scheme->scheme_image ? url('img/cache/scheme_mini/' . $scheme->scheme_image) : '';
	    $data['cancel_link'] = url('admin/schemes');

	    return view('admin.scheme.edit', $data);
    }

    public function update(RequestScheme $request, Scheme $scheme)
    {
	    $scheme->name = $request->input('name');
	    $scheme->status = $request->input('status') ? $request->input('status') : 0;
	    $scheme->location_id = $request->input('location_id');
	    $scheme->image_width = (int) $request->input('image_width');
	    $scheme->image_height = (int) $request->input('image_height');
	    if ($request->file('scheme_image')) {
		    $image = Image::make($request->file('scheme_image'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('scheme_image')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $scheme->scheme_image = $filename;
	    }
	    $scheme->save();

	    return redirect('admin/schemes')->with('message_success', 'Успешно обновлено!');
    }

    public function destroy(Scheme $scheme)
    {
	    $scheme->delete();
	    return redirect('admin/schemes')->with('message_success', 'Успешно удалено!');
    }

	public function parse_info()
	{

		$scheme_id = 1;

		$json = file_get_contents('https://storage.mds.yandex.net/get-tickets/370270/bd009be5-c27a-4acf-bf37-9ced82215a40');
		$array = json_decode($json, true);

		foreach ($array['levels'] as $level) {

			$sector = SchemeSector::where('origin_id', $level['id'])->first();

			if ($sector) {
				$sector->svg_path = $level['outline'];
				$sector->save();
			} else {
				$sector = new SchemeSector();
				$sector->scheme_id = $scheme_id;
				$sector->origin_id = $level['id'];
				$sector->name = $level['name'];
				$sector->type = 0;
				$sector->status = 1;
				$sector->svg_path = $level['outline'];
				$sector->save();
			}

			foreach ($level['seats'] as $seat) {

				$place = SchemePlace::where('sector_id', $sector->id)->where('svg_row', $seat['row'])->where('svg_place', $seat['place'])->first();

				if ($place) {
					$place->svg_x = $seat['x_coord'];
					$place->svg_y = $seat['y_coord'];
					$place->save();
				} else {
					$place = new SchemePlace();
					$place->sector_id = $sector->id;
					$place->svg_row = $seat['row'];
					$place->svg_place = $seat['place'];
					$place->svg_x = $seat['x_coord'];
					$place->svg_y = $seat['y_coord'];
					$place->status = 1;
					$place->save();
				}
			}
		}


//		$json = file_get_contents('https://widget.tickets.yandex.ru/api/tickets/v1/sessions/OTAwfDc2MzE0fDE1NjEyMHwxNTMxNzYwNDAwMDAw/hallplan/async?clientKey=bb40c7f4-11ee-4f00-9804-18ee56565c87');
//		$array = json_decode($json, true);
//
//		foreach ($array['result']['hallplan']['levels'] as $category) {
//			$sector = SchemeSector::where('origin_id', $category['id'])->first();
//			if ($sector) {
//				foreach ($category['seats'] as $seat) {
//					$svg_row = isset($seat['seat']['row']) ? $seat['seat']['row'] : '1';
//					$svg_place = isset($seat['seat']['place']) ? $seat['seat']['place'] : '1';
//					$price = isset($seat['priceInfo']['price']['value']) ? intval($seat['priceInfo']['price']['value']/100) : 0;
//
//					$place = SchemePlace::where('sector_id', $sector->id)->where('svg_row', $svg_row)->where('svg_place', $svg_place)->where('status', 1)->first();
//					if ($place) {
//						$sale_price = new SalePlace();
//						$sale_price->event_id = 1;
//						$sale_price->place_id = $place->id;
//						$sale_price->price = $price;
//						$sale_price->status = 1;
//						$sale_price->source = 'yandex';
//						$sale_price->save();
//					} else {
//						echo '<pre>';
//						print_r('НЕ НАЙДЕНО: ' . $category['id'] . ' == ' . $svg_row . ' == ' . $svg_place);
//						echo '</pre>';
//					}
//				}
//			} else {
//				echo '<pre>';
//				print_r('НЕ НАЙДЕНО: ' . $category['id']);
//				echo '</pre>';
//			}
//
//			echo '<pre>';
//			print_r($category['name']);
//			echo '</pre>';
//		}
//
//		echo '<pre>';
//		print_r('----------------');
//		echo '</pre>';

//		$json = file_get_contents('https://widget.tickets.yandex.ru/api/tickets/v1/sessions/OTAwfDc2MzE0fDE1NjEyMHwxNTMxNzYwNDAwMDAw/hallplan/async?clientKey=bb40c7f4-11ee-4f00-9804-18ee56565c87');
//		$array = json_decode($json, true);
//
//		echo '<pre>';
//		print_r($array);
		echo '</pre>';



		//return redirect('admin/schemes/' . $scheme_id)->with('message_success', 'Успешно обновлено!');
	}
}
