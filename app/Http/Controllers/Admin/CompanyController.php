<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyModel;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use URL;
use Validator;

class CompanyController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = CompanyModel::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Action', function ($row) {

                    $delete = ' <form method="POST"  action="' . route('company.destroy', [$row->id]) . ' ">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn "><img src="' . url('/public') . '/assets/imgs/trash.png" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href=" ' . route('company.edit', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '</a></div>';
                    return $btn;
                })
                ->rawColumns(['Action'])
                ->make(true);
        } else {
            return view('admin.company.index', $params);
        }

    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('company.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            return view('admin.company.add');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            $company = new CompanyModel();
            $company->name = $request->name;
            $company->status = '1';

            if ($company->save()) {
                return response()->json(array('status' => 200, 'success' => "Company Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Company Not Added"));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function edit($id)
    {
        $params['data'] = CompanyModel::where('id', $id)->first();
        return view('admin.company.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return response()->json(array('status' => 400, 'errors' => ("This User Is Not Access Your Pages")));
            } else {
                $requestData = $request->all();
                $company = CompanyModel::updateOrCreate(['id' => $requestData['id']], $requestData);
                if (isset($company->id)) {
                    return response()->json(array('status' => 200, 'success' => "Company Update Succesfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Company Not Updated"));
                }
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('company.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            $data = CompanyModel::where('id', $id)->first();
            $data->delete();
            return redirect()->route('company.index')->with('success', "Company Delete Succesfully");
        }

    }
}
