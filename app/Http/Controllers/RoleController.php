<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    public function index()
    {

        return view('portal.roles.index');
    }
    public function list(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Role::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Role::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
        $records = Role::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
            })
            // /->where('role', 'Employee') // Add the condition for role
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $permissions = $record->permissions->pluck('name')->join(', '); // Fetch permissions as comma-separated string
            $route = route('role.edit', $record->id);
            $delete_route = route('role.delete', $record->id);





            $data_arr[] = array(
                "id" => $record->id,
                "role_name" => $record->name,
                "permissions" => $permissions,
                "action" => '
                <a href="' . $route . '" class="mr-1 text-info" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="#" onclick="delete_confirmation(\'' . $delete_route . '\')" class="mr-1 text-danger" title="Delete">
                    <i class="bi bi-trash3"></i>
                </a>
            </div>'
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return \Response::json($response);
    }
    public function create()
    {
        $permissions = Permission::all();
        return view('portal.roles.add', compact('permissions'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role = Role::create(['name' => $validated['role']]);
        if (!empty($validated['permissions'])) {
            $permissions = Permission::where('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }
        return redirect()->route('role.index')->with('success', 'Role Added');
    }
    public function edit($id)
    {

        $role = Role::find($id);
        $permissions = Permission::all();
        return view('portal.roles.edit',compact('role','permissions'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role = Role::findOrFail($id);
        $role->name = $validated['name'];
        $role->save();

        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
        ->where('guard_name', $role->guard_name) // Ensure correct guard
        ->get();
        $role->syncPermissions($permissions);

        return redirect()->route('role.index')->with('success', 'Role Updated');
    }
    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role Deleted');
    }
}
