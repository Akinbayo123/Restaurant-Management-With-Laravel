<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Table;
use App\Enums\TableStatus;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ReservationSuccess;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function stepOne(Request $request)
    {
        $reservation = $request->session()->get('reservation');
        $min_date = Carbon::today();
        $max_date = Carbon::now()->addWeek();
        if ($reservation) {
            $res_date =  Carbon::parse($reservation->res_date);
        } else {
            $res_date = '';
        }

        return view('reservations.step-one', compact('reservation', 'min_date', ('res_date') ?? '', 'max_date'));
    }


    public function storeStepOne(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'email' => ['required', 'email'],
            'res_date' => ['required', 'date', new DateBetween, new TimeBetween],
            'phone' => ['required'],
            'guest_no' => ['required'],
        ]);

        if (empty($request->session()->get('reservation'))) {
            $reservation = new Reservation();
            $reservation->fill($validated);
            $request->session()->put('reservation', $reservation);
        } else {
            $reservation = $request->session()->get('reservation');
            $reservation->fill($validated);
            $request->session()->put('reservation', $reservation);
        }

        return to_route('reservations.step.two');
    }
    public function stepTwo(Request $request)
    {
        $reservation = $request->session()->get('reservation');

        $res_table_ids = Reservation::orderBy('res_date')->get()->filter(function ($value) use ($reservation) {
            $dates = Carbon::parse($reservation->res_date)->format('Y:m:d');

            $date = Carbon::parse($value->res_date)->format('Y:m:d');

            return ($date) == ($dates);
        })->pluck('table_id');


        $tables = Table::where('status', 'avaliable')
            ->where('guest_no', '>=', $reservation->guest_no)
            ->whereNotIn('id', $res_table_ids)->get();
        //dd($tables);
        return view('reservations.step-two', compact('reservation', 'tables'));
    }

    public function storeStepTwo(Request $request)
    {
        $validated = $request->validate([
            'table_id' => ['required']
        ]);
        $reservation = $request->session()->get('reservation');


        $reservation->fill($validated);
        $reservation->save();
        $user_email = $reservation->email;
        Mail::to($user_email)->send(new ReservationSuccess($reservation));
        $request->session()->forget('reservation');

        return to_route('thankyou');
    }
}
