<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource with FullCalendar events & metrics.
     */
    public function index()
    {
        $bookings = Booking::with(['bookingDate', 'bookingSlot'])
            ->orderBy('id', 'desc')
            ->get();

        $totalBookings = $bookings->count();
        $attendedCount = $bookings->filter(function ($b) {
            return $b->status === 'attended' || $b->status === 'completed' || !is_null($b->attended_at);
        })->count();
        $confirmedCount = $bookings->where('status', 'confirmed')->count();
        $attendanceRate = $totalBookings > 0 ? round(($attendedCount / $totalBookings) * 100, 1) : 0;

        // Map events for FullCalendar JS
        $calendarEvents = $bookings->map(function ($b) {
            $dateStr = ($b->bookingDate && $b->bookingDate->date) 
                ? Carbon::parse($b->bookingDate->date)->format('Y-m-d') 
                : now()->format('Y-m-d');

            $timeStr = ($b->bookingSlot && $b->bookingSlot->start_time)
                ? Carbon::parse($b->bookingSlot->start_time)->format('H:i:s')
                : '10:00:00';

            $startIso = $dateStr . 'T' . $timeStr;
            $isAttended = ($b->status === 'attended' || $b->status === 'completed' || !is_null($b->attended_at));

            $bgColor = $isAttended ? '#10b981' : ($b->status === 'cancelled' ? '#ef4444' : '#e86034');

            $timeSlotText = ($b->bookingSlot && $b->bookingSlot->display_time) ? ' (' . $b->bookingSlot->display_time . ')' : '';

            return [
                'id' => $b->id,
                'title' => $b->customer_name . $timeSlotText,
                'start' => $startIso,
                'backgroundColor' => $bgColor,
                'borderColor' => $bgColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'ref' => $b->reference_no,
                    'name' => $b->customer_name,
                    'email' => $b->customer_email,
                    'phone' => $b->customer_phone,
                    'date' => $b->bookingDate->display_date ?? 'N/A',
                    'time' => $b->bookingSlot->display_time ?? 'N/A',
                    'venue' => $b->venue ?? 'LONGCHAMP POP UP STORE THE GARDENS MALL',
                    'status' => $isAttended ? 'ATTENDED' : strtoupper($b->status),
                    'attended_at' => $b->attended_at ? Carbon::parse($b->attended_at)->format('M d, Y h:i A') : 'Not yet',
                    'is_attended' => $isAttended
                ]
            ];
        });

        $firstBooking = $bookings->filter(function ($b) {
            return $b->bookingDate && $b->bookingDate->date;
        })->first();

        $initialCalendarDate = $firstBooking 
            ? Carbon::parse($firstBooking->bookingDate->date)->format('Y-m-d')
            : now()->format('Y-m-d');

        // Prepare Matrix Schedule Grid Data (Reference Image Layout)
        $matrixDates = BookingDate::where('is_available', true)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($d, $index) {
                $cDate = Carbon::parse($d->date);
                return [
                    'id' => $d->id,
                    'code' => 'D' . ($index + 1),
                    'display_day' => $cDate->format('j-M'),
                    'display_dow' => $cDate->format('D'),
                    'raw_date' => $cDate->format('Y-m-d'),
                ];
            });

        $standardTimeSlots = [
            '11am-12pm' => ['start' => '11:00:00', 'end' => '12:00:00'],
            '12pm-1pm'  => ['start' => '12:00:00', 'end' => '13:00:00'],
            '1pm-2pm'   => ['start' => '13:00:00', 'end' => '14:00:00'],
            '2pm-3pm'   => ['start' => '14:00:00', 'end' => '15:00:00'],
            '3pm-4pm'   => ['start' => '15:00:00', 'end' => '16:00:00'],
            '4pm-5pm'   => ['start' => '16:00:00', 'end' => '17:00:00'],
            '5pm-6pm'   => ['start' => '17:00:00', 'end' => '18:00:00'],
            '6pm-7pm'   => ['start' => '18:00:00', 'end' => '19:00:00'],
            '7pm-8pm'   => ['start' => '19:00:00', 'end' => '20:00:00'],
            '8pm-9pm'   => ['start' => '20:00:00', 'end' => '21:00:00'],
            '9pm-10pm'  => ['start' => '21:00:00', 'end' => '22:00:00'],
        ];

        $matrixCells = [];
        foreach ($standardTimeSlots as $label => $times) {
            foreach ($matrixDates as $d) {
                $slot = BookingSlot::where('booking_date_id', $d['id'])
                    ->where('start_time', 'like', substr($times['start'], 0, 2) . ':%')
                    ->first();

                if ($slot) {
                    $slotBookings = Booking::where('booking_slot_id', $slot->id)->get();
                    $vipBookings = $slotBookings->where('is_vip', true);
                    $publicBookings = $slotBookings->where('is_vip', false);

                    $publicPax = $publicBookings->sum('pax');
                    if ($publicPax == 0 && $publicBookings->count() > 0) {
                        $publicPax = $publicBookings->count();
                    }

                    $vipPax = $vipBookings->sum('pax');
                    if ($vipPax == 0 && $vipBookings->count() > 0) {
                        $vipPax = $vipBookings->count();
                    }

                    $firstVip = $vipBookings->first();
                    $vipName = $firstVip ? ($firstVip->vip_name ?: $firstVip->customer_name) : 'VIP';

                    $matrixCells[$label][$d['id']] = [
                        'slot_id' => $slot->id,
                        'date_id' => $d['id'],
                        'public_count' => $publicPax,
                        'vip_count' => $vipPax,
                        'vip_name' => $vipName,
                        'total_count' => $publicPax + $vipPax,
                        'bookings' => $slotBookings->map(function($b) {
                            return [
                                'id' => $b->id,
                                'name' => $b->customer_name,
                                'email' => $b->customer_email,
                                'phone' => $b->customer_phone,
                                'pax' => $b->pax ?? 1,
                                'is_vip' => $b->is_vip,
                                'status' => $b->computed_status,
                                'ref' => $b->reference_no,
                                'raw_date' => $b->bookingDate ? \Carbon\Carbon::parse($b->bookingDate->date)->format('Y-m-d') : null,
                            ];
                        })->values()
                    ];
                } else {
                    $matrixCells[$label][$d['id']] = null;
                }
            }
        }

        // Walk-in Customer Dropdown Data (Formatted same as front page: Oct 2 to Oct 17)
        $walkinDates = BookingDate::where('is_available', true)
            ->whereBetween('date', ['2026-10-02', '2026-10-17'])
            ->orderBy('date', 'asc')
            ->get();

        $walkinSlots = BookingSlot::with('bookingDate')
            ->where('is_available', true)
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($s) {
                $startTime = Carbon::parse($s->start_time)->format('g:iA');
                $endTime = Carbon::parse($s->end_time)->format('g:iA');
                return [
                    'id' => $s->id,
                    'booking_date_id' => $s->booking_date_id,
                    'time_label' => strtoupper($startTime . ' - ' . $endTime),
                    'display_time' => strtoupper($startTime),
                    'capacity' => $s->capacity,
                    'booked_count' => $s->booked_count,
                    'remaining' => max(0, $s->capacity - $s->booked_count),
                ];
            });

        $bookingDates = BookingDate::orderBy('date', 'asc')->get();

        return view('admin.booking.index', compact(
            'bookings',
            'calendarEvents',
            'totalBookings',
            'attendedCount',
            'confirmedCount',
            'attendanceRate',
            'initialCalendarDate',
            'matrixDates',
            'standardTimeSlots',
            'matrixCells',
            'walkinDates',
            'walkinSlots',
            'bookingDates'
        ));
    }

    /**
     * Mark a booking attendance state (toggle or set attended)
     */
    public function markAttended($id)
    {
        $booking = Booking::with('bookingDate')->findOrFail($id);

        $isAlreadyAttended = ($booking->status === 'attended' || !is_null($booking->attended_at));
        $isToday = $booking->bookingDate && Carbon::parse($booking->bookingDate->date)->isToday();

        if (!$isAlreadyAttended && !$isToday) {
            $formattedDate = $booking->bookingDate->display_date ?? $booking->bookingDate->date;
            $message = "Cannot mark attendance for a booking scheduled on {$formattedDate}. Attendance can only be marked on the actual booking date.";
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        if ($isAlreadyAttended) {
            $booking->status = 'confirmed';
            $booking->attended_at = null;
            $message = 'Booking marked as NOT ATTENDED.';
        } else {
            $booking->status = 'attended';
            $booking->attended_at = now();
            $message = 'Booking verified & marked as ATTENDED!';
        }

        $booking->save();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $booking->status,
                'attended_at' => $booking->attended_at ? Carbon::parse($booking->attended_at)->format('M d, Y h:i A') : null
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Lookup booking details by QR Code message, Reference No, or Email
     */
    public function lookup(Request $request)
    {
        $queryStr = trim($request->input('qrCodeMessage', $request->input('query', '')));

        if (!$queryStr) {
            return response()->json(['status' => 'error', 'message' => 'Please scan a QR code or enter an email / reference number.'], 400);
        }

        // Handle URL inputs or raw reference codes
        $refNo = $queryStr;
        if (filter_var($queryStr, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($queryStr);
            parse_str($parsedUrl['query'] ?? '', $queryParams);
            if (!empty($queryParams['ref'])) {
                $refNo = $queryParams['ref'];
            } elseif (!empty($queryParams['id'])) {
                $refNo = $queryParams['id'];
            }
        }

        // 1. Search by Booking reference_no, customer_email, or customer_phone
        $booking = Booking::with(['bookingDate', 'bookingSlot'])
            ->where('reference_no', $refNo)
            ->orWhere('customer_email', $queryStr)
            ->orWhere('customer_phone', $queryStr)
            ->first();

        // 2. Fallback search via User model
        if (!$booking) {
            $user = User::where('email', $queryStr)
                ->orWhere('number', $queryStr)
                ->orWhere('id', $queryStr)
                ->first();

            if ($user) {
                $booking = Booking::with(['bookingDate', 'bookingSlot'])
                    ->where('customer_email', $user->email)
                    ->orWhere('customer_phone', $user->number)
                    ->latest()
                    ->first();
            }
        }

        if (!$booking) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'No booking found matching "' . $queryStr . '".'
            ], 404);
        }

        $computedStatus = $booking->computed_status; // 'Attended', 'Missed', or 'Not Yet Attended'

        return response()->json([
            'status' => 'success',
            'booking' => [
                'id' => $booking->id,
                'name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
                'venue' => $booking->venue ?? 'LONGCHAMP POP UP STORE',
                'pax' => $booking->pax ?? 1,
                'date' => $dateStr,
                'time' => $timeStr,
                'status' => $isAttended ? 'Attended' : 'Not Yet Attended',
                'attended_at' => $booking->attended_at ? Carbon::parse($booking->attended_at)->format('M d, Y h:i A') : null,
                'is_attended' => $isAttended,
            ]
        ]);
    }

    /**
     * Confirm attendance via scanner endpoint
     */
    public function confirmAttendanceByScan(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $refNo = $request->input('reference_no');

        $booking = Booking::with('bookingDate')->where('id', $bookingId)->orWhere('reference_no', $refNo)->first();

        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking record not found.'], 404);
        }

        if ($booking->status === 'attended' || $booking->status === 'completed' || !is_null($booking->attended_at)) {
            return response()->json([
                'status' => 'info',
                'message' => 'Customer is already marked as Attended.',
                'booking' => [
                    'id' => $booking->id,
                    'name' => $booking->customer_name,
                    'ref' => $booking->reference_no,
                    'status' => 'Attended',
                    'attended_at' => Carbon::parse($booking->attended_at)->format('M d, Y h:i A'),
                ]
            ]);
        }

        if ($booking->bookingDate && !Carbon::parse($booking->bookingDate->date)->isToday()) {
            $formattedDate = $booking->bookingDate->display_date ?? $booking->bookingDate->date;
            return response()->json([
                'status' => 'error',
                'message' => "Cannot mark attendance today. This booking is scheduled for {$formattedDate}."
            ], 422);
        }

        $booking->status = 'Attended';
        $booking->attended_at = now();
        $booking->save();

        \App\Services\HistoryLogService::log(
            'MARK_ATTENDED',
            "Marked ATTENDED via scanner for booking {$booking->reference_no} ({$booking->customer_name})",
            'Booking',
            $booking->id
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance confirmed! ' . $booking->customer_name . ' marked as Attended.',
            'booking' => [
                'id' => $booking->id,
                'name' => $booking->customer_name,
                'ref' => $booking->reference_no,
                'status' => 'Attended',
                'attended_at' => now()->format('M d, Y h:i A'),
            ]
        ]);
    }

    /**
     * Store walk-in customer booking created from Admin panel (No OTP required)
     */
    public function storeWalkin(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'booking_date_id' => 'required|exists:booking_dates,id',
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'pax' => 'nullable|integer|min:1|max:20',
            'mark_attended' => 'nullable|boolean',
        ], [
            'email.unique' => 'This email address is already registered in the system.',
        ]);

        $email = strtolower(trim($request->email));
        $pax = (int) $request->input('pax', 1);

        // Check if an active booking already exists under this email address
        $existingBooking = Booking::where('customer_email', $email)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($existingBooking) {
            throw ValidationException::withMessages([
                'email' => ['This email address already has an active booking registered.']
            ]);
        }

        // Verify remaining slot capacity
        $slot = BookingSlot::find($request->booking_slot_id);
        if (!$slot) {
            throw ValidationException::withMessages([
                'booking_slot_id' => ['The selected time slot does not exist.']
            ]);
        }

        $remaining = max(0, $slot->capacity - $slot->booked_count);
        if ($remaining <= 0) {
            throw ValidationException::withMessages([
                'booking_slot_id' => ['The selected time slot is fully booked.']
            ]);
        }

        if ($pax > $remaining) {
            throw ValidationException::withMessages([
                'pax' => ["Requested pax count ({$pax}) exceeds available capacity for this time slot ({$remaining} left)."]
            ]);
        }

        $fullName = trim($request->fname . ' ' . $request->lname);
        $phone = $request->phone ?? '-';

        // 1. Create User without OTP requirement
        $user = User::create([
            'title' => $request->title,
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $email,
            'number' => $phone,
            'country' => 'Malaysia',
            'preferred_contact' => 'Email',
            'communication_consent' => true,
            'marketing' => true,
            'otp_verified' => 1,
            'created_at' => now(),
            'last_login_at' => now(),
            'password' => Hash::make('password'),
        ]);
        
        $clientRole = \Spatie\Permission\Models\Role::where('name', 'client')->first();
        if ($clientRole) {
            $user->assignRole($clientRole);
        }

        // 2. Generate unique reference code for walk-in booking
        $refNo = 'WK-' . strtoupper(Str::random(6));

        $bookingDateObj = BookingDate::find($request->booking_date_id);
        $isToday = $bookingDateObj && Carbon::parse($bookingDateObj->date)->isToday();

        $markAttended = ($request->has('mark_attended') || $request->input('mark_attended') == 1) && $isToday;

        $booking = Booking::create([
            'booking_date_id' => $request->booking_date_id,
            'booking_slot_id' => $request->booking_slot_id,
            'reference_no' => $refNo,
            'customer_name' => $fullName,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'venue' => 'LONGCHAMP POP UP STORE THE GARDENS MALL',
            'is_vip' => false,
            'pax' => $pax,
            'status' => $markAttended ? 'Attended' : 'Not Yet Attended',
            'attended_at' => $markAttended ? now() : null,
        ]);

        // Increment slot capacity count
        $slot->increment('booked_count', $pax);

        // Log history
        \App\Services\HistoryLogService::log(
            'CREATE_WALKIN_BOOKING',
            "Registered walk-in booking ({$refNo}) for customer {$fullName} ({$email})",
            'Booking',
            $booking->id
        );

        return redirect()->back()->with('success', 'Walk-in booking created successfully! Reference: ' . $refNo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $refNo = $booking->reference_no;
        $name = $booking->customer_name;
        $booking->delete();

        \App\Services\HistoryLogService::log(
            'DELETE_BOOKING',
            "Deleted booking ({$refNo}) for {$name}",
            'Booking',
            $id
        );

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully.']);
        }

        return redirect()
            ->route('bookings')
            ->with('success', 'Booking deleted successfully.');
    }
}
