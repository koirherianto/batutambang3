
<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $role->name }}</p>
</div>

<!-- Guard Name Field -->
<div class="col-sm-12">
    {!! Form::label('guard_name', 'Guard Name:') !!}
    <p>{{ $role->guard_name }}</p>
</div>

<!-- Created At Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $role->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="col-sm-12">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $role->updated_at }}</p>
</div>

@if ($permissions->isEmpty())
    <p>This role has no permissions.</p>
@else
    <h2>Permissions:</h2>
    <ul>
        @foreach ($permissions as $key => $value)
            <li>{{ $value}}</li>
        @endforeach
    </ul>
@endif


