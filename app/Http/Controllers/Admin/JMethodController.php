<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JMethod\JMethodRequest;
use App\Http\Requests\JMethod\JMethodUpdateRequest;
use App\Models\JMethod;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JMethodController extends Controller
{
    /**
     * Show index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admins.jmethod.index', ['active' => 3]);
    }

    /**
     * display list
     *
     * @return void
     */
    public function list()
    {

        $jmethods = JMethod::select('ID', 'Name');
        $datatables = DataTables::eloquent($jmethods)
            ->editColumn('Name', function ($jmethod) {
                return "<div class='Name-JMethod' title='{$jmethod->Name}'>{$jmethod->Name}</div>";
            })
            ->addColumn('Action', function ($jmethod) {
                $url_edit = route('jmethods.edit', ['jmethodID' => $jmethod->ID]);
                $url_delete = route('jmethods.destroy', ['jmethodID' => $jmethod->ID]);

                return "<div class='text-right'>
                <button class='btn btn-info btn-xs edit' data-url='{$url_edit}' data-id='{$jmethod->ID}'><i class='far fa-edit'
                style='pointer-events: none'></i> Edit</button>
                <button class='btn btn-danger remove btn-xs' data-url='{$url_delete}'><i class='far fa-trash-alt'
                style='pointer-events: none'></i> Remove</button>
                </div>";
            })
            ->rawColumns(['Action', 'Name'])
            ->setRowId(function ($jmethod) {
                return "JMethod-$jmethod->ID";
            })
            ->toJson();
        return $datatables;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $html = view('partials.form.form-create_jmethod')->render();

        return response()->json(['html' => $html], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(JMethodRequest $request)
    {
        $data = [];
        if ($request->Name) {
            $data['Name'] = Str::upper($request->Name);
        }

        JMethod::create($data);

        return response()->json(['code' => 201], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(JMethod $jmethodID)
    {
        $html = view('partials.form.form-edit_jmethod', ['jmethod' => $jmethodID])->render();

        return response()->json(['html' => $html], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(JMethodUpdateRequest $request, $jmethodID)
    {
        $data = [];
        if ($request->Name) {
            $data['Name'] = Str::upper($request->Name);
        }
        $jmethod = JMethod::where('ID', $jmethodID);
        $jmethod->update($data);

        return response()->json(['Name' => $data['Name']], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($jmethodID)
    {
        $jmethod = JMethod::where('ID', $jmethodID);
        $jmethod->delete();

        return response()->json(['code' => 204], 200);
    }

    public function ajaxGetMethods(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(JMethod::all());
        }
    }
}
