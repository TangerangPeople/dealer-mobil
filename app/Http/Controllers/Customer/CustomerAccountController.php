<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DealerSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer.account', [
            'customer' => $request->user('customer'),
            'settings' => DealerSetting::query()->first(),
        ]);
    }
}
