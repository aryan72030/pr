@extends('masterpage.layout')

@section('title')
    {{ __('Appointment Calendar') }}
@endsection

@section('mainConten')
    <section class="dash-container">
        <div class="dash-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h4 class="m-b-10">{{ __('Appointment Calendar') }}</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('appointment.index') }}">{{ __('Appointment') }}</a></li>
                                <li class="breadcrumb-item">{{ __('Calendar') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach($appointments as $appointment)
                    {
                        title: '{{ $appointment->service }} - {{ $appointment->users->name }}',
                        start: '{{ $appointment->appointment_date }}T{{ $appointment->start_time }}',
                        backgroundColor: '{{ $appointment->status == "confirmed" ? "#28a745" : ($appointment->status == "cancelled" ? "#dc3545" : "#ffc107") }}',
                        borderColor: '{{ $appointment->status == "confirmed" ? "#28a745" : ($appointment->status == "cancelled" ? "#dc3545" : "#ffc107") }}',
                        extendedProps: {
                            staff: '{{ $appointment->staff->name }}',
                            status: '{{ $appointment->status }}'
                        }
                    },
                    @endforeach
                ],
                eventClick: function(info) {
                    alert('Service: ' + info.event.title + '\nStaff: ' + info.event.extendedProps.staff + '\nStatus: ' + info.event.extendedProps.status);
                }
            });
            calendar.render();
        });
    </script>
@endsection
