<div class="card-body px-0">
    <div class="col-12">
        <div class="row justify-content-between flex-wrap gap-2 px-4">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="btn-group mr-2">
                    <select name="per_page" class="form-control form-control-sm perPageSelect" id="per_page_filter">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-light btn-sm dropdown-toggle border" data-toggle="dropdown">
                        {{ strtoupper(request('sort_order', 'DESC')) }}
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ request('sort_order', 'DESC') === 'ASC' ? 'active' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['sort_order' => 'ASC', 'page' => null]) }}">
                            ASC
                        </a>
                        <a class="dropdown-item {{ request('sort_order', 'DESC') === 'DESC' ? 'active' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['sort_order' => 'DESC', 'page' => null]) }}">
                            DESC
                        </a>
                    </div>
                </div>
                <div class="btn-group mr-2">
                    <button type="button" class="btn btn-light btn-sm dropdown-toggle border" data-toggle="dropdown">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-2" id="column-visibility-menu">
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="1" checked> {{ __('Image') }}
                        </label>
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="2" checked> {{ __('Username') }}
                        </label>
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="3" checked> {{ __('Role') }}
                        </label>
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="4" checked> {{ __('Phone') }}
                        </label>
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="5" checked> {{ __('Email') }}
                        </label>
                        <label class="dropdown-item mb-0">
                            <input type="checkbox" class="toggle-column" data-column="6" checked> {{ __('Created') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                        placeholder="{{ __('Search name, email or phone') }}">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
