<?php

namespace App\Http\Controllers\Admin;

use App\Location;
use App\Http\Requests\RequestLocation;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class LocationController extends Controller
{
    public function index()
    {
	    $data = [];
	    $data['page_title'] = 'Список локаций';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Локации',
			    'link' => false
		    ]
	    ];

	    $data['locations'] = Location::all();

	    return view('admin.location.index', $data);
    }

    public function create()
    {
    	$data = [];
    	$data['page_title'] = 'Добавить локацию';

    	$data['breadcrumbs'] = [
    		0 => [
    		    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Локации',
			    'link' => url('admin/locations')
		    ],
		    2 => [
			    'title' => 'Создать',
			    'link' => false
		    ]
	    ];

	    $data['location_image'] = '';
	    $data['cancel_link'] = url('admin/locations');

        return view('admin.location.create', $data);
    }

    public function store(RequestLocation $request)
    {
	    $location = new Location();
	    $location->name = $request->input('name');
	    $location->address = $request->input('address');
	    $location->status = $request->input('status') ? $request->input('status') : 0;

	    if ($request->file('image')) {
		    $image = Image::make($request->file('image'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('image')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $location->image = $filename;
	    }

	    $location->save();

	    return redirect('admin/locations')->with('message_success', 'Успешно додано!');
    }

    public function show(Location $location)
    {
        //
    }

    public function edit(Location $location)
    {
	    $data = [];
	    $data['page_title'] = 'Изменить локацию';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Локации',
			    'link' => url('admin/locations')
		    ],
		    2 => [
			    'title' => 'Изменить',
			    'link' => false
		    ]
	    ];

	    $data['location'] = $location;
	    $data['location_image'] = $location->image ? url('img/cache/mini/' . $location->image) : '';
	    $data['cancel_link'] = url('admin/locations');

	    return view('admin.location.edit', $data);
    }

    public function update(RequestLocation $request, Location $location)
    {
	    $location->name = $request->input('name');
	    $location->address = $request->input('address');
	    $location->status = $request->input('status') ? $request->input('status') : 0;

	    if ($request->file('image')) {
			$image = Image::make($request->file('image'));
			$mime = $image->mime;
			$extension = 'jpg';
			if ($mime == 'image/png') $extension = 'png';
			if ($mime == 'image/gif') $extension = 'gif';
			if ($mime == 'image/bmp') $extension = 'bmp';
			if ($mime == 'image/tiff') $extension = 'tiff';
			if ($mime == 'image/svg+xml') $extension = 'svg';
			$filename = md5($request->file('image')->getClientOriginalName() . microtime()) . '.' . $extension;
			$image->save(storage_path('uploads/images/' . $filename));
		    $location->image = $filename;
		}

	    $location->save();

	    return redirect('admin/locations')->with('message_success', 'Успешно обновлено!');
    }

    public function destroy(Location $location)
    {
	    $location->delete();
	    return redirect('admin/locations')->with('message_success', 'Успешно удалено!');
    }
}
