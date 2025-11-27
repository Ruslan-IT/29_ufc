<?php

namespace App\Http\Controllers\Admin;

use App\Participant;
use App\Http\Requests\RequestParticipant;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ParticipantController extends Controller
{
    public function index()
    {
	    $data = [];
	    $data['page_title'] = 'Список участников';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Участники',
			    'link' => false
		    ]
	    ];

	    $data['participants'] = Participant::all();

	    return view('admin.participant.index', $data);
    }

    public function create()
    {
    	$data = [];
    	$data['page_title'] = 'Добавить участника';

    	$data['breadcrumbs'] = [
    		0 => [
    		    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Участники',
			    'link' => url('admin/participants')
		    ],
		    2 => [
			    'title' => 'Создать',
			    'link' => false
		    ]
	    ];

	    $data['participant_image'] = '';
	    $data['participant_image_large'] = '';
	    $data['countries'] = Participant::getCountries();
	    $data['sort'] = 0;

	    $data['cancel_link'] = url('admin/participants');

        return view('admin.participant.create', $data);
    }

    public function store(RequestParticipant $request)
    {
	    $participant = new Participant();
	    $participant->name = $request->input('name');
	    $participant->text = $request->input('text');
	    $participant->country = $request->input('country');
	    $participant->sort = $request->input('sort') ? $request->input('sort') : 0;
	    $participant->status = $request->input('status') ? $request->input('status') : 0;

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
		    $participant->image = $filename;
	    }

	    if ($request->file('image_large')) {
		    $image = Image::make($request->file('image_large'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('image_large')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $participant->image_large = $filename;
	    }

	    $participant->save();

	    return redirect('admin/participants')->with('message_success', 'Успешно додано!');
    }

    public function edit(Participant $participant)
    {
	    $data = [];
	    $data['page_title'] = 'Изменить участника';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Участники',
			    'link' => url('admin/participants')
		    ],
		    2 => [
			    'title' => 'Изменить',
			    'link' => false
		    ]
	    ];

	    $data['participant'] = $participant;
	    $data['participant_image'] = $participant->image ? url('img/cache/mini/' . $participant->image) : '';
	    $data['participant_image_large'] = $participant->image_large ? url('img/cache/mini/' . $participant->image_large) : '';
	    $data['countries'] = Participant::getCountries();
	    $data['sort'] = $participant->sort;

	    $data['cancel_link'] = url('admin/participants');

	    return view('admin.participant.edit', $data);
    }

    public function update(RequestParticipant $request, Participant $participant)
    {
	    $participant->name = $request->input('name');
	    $participant->text = $request->input('text');
	    $participant->country = $request->input('country');
	    $participant->sort = $request->input('sort') ? $request->input('sort') : 0;
	    $participant->status = $request->input('status') ? $request->input('status') : 0;

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
		    $participant->image = $filename;
		}

	    if ($request->file('image_large')) {
		    $image = Image::make($request->file('image_large'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('image_large')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $participant->image_large = $filename;
	    }

	    $participant->save();

	    return redirect('admin/participants')->with('message_success', 'Успешно обновлено!');
    }

    public function destroy(Participant $participant)
    {
	    $participant->delete();
	    return redirect('admin/participants')->with('message_success', 'Успешно удалено!');
    }
}
