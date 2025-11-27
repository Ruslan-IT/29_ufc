<div class="row">
	<div class="col-xl-6 col-lg-12">

        <div class="form-group">
            {!! Form::label('status', 'Активность', [], false) !!}
            <button type="button" class="switch-button btn btn-toggle btn-primary{{ isset($scheme) && $scheme->status ? ' active' : '' }}" data-toggle="button" aria-pressed="true" data-switch_hidden_id="field_scheme_status">
                <span class="handle"></span>
            </button>
            {{ Form::hidden('status', null, ['class' => 'switch-hidden', 'data-switch_hidden_id' => 'field_scheme_status']) }}
        </div>

		<div class="form-group{{ empty($errors->get('name')) ? '' : ' has-error' }}">
			{!! Form::label('name', 'Название схемы площадки <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @foreach($errors->get('name') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
		</div>

        <div class="form-group{{ empty($errors->get('location_id')) ? '' : ' has-error' }}">
            {!! Form::label('location_id', 'Локация <span class="text-danger">*</span>', [], false) !!}
            {{ Form::select('location_id', $locations, null, ['class' => 'form-control select-select2']) }}
            @foreach($errors->get('location_id') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('image_width')) ? '' : ' has-error' }}">
            {!! Form::label('image_width', 'Ширина схемы площадки, px', [], false) !!}
            {{ Form::text('image_width', null, ['class' => 'form-control']) }}
            @foreach($errors->get('image_width') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('image_height')) ? '' : ' has-error' }}">
            {!! Form::label('image_height', 'Высота схемы площадки, px', [], false) !!}
            {{ Form::text('image_height', null, ['class' => 'form-control']) }}
            @foreach($errors->get('image_height') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('scheme_image')) ? '' : ' has-error' }}">
            {!! Form::label('scheme_image', 'Изображение схемы', [], false) !!}
            <div class="file-image-button">
                <label>
                    {{ Form::file('scheme_image', null, ['class' => 'form-control']) }}
                    <div class="btn btn-info">Выбрать файл</div>
                </label>
                <div class="file-image-button-image">
                    @if($scheme_image)
                        <img src="{{ $scheme_image }}">
                    @endif
                </div>
            </div>
            @foreach($errors->get('scheme_image') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

	</div>
</div>