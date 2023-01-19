<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use URL;
use App\Models\Page;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $data = auth()->guard('admin')->user();

            if ($data->role_id == 2) {
                $this->middleware('checkadmin');
            }

            return response()->json(array('status' => 200, 'success' => "Admin Login Succesfully"));
        } else {
            return response()->json(array('status' => 400, 'errors' => "Your Email And Password Wrong"));
        }
    }

    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('Login');
    }

    public function Page()
    {
        $currentURL = URL::current();

        if (str_contains($currentURL, 'about-us')) {
            $data = Page::select('*')->where('page_name', 'about-us')->first();
            return view('page', ['result' => $data]);
        } elseif (str_contains($currentURL, 'privacy-policy')) {
            $data = Page::select('*')->where('page_name', 'privacy-policy')->first();
            return view('page', ['result' => $data]);
        } elseif (str_contains($currentURL, 'terms-and-conditions')) {
            $data = Page::select('*')->where('page_name', 'terms-and-conditions')->first();
            return view('page', ['result' => $data]);
        } else {
            abort(404);
        }
    }
}
