@extends('backends.master')

@push('css')
    <style>
        .user-form-card .card-header {
            border-bottom: 0;
        }

        .photo-box {
            width: 180px;
            height: 180px;
            border-radius: 18px;
            /* background-color: #f0faff;
                border: 1px dashed #9cc3ff; */
            cursor: pointer;
            position: relative;
            overflow: hidden;
            margin: 0 auto 15px;
        }

        .photo-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('contents')
    @php
        $imagePath = getImagePath($user->image, 'users', 'default-image.png');
    @endphp
    <section class="content-header"></section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <form method="POST" action="{{ route('admin.user.update', $user->id) }}" enctype="multipart/form-data"
                    class="col-12">
                    @csrf
                    @method('PUT')
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h4 class="mb-0">{{ __('Edit User') }}</h4>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <a class="btn btn-warning text-white mr-2" href="{{ route('admin.user.index') }}">
                                        <i class="fas fa-undo"></i>
                                        {{ __('Back') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save mr-1"></i>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('First Name') }}</label>
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                                placeholder="{{ __('Enter First Name') }}">
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('Last Name') }}</label>
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                                placeholder="{{ __('Enter Last Name') }}">
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('Username') }}</label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror" name="username"
                                                value="{{ old('username', $user->name) }}"
                                                placeholder="{{ __('Enter Username') }}">
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('User ID') }}</label>
                                            <input type="text"
                                                class="form-control @error('user_id') is-invalid @enderror" name="user_id"
                                                value="{{ old('user_id', $user->user_id) }}"
                                                placeholder="{{ __('Enter User ID') }}">
                                            @error('user_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('Email') }}</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', $user->email) }}"
                                                placeholder="{{ __('Enter Email') }}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('Phone') }}</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone', $user->phone) }}"
                                                placeholder="{{ __('Enter Phone') }}">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>{{ __('Password') }}</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                placeholder="{{ __('Leave blank to keep current') }}">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>{{ __('Confirm Password') }}</label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_lable">{{ __('Role') }}</label>
                                            <select name="role_id"
                                                class="form-control select2 @error('role_id') is-invalid @enderror">
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $id => $role)
                                                    <option value="{{ $id }}"
                                                        {{ old('role_id', optional($user->roles->first())->id) == $id ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>{{ __('Telegram') }}</label>
                                            <input type="text"
                                                class="form-control @error('telegram') is-invalid @enderror"
                                                name="telegram" value="{{ old('telegram', $user->telegram) }}"
                                                placeholder="{{ __('Enter Telegram username') }}">
                                            @error('telegram')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 d-flex align-items-center justify-content-center">
                                    <div class="form-group text-center w-100">
                                        <label class="d-block font-weight-bold">{{ __('Photo') }}</label>
                                        <div class="photo-box d-flex align-items-center justify-content-center mx-auto">
                                            <img class="photo-preview" src="{{ $imagePath }}" alt="preview">
                                            <input type="file" name="image" class="photo-input" accept="image/*"
                                                hidden>
                                        </div>
                                        @error('image')
                                            <span class="invalid-feedback d-block mb-2" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <button type="button" class="btn btn-outline-info btn-sm photo-upload-btn">
                                            <i class="fa fa-upload mr-1"></i> {{ __('Upload') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
