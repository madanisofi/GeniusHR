<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Role')) {
            $roles = Role::where('created_by', '=', Auth::user()->creatorId())->get();

            return view('role.index')->with('roles', $roles);
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Role')) {
            $user = Auth::user();
            if ($user->type == 'super admin' || $user->type == 'company') {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
                sort($permissions);
            } else {
                $permissions = new Collection();
                foreach ($user->roles as $role) {
                    $permissions = $permissions->merge($role->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }

            return view('role.create', ['permissions' => $permissions]);
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Role')) {
            $role = Role::where('name', '=', $request->name)
                ->where('created_by', Auth::user()->creatorId())->first();
            if (isset($role)) {
                return back()->with('error', __('The Role has Already Been Taken.'));
            } else {
                $this->validate(
                    $request,
                    [
                        'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,' . Auth::user()->creatorId(),
                        'permissions' => 'required',
                    ]
                );

                $name             = $request['name'];
                $role             = new Role();
                $role->name       = $name;
                $role->level       = $request['level'];
                $role->created_by = Auth::user()->creatorId();
                $permissions      = $request['permissions'];
                $role->save();

                foreach ($permissions as $permission) {
                    $p    = Permission::where('id', '=', $permission)->firstOrFail();
                    $role = Role::where('id', '=', $role->id)->first();
                    $role->givePermissionTo($p);
                }

                return redirect()->route('roles.index')->with('success', 'Role successfully created.');
            }
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }

    public function edit(Role $role)
    {

        if (Auth::user()->can('Edit Role')) {

            $user = Auth::user();
            if ($user->type == 'super admin' || $user->type == 'company') {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
                sort($permissions);
            } else {
                $permissions = new Collection();
                foreach ($user->roles as $role1) {
                    $permissions = $permissions->merge($role1->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }


            return view('role.edit', compact('role', 'permissions'));
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }

    public function update(Request $request, Role $role)
    {
        if (Auth::user()->can('Edit Role')) {
            if ($role->name == 'employee' or $role->name == 'company' or $role->name == 'hr') {
                $this->validate(
                    $request,
                    [
                        'permissions' => 'required',
                    ]
                );
            } else {
                $this->validate(
                    $request,
                    [
                        'name' => 'required|max:100|unique:roles,name,' . $role['id'] . ',id,created_by,' . Auth::user()->creatorId(),
                        'permissions' => 'required',
                    ]
                );
            }

            if (isset($request->name)) {
                User::where('type', $role->name)->update(array(
                    'type' => $request->name
                ));
            }

            Role::find($role->id)->update(array(
                'level' => $request->level
            ));

            $input       = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::all();

            foreach ($p_all as $p) {
                $role->revokePermissionTo($p);
            }

            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return redirect()->route('roles.index')->with('success', 'Role successfully updated.');
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }

    public function destroy(Role $role)
    {
        if (Auth::user()->can('Delete Role')) {
            $role->delete();

            return redirect()->route('roles.index')->with(
                'success',
                'Role successfully deleted.'
            );
        } else {
            return back()->with('error', 'Permission denied.');
        }
    }
}
