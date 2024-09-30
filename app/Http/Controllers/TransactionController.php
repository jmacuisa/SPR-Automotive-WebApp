<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
  public function index(Request $request)
  {
    if ($request->ajax()) {

      // Fetching parameters
      $draw = $request->input('draw');
      $start = $request->input('start');
      $length = $request->input('length');
      $searchValue = $request->input('search.value');
      $orderColumn = $request->input('order.0.column');
      $orderDir = $request->input('order.0.dir');

      // Get the column names for ordering
      $columns = [
        'user_id',
        'mechanic_id',
        'code',
        'client_name',
        'contact',
        'email',
        'address',
        'amount',
        'status'
      ];

      // Build the query and eager load user and mechanic
      $query = Transaction::with(['user', 'mechanic']);

      // Apply search filter if applicable
      if ($searchValue) {
        $query->where(function ($q) use ($searchValue) {
          $q->whereHas('user', function ($q) use ($searchValue) {
            $q->where('name', 'like', "%$searchValue%"); // Searching by user name
          })->orWhereHas('mechanic', function ($q) use ($searchValue) {
            $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%$searchValue%"]); // Search by full name of mechanic
          })
            ->orWhere('code', 'like', "%$searchValue%")
            ->orWhere('client_name', 'like', "%$searchValue%")
            ->orWhere('contact', 'like', "%$searchValue%")
            ->orWhere('email', 'like', "%$searchValue%")
            ->orWhere('address', 'like', "%$searchValue%")
            ->orWhere('amount', 'like', "%$searchValue%")
            ->orWhere('status', 'like', "%$searchValue%");
        });
      }

      // Get total records after filtering
      $filteredCount = $query->count();

      // Apply sorting
      if (isset($columns[$orderColumn])) {
        $query->orderBy($columns[$orderColumn], $orderDir);
      }

      // Apply pagination
      $transaction = $query->skip($start)->take($length)->get();

      // Get total records before filtering
      $totalCount = Transaction::count();

      // Prepare the response in the format DataTables expects, with related data
      return response()->json([
        'draw' => intval($draw),
        'recordsTotal' => intval($totalCount),
        'recordsFiltered' => intval($filteredCount),
        'data' => $transaction->map(function ($transaction) {
          // Concatenate mechanic's first, middle, and last name
          $mechanicFullName = $transaction->mechanic
            ? trim("{$transaction->mechanic->firstname} " . "{$transaction->mechanic->lastname}")
            : 'N/A';

          return [
            'processed_by' => $transaction->user->name ?? 'N/A',        // Get user name
            'mechanic' => $mechanicFullName,
            'code' => $transaction->code,
            'client_name' => $transaction->client_name,
            'contact' => $transaction->contact,
            'email' => $transaction->email,
            'address' => $transaction->address,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
            'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
          ];
        })
      ]);
    }

    return view('admin.transaction.transactions');
  }

  public function store(Request $request)
  {
    // Validate the request
    $validated = $request->validate([
      'user_id' => 'required|numeric',
      'mechanic_id' => 'required|numeric',
      'code' => 'required|string',
      'client_name' => 'required|string',
      'contact' => 'required|string',
      'email' => 'required|string',
      'address' => 'required|string',
      'amount' => 'required',
      'status' => 'required|in:1,2',
    ]);

    // Create the new Transaction
    $transaction = Transaction::create([
      'user_id' => $validated['user_id'],
      'mechanic_id' => $validated['mechanic_id'],
      'code' => $validated['code'],
      'client_name' => $validated['client_name'],
      'contact' => $validated['contact'],
      'email' => $validated['email'],
      'address' => $validated['address'],
      'amount' => $validated['amount'],
      'status' => $validated['status'] == '1' ? true : false, // Convert to boolean
    ]);

    return response()->json(['success' => true]);
  }

  public function update(Request $request, $id)
  {
    // Validate and update existing Transaction
    $validatedData = $request->validate([
      'user_id' => 'required|numeric',
      'mechanic_id' => 'required|numeric',
      'code' => 'required|string',
      'client_name' => 'required|string',
      'contact' => 'required|string',
      'email' => 'required|string',
      'address' => 'required|string',
      'amount' => 'required',
      'status' => 'required|in:1,2',
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->update($validatedData);

    return response()->json(['message' => 'Transaction updated successfully.']);
  }

  // app/Http/Controllers/TransactionController.php
  public function destroy($id)
  {
    $transaction = Transaction::findOrFail($id);
    $transaction->delete();

    return response()->json(['success' => true]);
  }
}
