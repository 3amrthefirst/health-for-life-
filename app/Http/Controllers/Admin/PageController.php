<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use DataTables;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    public function index(Request $request)
    {
        $params['data'] = [];
        if ($request->ajax()) {
            $data = Page::select('*')->get();

            return DataTables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a class="btn" href="' . route('page.edit', [$row->id]) . '">';
                    $btn .= '<img src="' . url('/public') . '/assets/imgs/edit.png" />';
                    $btn .= '</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.page.index', $params);
    }

    public function edit($id)
    {
        $params['data'] = Page::where('id', $id)->first();

        if ($params['data'] != null) {
            return view('admin.page.edit', $params);
        } else {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        try {
            if (Auth::guard('admin')->user()->role_id == 2) {
                return redirect()->route('Setting')->with('errors', 'This User Is Not Access Your Pages');
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required',
                    'description' => 'required',
                ]);

                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                } else {

                    $page = Page::where('id', $request->id)->first();

                    if (isset($page->id)) {

                        $page->title = $request->title;
                        $page->description = $request->description;
                        $page->status = '1';

                        if ($page->save()) {
                            return response()->json(array('status' => 200, 'success' => __('label.Data Edit Successfully')));
                        } else {
                            return response()->json(array('status' => 400, 'errors' => __('label.Data Not Updated')));
                        }
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

}
