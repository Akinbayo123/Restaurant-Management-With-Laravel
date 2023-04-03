<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Table;
use App\Enums\TableStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationStoreRequest;

class ReservationController extends Controller
{
    //
    public function reservation()
    {
        
        $reservations = Reservation::all();
       

        return view("admin.reservations.index", compact('reservations'));
    }

    public function create()
    {

        $tables = Table::where('status', TableStatus::Avalaiable)->get();
        return view("admin.reservations.create", compact('tables'));
    }
    public function store(ReservationStoreRequest $request)
    {
        // $request_date = Carbon::parse($request->res_date);
        // dd($request_date->toDateString());
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_no > $table->guest_no) {
            return back()->with('warning', 'Please choose the table base on guests.');
        }
        $request_date = Carbon::parse($request->res_date);
        foreach ($table->reservations as $res) {
            $dates = Carbon::parse($res->res_date);
            if ($dates->toDateString() == $request_date->toDateString()) {
                return back()->with('warning', 'This table is reserved for this date.');
            }
        }

        Reservation::create($request->validated());

        return to_route('reservation');
    }
    public function destroy(Reservation $res)
    {
        $res->delete();
        return back()->with('success', 'Successfully deleted.');
    }
    public function edit(Reservation $reservation)
    {
        $date = Carbon::parse($reservation->res_date);
        $tables = Table::where('status', TableStatus::Avalaiable)->get();
        return view('admin.reservations.edit', compact('reservation', 'tables', 'date'));
    }

    public function update(ReservationStoreRequest $request, Reservation $reservation)
    {
        $table = Table::findOrFail($request->table_id);
        if ($request->guest_no > $table->guest_no) {
            return back()->with('warning', 'Please choose the table base on guests.');
        }
        $request_date = Carbon::parse($request->res_date);
        $reservations = $table->reservations()->where('id', '!=', $reservation->id)->get();
        foreach ($reservations as $res) {
            $dates = Carbon::parse($res->res_date);
            if ($dates->res_date->toDateString() == $request_date->toDateString()) {
                return back()->with('warning', 'This table is reserved for this date.');
            }
        }

        $reservation->update($request->validated());
        return to_route('reservation')->with('success', 'Reservation updated successfully.');
    }
}
