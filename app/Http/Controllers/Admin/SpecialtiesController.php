<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialtieModel;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Storage;
use URL;
use Validator;

class SpecialtiesController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = SpecialtieModel::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Action', function ($row) {

                    $delete = ' <form method="POST"  action=" ' . route('specialties.destroy', [$row->id]) . ' ">
                <input type="hidden" name="_token" value="' . csrf_token() . '">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn "><img src="' . url('/public') . '/assets/imgs/trash.png" /></button></form>';

                    $btn = '<div class="d-flex justify-content-around"><a class="btn float-xl-left" href=" ' . route('specialties.edit', [$row->id]) . ' ">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    $btn .= $delete;
                    $btn .= '</a></div>';
                    return $btn;
                })
                ->rawColumns(['Action'])
                ->make(true);
        }
        return view('admin.specialties.index', $params);
    }

    public function create()
    {
        if (Auth::guard('admin')->user()->role_id == 2) {
            return redirect()->route('specialties.index')->with('errors', 'This User Is Not Access Your Pages');
        } else {
            return view('admin.specialties.add');
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            $requestData = $request->all();

            if (isset($requestData['image']) && $requestData['image'] != 'undefined') {
                $files = $requestData['image'];
                $ext = $files->extension();
                $path = base_path('/storage/app/public/specialties/');
                $name = rand() . time() . "." . $ext;
                $files->move($path, $name);
                $requestData['image'] = $name;

            }
            $specialties = SpecialtieModel::updateOrCreate(['id' => $requestData['id']], $requestData);

            if (isset($specialties->id)) {
                return response()->json(array('status' => 200, 'success' => "Specialties Add Successfully"));
            } else {
                return response()->json(array('status' => 400, 'error' => "Specialties Not Added"));
            }

        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function edit($id)
    {
        $params['data'] = SpecialtieModel::where('id', $id)->first();
        return view('admin.specialties.edit', $params);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return response()->json(array('status' => 400, 'errors' => ("This User Is Not Access Your Pages")));
            } else {
                $requestData = $request->all();
                if (isset($requestData['image']) && $requestData['image'] != 'undefined') {

                    $files = $requestData['image'];
                    $ext = $files->extension();
                    $path = base_path('/storage/app/public/specialties/');
                    $name = rand() . time() . "." . $ext;
                    $files->move($path, $name);
                    $requestData['image'] = $name;
                    Storage::disk('public')->delete('specialties/' . $requestData['old_image']);

                } else {
                    $requestData['image'] = $requestData['old_image'];
                }
                $requestData = Arr::except($requestData, ['old_image']);

                $specialties = SpecialtieModel::updateOrCreate(['id' => $requestData['id']], $requestData);
                if (isset($specialties->id)) {
                    return response()->json(array('status' => 200, 'success' => "Specialtie Update Succesfully"));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Specialtie Not Updated"));
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
                return redirect()->route('specialties.index')->with('errors', 'This User Is Not Access Your Pages');
            } else {
                $data = SpecialtieModel::where('id', $id)->first();
                Storage::disk('public')->delete('specialties/' . $data->image);
                $data->delete();
                return redirect()->route('specialties.index')->with('success', "Specialties Delete Succesfully");
            }

        } catch (Exception $e) {
            return redirect()->route('specialties.index')->with($e);
        }

    }
}
