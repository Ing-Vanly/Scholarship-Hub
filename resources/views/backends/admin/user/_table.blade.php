<div class="table-responsive">
    <table class="table table-hover text-nowrap user-table">
        <thead>
            <tr>
                <th>{{ __('Action') }}</th>
                <th data-column="1">{{ __('Image') }}</th>
                <th data-column="2">{{ __('Username') }}</th>
                <th data-column="3">{{ __('Role') }}</th>
                <th data-column="4">{{ __('Phone') }}</th>
                <th data-column="5">{{ __('Email') }}</th>
                <th data-column="6">{{ __('Created') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                @include('backends.svg.tool')
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $user->id }}">
                                {{-- @can('user.view') --}}
                                <a href="{{ route('admin.user.show', $user->id) }}"
                                    class="dropdown-item btn btn-success btn-sm">
                                    <i class="fas fa-eye"></i>
                                    {{ __('View') }}
                                </a>
                                {{-- @endcan --}}
                                {{-- @can('user.edit') --}}
                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                    class="dropdown-item btn btn-info btn-sm btn-edit">
                                    <i class="fas fa-pencil-alt"></i>
                                    {{ __('Edit') }}
                                </a>
                                {{-- @endcan --}}
                                <form action="javascript:void(0);" class="d-inline form-delete-{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    {{-- @can('user.delete')
                                        @if (!$user->hasRole('admin')) --}}
                                    <button type="button" data-id="{{ $user->id }}"
                                        data-href="{{ route('admin.user.destroy', $user->id) }}"
                                        data-refresh-module="user"
                                        data-remove-target="tr[data-id='{{ $user->id }}']"
                                        class="dropdown-item btn btn-danger btn-sm text-danger btn-delete-user">
                                        <i class="fa fa-trash-alt text-danger"></i>
                                        {{ __('Delete') }}
                                    </button>
                                    {{-- @endif
                                    @endcan --}}
                                </form>
                            </div>
                        </div>
                    </td>
                    <td>
                        <img src="{{ getImagePath($user->image, 'users') }}" alt="Profile" class="img-fluid rounded"
                            width="40" height="40">
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>
                        @php
                            $roleName = optional($user->roles->first())->name;
                        @endphp
                        <span class="badge badge-light">{{ $roleName ?? __('N/A') }}</span>
                    </td>
                    <td>{{ $user->phone ?? __('N/A') }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ optional($user->created_at)->format('d M Y h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">{{ __('No users found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($users->count())
    <div class="row mt-3">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $users->firstItem() }} {{ __('to') }} {{ $users->lastItem() }}
                    {{ __('of') }} {{ $users->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endif
