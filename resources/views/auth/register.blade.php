<!-- resources/views/auth/register.blade.php -->
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all('<p>:message</p>') as $field=>$error)
                <li>{{$field}} {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@if ($errors->has('password')) 
    //
{{$errors->first('password') }}
@endif
@endif
<?php var_dump( session()->all()); ?>
<form method="POST" action="/register">
    {!! csrf_field() !!}

    <div class="col-md-6">
        Name
        <input type="text" name="name" value="{{ old('name') }}">
    </div>

    <div>
        Email
        <input type="email" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password">
    </div>

    <div class="col-md-6">
        Confirm Password
        <input type="password" name="password_confirmation">
    </div>

    <div>
        <button type="submit">Register</button>
    </div>
</form>


