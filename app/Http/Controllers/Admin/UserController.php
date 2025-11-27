<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\helpers\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Throwable;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request)
    {
        // $this->authorizeAbility('user.view');

        $users = $this->userService->getUsers($request);
        $roles = Role::orderBy('name')->pluck('name', 'id');

        if ($request->ajax()) {
            return view('backends.admin.user._table', compact('users'))->render();
        }

        return view('backends.admin.user.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        // $this->authorizeAbility('user.create');

        $roles = Role::orderBy('name')->pluck('name', 'id');

        return view('backends.admin.user.create', compact('roles'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        // $this->authorizeAbility('user.create');

        $data = $request->validated();
        $data['image'] = $request->file('image');

        try {
            CreateUserAction::run($data);

            return redirect()->route('admin.user.index')
                ->with('success', __('User created successfully.'));
        } catch (Throwable $throwable) {
            report($throwable);

            return back()->withInput()
                ->with('error', __('Unable to create user.'));
        }
    }

    public function show(User $user): View
    {
        // $this->authorizeAbility('user.view');

        $user->load('roles');

        return view('backends.admin.user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        // $this->authorizeAbility('user.edit');

        $user->load('roles');
        $roles = Role::orderBy('name')->pluck('name', 'id');

        return view('backends.admin.user.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorizeAbility('user.edit');

        $data = $request->validated();
        $data['image'] = $request->file('image');

        try {
            UpdateUserAction::run($data, $user);

            return redirect()->route('admin.user.index')
                ->with('success', __('User updated successfully.'));
        } catch (Throwable $throwable) {
            report($throwable);

            return back()->withInput()
                ->with('error', __('Unable to update user.'));
        }
    }

    public function destroy(User $user): JsonResponse
    {
        // $this->authorizeAbility('user.delete');

        try {
            if ($user->image) {
                ImageManager::delete('uploads/users/' . $user->image);
            }

            $user->delete();

            return response()->json([
                'success' => 1,
                'msg' => __('User deleted successfully.'),
            ]);
        } catch (Throwable $throwable) {
            report($throwable);

            return response()->json([
                'success' => 0,
                'msg' => __('Unable to delete user.'),
            ], 500);
        }
    }

    protected function authorizeAbility(string $ability): void
    {
        $user = auth()->user();

        if (! $user || ! $user->can($ability)) {
            abort(403);
        }
    }
}
