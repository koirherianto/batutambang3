<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- password Field -->
@if ($isEditPage)
    <div class="form-group col-sm-6">
        {!! Form::label('password', 'Password: ') !!}
        {!! Form::password('password', ['class' => 'form-control', '']) !!}
    </div>
@else
    <div class="form-group col-sm-6">
        {!! Form::label('password', 'Password: ') !!}
        {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
    </div>
@endif

<div class="card bg-grey bg-lighten-4 rounded-2 col-sm-12">
    <div class="d-flex pt-1 pb-1">
        {!! Form::label('s_role_id', 'Hak Akses Diberikan', ['class' => 'col-md-3 label-control text-uppercase mb-0']) !!}
        <div class="skin skin-flat">
            @foreach ($sRoles as $item)
                <fieldset>
                    {!! Form::radio('s_role_id[]', $item->id, in_array($item->id, $roles) ? true : false, [
                        'id' => 'input-' . $item->id,
                    ]) !!}
                    <label for="input-{{ $item->id }}"
                        class="ml-1 text-bold-700 black text-uppercase">{!! $item->name !!}
                        {!! $item->desc !!}</label>
                </fieldset>
            @endforeach
        </div>
    </div>
</div>
