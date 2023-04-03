<x-mail::message>
# Introduction

Your reservation is successful.
<h1>Details</h1>
<h2>Name: {{ $reservation->firstname
 }} {{ $reservation->lastname
 }}</h2>
 <h2>Table: {{ $reservation->table->name
}}</h2>
<h2>Guest: {{ $reservation->guest_no
}} </h2>
 <h2>Reservation date: {{ $reservation->res_date
}}</h2>

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks for choosing us,<br>
{{ config('app.name') }}
</x-mail::message>
