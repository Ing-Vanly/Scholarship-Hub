@extends('backends.admin.layouts.app')
@push('css')
    <style>
        .table-wrapper {
            position: relative;
            overflow: visible !important;
            max-height: none !important;
        }

        .table-wrapper .table-responsive {
            overflow: visible !important;
            max-height: none !important;
            height: auto !important;
        }

        .table-wrapper .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }
    </style>
@endpush
@section('contents')
    <section class="content-header"></section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h4 class="mb-0">{{ __('User Management') }}</h4>
                                </div>
                                <div class="col-sm-6">
                                    {{-- @can('user.create') --}}
                                        <a class="btn btn-primary float-right" href="{{ route('admin.user.create') }}">
                                            <i class="fa fa-plus-circle"></i>
                                            {{ __('Add User') }}
                                        </a>
                                    {{-- @endcan --}}
                                    <a class="btn btn-outline-primary float-right mr-2"
                                        href="{{ route('admin.user.index') }}">
                                        <i class="fas fa-sync-alt"></i>
                                        {{ __('Refresh') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <form class="filterForm" data-module="user" data-table-wrapper="#userTableWrapper" method="GET"
                            action="{{ route('admin.user.index') }}">
                            @include('backends.admin.user.partials._filter', ['roles' => $roles])
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body p-3 table-wrapper" id="userTableWrapper">
                            @include('backends.admin.user._table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
