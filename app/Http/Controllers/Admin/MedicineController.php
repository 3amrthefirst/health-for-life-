<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicineModel;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Storage;
use URL;
use Validator;

class MedicineController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = MedicineModel::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Action', function ($row) {

                    $delete = ' <form method="POST"  action=" ' . route('medicine.destroy', [$row->id]) . ' ">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn "><img src="' . url('/public') . '/assets/imgs/trash.png" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href=" ' . route('medicine.edit', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '</a></div>';
                    return $btn;
                })
                ->rawColumns(['Action'])
                ->make(true);
        }
        return view('admin.medicine.index', $params);
    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('medicine.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            return view('admin.medicine.add');
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'form' => 'required',
                'power' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $Medicine = MedicineModel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($Medicine->id)) {
                return response()->json(array('status' => 200, 'success' => "Medicine Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Medicine Not Added"));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function edit($id)
    {
        $params['data'] = MedicineModel::where('id', $id)->first();
        return view('admin.medicine.edit', $params);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'form' => 'required',
                'power' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if (Auth::guard('admin')->user()->role_id == 2) {
                return response()->json(array('status' => 400, 'errors' => ("This User Is Not Access Your Pages")));
            } else {
                $requestData = $request->all();
                
                $Medicine = MedicineModel::updateOrCreate(['id' => $requestData['id']], $requestData);
                if (isset($Medicine->id)) {
                    return response()->json(array('status' => 200, 'success' => "Medicine Update Succesfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Medicine Not Updated"));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return redirect()->route('medicine.index')->with('errors', 'This User Is Not Access Your Pages');
            } else {
                $data = MedicineModel::where('id', $id)->first();
                $data->delete();
                return redirect()->route('medicine.index')->with('success', "Medicine Delete Succesfully");
            }

        } catch (Exception $e) {
            return redirect()->route('medicine.index')->with($e);
        }

    }

}
