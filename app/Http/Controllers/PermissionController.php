<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('portal.permissions.index');
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
        $totalRecords = Permission::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Permission::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
        $records = Permission::orderBy($columnName, $columnSortOrder)
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
            $route = route('permission.edit', $record->id);
            $delete_route = route('permission.delete', $record->id);





            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
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
        return view('portal.permissions.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'permission' => 'required|string',
        ]);
        $permission =  Permission::create([
            'name' => $request->permission
        ]);
        return redirect()->route('permission.index');
    }
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('portal.permissions.edit', compact('permission'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',

        ]);
        $permission = Permission::findOrFail($id);
        $permission->name = $validated['name'];

        $permission->save();

        return redirect()->route('permission.index')->with('success', 'Permission Updated');
    }
    public function delete($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('permission.index')->with('success', 'Permission Deleted');
    }
}
