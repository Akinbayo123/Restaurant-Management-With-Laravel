<?php

namespace App\Http\Controllers\Admin;

use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TableStoreRequest;

class TableController extends Controller
{
    //
    public function table(Table $table)
    {
        $tables = Table::all();
        return view("admin.tables.index", compact('tables'));
    }
    public function create()
    {
        return view("admin.tables.create");
    }
    public function store(TableStoreRequest $request)
    {
        Table::create([
            'name' => $request->name,
            'guest_no' => $request->guest_no,
            'status' => $request->status,
            'location' => $request->location
        ]);
        return redirect('admin/tables');
    }
    public function edit(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }
    public function update(TableStoreRequest $request, Table $table)
    {
       $table->update($request->validated());

        return to_route('table');
    }
    public function destroy(Table $table)
    {
        $table->delete();
        return back();
    }
}
