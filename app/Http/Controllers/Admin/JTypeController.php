<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JType\JTypeRequest;
use App\Http\Requests\JType\JTypeUpdateRequest;
use App\Models\JType;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JTypeController extends Controller
{
    /**
     * Show index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admins.jtype.index', ['active' => 4]);
    }

    /**
     * display list
     *
     * @return void
     */
    public function list()
    {

        $jtypes = JType::select('ID', 'Name');
        $datatables = DataTables::eloquent($jtypes)
            ->editColumn('Name', function ($jtype) {
                return "<div class='Name-JType' title='{$jtype->Name}'>{$jtype->Name}</div>";
            })
            ->addColumn('Action', function ($jtype) {
                $url_edit = route('jtypes.edit', ['jtypeID' => $jtype->ID]);
                $url_delete = route('jtypes.destroy', ['jtypeID' => $jtype->ID]);

                return "<div class='text-right'>
                <button class='btn btn-info btn-xs edit' data-url='{$url_edit}' data-id='{$jtype->ID}'><i class='far fa-edit'
                style='pointer-events: none'></i> Edit</button>
                <button class='btn btn-danger remove btn-xs' data-url='{$url_delete}'><i class='far fa-trash-alt'
                style='pointer-events: none'></i> Remove</button>
                </div>";
            })
            ->rawColumns(['Action', 'Name'])
            ->setRowId(function ($jmethod) {
                return "JType-$jmethod->ID";
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
        $html = view('partials.form.form-create_jtype')->render();

        return response()->json(['html' => $html], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(JTypeRequest $request)
    {
        $data = [];
        if ($request->Name) {
            $data['Name'] = Str::upper($request->Name);
        }
        JType::create($data);

        return response()->json(['code' => 201], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(JType $jtypeID)
    {
        $html = view('partials.form.form-edit_jtype', ['jtype' => $jtypeID])->render();

        return response()->json(['html' => $html], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(JTypeUpdateRequest $request, $jtypeID)
    {
        $data = [];
        if ($request->Name) {
            $data['Name'] = Str::upper($request->Name);
        }
        $jtype = JType::where('ID', $jtypeID);
        $jtype->update($data);

        return response()->json(['Name' => $data['Name']], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($jtypeID)
    {
        $jtype = JType::where('ID', $jtypeID);
        $jtype->delete();

        return response()->json(['code' => 204], 200);
    }

    public function ajaxGetTypes(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(JType::all());
        }
    }
}
