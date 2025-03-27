<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\District;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {


        return view('portal.users.index');
    }

    public function list(Request $request)
    {
        $user = Auth::user();

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


        $query = User::query();

        if ($user->hasRole('employee')) {
            $query->where('id', $user->id);
        } elseif ($user->hasRole('area_manager')) {
            $query->where('district_id', $user->district_id);
        }
        // Total records
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->orWhere('email', 'like', '%' . $searchValue . '%')->count();
        $records = User::orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhere('district_id', 'like', '%' . $searchValue . '%')
                    ->orWhere('salary', 'like', '%' . $searchValue . '%');
            })
            // /->where('role', 'Employee') // Add the condition for role
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName, $columnSortOrder)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $route = route('user.edit', $record->id);
            $delete_route = route('user.delete', $record->id);

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "role" => $record->getRoleNames()->isNotEmpty() ? $record->getRoleNames()->first() : 'N/A', // Get the first role or N/A
                'district' => $record->district ? $record->district->name : 'N/A',
                'salary' => $record->salary,
                "phone" => $record->phone,
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

        $districts = District::all();
        $roles = Role::all();
        return view('portal.users.add', compact('districts', 'roles'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password' => 'required|string',
            'role' => 'required|string|exists:roles,name',
            'district_id' => 'nullable|exists:districts,id',
            'salary' => 'nullable|numeric'
        ]);

        $user = User::Create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'district_id' => $validated['district_id'] ?? null,
            'salary' => $validated['salary'] ?? null,

        ]);
        $role = Role::findByName($validated['role']);
        $user->assignRole($role);
        return redirect()->route('user.index')->with('success', 'User Added');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->hasRole('employee') && auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        if (auth()->user()->hasRole('area_manager') && $user->district_id !== auth()->user()->district_id) {
            abort(403, 'Unauthorized action.');
        }
        $districts = District::all();
        $roles = Role::all();
        return view('portal.users.edit', compact('user', 'districts', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'nullable|string',
            'role' => 'required|string|exists:roles,name',
            'district_id' => 'nullable|exists:districts,id',
            'salary' => 'nullable|numeric',
        ]);
        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->district_id = $validated['district_id'] ?? null;
        $user->salary = $validated['salary'] ?? null;

        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }
        $role = Role::findByName($validated['role']);
        $user->syncRoles([$role]);
        $user->save();

        return redirect()->route('user.index')->with('success', 'User Updated');
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->hasRole('employee')) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->hasRole('area_manager') && $user->district_id !== auth()->user()->district_id) {
            abort(403, 'Unauthorized action.');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User Deleted');
    }
    public function viewDetail()
    {
        $user = Auth::user();

        return view('portal.users.profile.view', compact('user'));
    }
    public function editProfile()
    {
        $user = auth()->user();
        return view('portal.users.profile.edit-profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'nullable|string',
        ]);
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->route('user.view')->with('success', 'Profile Updated Successfully');
    }

    public function areaManagerIndex()
    {
        return view('portal.users.area-manager-index');
    }
    public function employeeIndex()
    {
        return view('portal.users.employee-index');
    }

    public function areamanagerDetails(Request $request)
    {
        $user = Auth::user();

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        // Base query for records - Fetch only area managers
        $query = User::query()->whereHas('roles', function ($q) {
            $q->where('name', 'area_manager');
        });

        // Filter by district for area managers
        if ($user->hasRole('area_manager')) {
            $query->where('district_id', $user->district_id);
        }

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%$searchValue%")
                    ->orWhere('email', 'like', "%$searchValue%")
                    ->orWhere('salary', 'like', "%$searchValue%");
            });
        }

        $totalRecords = $query->count();

        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            // Fetch employee names in the same district
            $employeesInDistrict = User::where('district_id', $record->district_id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'employee');
                })
                ->pluck('name')
                ->implode(', ');

            $data_arr[] = [
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "role" => $record->getRoleNames()->isNotEmpty() ? $record->getRoleNames()->first() : 'N/A',
                "district" => $record->district ? $record->district->name : 'N/A',
                "salary" => $record->salary,
                "employees_in_district" => $employeesInDistrict ?: 'No Employees',
            ];
        }

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data_arr
        ];

        return response()->json($response);
    }

    public function employeeDetails(Request $request)
    {
        $user = Auth::user();

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

        $query = User::query();

        // Filter for employee role
        if ($user->hasRole('employee')) {
            $query->where('id', $user->id);
        }

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%$searchValue%")
                    ->orWhere('email', 'like', "%$searchValue%")
                    ->orWhere('salary', 'like', "%$searchValue%");
            });
        }

        $totalRecords = $query->count();

        $records = $query->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = $records->map(function ($record) {
            return [
                "id" => $record->id,
                "name" => $record->name,
                "email" => $record->email,
                "role" => $record->getRoleNames()->isNotEmpty() ? $record->getRoleNames()->first() : 'N/A',
                "district" => $record->district ? $record->district->name : 'N/A',
                "salary" => $record->salary,
            ];
        })->toArray();

        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data_arr
        ];

        return response()->json($response);
    }
}
