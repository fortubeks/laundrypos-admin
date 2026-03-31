<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // List all customers, optionally filtered by user
    public function index(Request $request)
    {
        $query = Customer::query()->with('laundry');
        if ($request->filled('laundry_id')) {
            $query->where('laundry_id', $request->input('laundry_id'));
        }
        $customers = $query->latest()->paginate(15)->appends($request->query());
        $title     = 'All Customers';
        return view('material.customers.index', compact('customers', 'title'));
    }

    // Show customer details
    public function show($id)
    {
        $customer = Customer::with(['orders', 'laundry'])->findOrFail($id);
        return view('material.customers.show', compact('customer'));
    }
}
