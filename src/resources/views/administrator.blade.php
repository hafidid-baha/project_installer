@extends('project_installer::main')

@section('content')

    <form method="post" action="{{ route('install.finish') }}" autocomplete="off">
        @csrf

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add your system Administrator</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Administrator full name</label>
                            <input type="text" name="name" value="{{ old('name') }}" maxlength="255" class="form-control" placeholder="Full name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Administrator E-mail</label>
                            <input type="email" name="email" value="{{ old('email') }}" maxlength="255" class="form-control" placeholder="E-mail" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Administrator password</label>
                            <input type="password" name="password" value="" minlength="6" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" value="" minlength="6" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>

                </div>



            </div>
            <div class="card-footer">
                <button class="btn btn-block btn-primary">Finish installation</button>
            </div>
        </div>

    </form>

@endsection
