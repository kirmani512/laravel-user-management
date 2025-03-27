<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        return view('portal.districts.index');
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
        $totalRecords = District::select('count(*) as allcount')->count();
        $totalRecordswithFilter = District::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();
        $records = District::orderBy($columnName, $columnSortOrder)
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
            $route = route('district.edit', $record->id);
            $delete_route = route('district.delete', $record->id);





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
        return view('portal.districts.add');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        $district = District::create([
            'name' => $request->name
        ]);
        return redirect()->route('district.index')->with('success','District Added');
    }
    public function edit($id)
    {
        $district = District::find($id);
        return view('portal.districts.edit', compact('district'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',

        ]);
        $district = District::findOrFail($id);
        $district->name = $validated['name'];

        $district->save();

        return redirect()->route('district.index')->with('success', 'District Updated');
    }
    public function delete($id)
    {
        $district = District::findOrFail($id);
        $district->delete();
        return redirect()->route('district.index')->with('success','District Deleted');
    }


}
