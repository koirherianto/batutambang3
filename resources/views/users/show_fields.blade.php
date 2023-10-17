<!-- Nama Lengkap Field -->
<div class="col-sm-12">
    {!! Form::label('nama_lengkap', 'Nama Lengkap:') !!}
    <p>{{ $user->nama_lengkap }}</p>
</div>

<!-- Nama Panggilan Field -->
<div class="col-sm-12">
    {!! Form::label('nama_panggilan', 'Nama Panggilan:') !!}
    <p>{{ $user->nama_panggilan }}</p>
</div>

<!-- Email Field -->
<div class="col-sm-12">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>

<!-- Is Approved Field -->
<div class="col-sm-12">
    {!! Form::label('is_approved', 'Is Approved:') !!}
    <p>{{ $user->is_approved }}</p>
</div>

<!-- Email Verified At Field -->
<div class="col-sm-12">
    {!! Form::label('email_verified_at', 'Email Verified At:') !!}
    <p>{{ $user->email_verified_at }}</p>
</div>

<!-- Password Field -->
<div class="col-sm-12">
    {!! Form::label('password', 'Password:') !!}
    <p>{{ $user->password }}</p>
</div>

<!-- Remember Token Field -->
<div class="col-sm-12">
    {!! Form::label('remember_token', 'Remember Token:') !!}
    <p>{{ $user->remember_token }}</p>
</div>

