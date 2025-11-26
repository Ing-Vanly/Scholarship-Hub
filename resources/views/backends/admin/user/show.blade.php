@extends('backends.admin.layouts.app')
@push('css')
    <style>
        .content-header {
            padding: 10px !important;
        }
    </style>
@endpush
@section('contents')
    <section class="content-header"></section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @php
                        $imagePath = getImagePath($user->image, 'users', 'default-image.png');
                        $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: __('N/A');
                        $primaryRole = optional($user->roles->first())->name ?? __('N/A');
                    @endphp
                    <div class="card card-primary card-outline mt-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-bold mb-0">{{ __('User Details') }}</h3>
                                <div>
                                    <a class="btn btn-warning text-white mr-2" href="{{ route('admin.user.index') }}">
                                        <i class="fas fa-undo"></i>
                                        {{ __('Back') }}
                                    </a>
                                    <a class="btn btn-primary" href="{{ route('admin.user.edit', $user->id) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                        {{ __('Edit') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    <img src="{{ $imagePath }}" alt="{{ $fullName }}" class="img-fluid mb-3"
                                        style="max-height: 160px; solid #e5edf9; object-fit: cover;">
                                    <div class="text-muted">
                                        <strong>{{ __('User ID') }}</strong>
                                        <span class="d-block">{{ $user->user_id ?? __('N/A') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <ul class="list-group border-0">
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Full Name') }}</strong>
                                            <span class="text-muted">: {{ $fullName }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Username') }}</strong>
                                            <span class="text-muted">: {{ $user->name ?? __('N/A') }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Email') }}</strong>
                                            <span class="text-muted">: {{ $user->email ?? __('N/A') }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Phone') }}</strong>
                                            <span class="text-muted">: {{ $user->phone ?? __('N/A') }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-5">
                                    <ul class="list-group border-0">
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Role') }}</strong>
                                            <span class="text-muted">: {{ $primaryRole }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Address') }}</strong>
                                            <span class="text-muted">: {{ $user->address ?? __('N/A') }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Created At') }}</strong>
                                            <span class="text-muted">:
                                                {{ optional($user->created_at)->format('d M Y h:i A') ?? __('N/A') }}</span>
                                        </li>
                                        <li class="list-group-item border-0 px-0 d-flex justify-content-between">
                                            <strong>{{ __('Updated At') }}</strong>
                                            <span class="text-muted">:
                                                {{ optional($user->updated_at)->format('d M Y h:i A') ?? __('N/A') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
