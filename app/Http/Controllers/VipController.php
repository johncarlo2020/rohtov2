<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingDate;
use App\Models\BookingSlot;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VipController extends Controller
{
    public function index()
    {
        // 1. VIP users list for dropdown
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->whereIn('name', ['admin', 'superadmin', 'staff']);
        })->orderBy('fname')->get();

        // 2. Dates & Slots for form selection (Sept 30 to Oct 17)
        $bookingDates = BookingDate::with('slots')
            ->where('is_available', true)
            ->whereBetween('date', ['2026-09-30', '2026-10-17'])
            ->get();
        $bookingSlots = BookingSlot::with('bookingDate')->where('is_available', true)->get();

        // 3. Public Count Table (Grouped by Date and Time Slot)
        $publicCounts = BookingSlot::with(['bookingDate', 'bookings' => function ($q) {
            $q->where('is_vip', false);
        }])
        ->whereHas('bookingDate', function ($q) {
            $q->where('is_available', true);
        })
        ->get()
        ->map(function ($slot) {
            $publicPax = $slot->bookings->sum('pax');
            $vipPax = Booking::where('booking_slot_id', $slot->id)->where('is_vip', true)->sum('pax');
            return [
                'id' => $slot->id,
                'date' => $slot->bookingDate->display_date ?? 'N/A',
                'raw_date' => $slot->bookingDate->date ?? '',
                'time' => $slot->display_time,
                'capacity' => $slot->capacity,
                'public_pax' => $publicPax,
                'vip_pax' => $vipPax,
                'total_pax' => $publicPax + $vipPax,
                'remaining' => max(0, $slot->capacity - ($publicPax + $vipPax)),
            ];
        });

        // 4. VIP Bookings Table
        $vipBookings = Booking::with(['bookingDate', 'bookingSlot'])
            ->where('is_vip', true)
            ->latest()
            ->get();

        $vipGroups = \App\Services\VipGroupService::getVipGroups();

        // 5. List of fully booked slots for validation
        $fullSlots = BookingSlot::with('bookingDate')
            ->get()
            ->filter(function ($slot) {
                return $slot->capacity > 0 && $slot->booked_count >= $slot->capacity;
            })
            ->map(function ($slot) {
                return [
                    'date' => $slot->bookingDate->date ?? '',
                    'start_time' => substr($slot->start_time, 0, 5),
                ];
            })
            ->values();

        return view('admin.vip.index', compact('users', 'bookingDates', 'bookingSlots', 'publicCounts', 'vipBookings', 'vipGroups', 'fullSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'vip_name' => 'required|string|max:255',
            'booking_date_id' => 'required|exists:booking_dates,id',
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'pax' => 'required|integer|min:1|max:50',
        ]);

        $slot = BookingSlot::with('bookingDate')->findOrFail($request->booking_slot_id);
        $remaining = max(0, $slot->capacity - $slot->booked_count);

        if ($remaining <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['booking_slot_id' => 'The selected time slot is fully booked.']);
        }

        if ((int) $request->pax > $remaining) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['pax' => "Requested pax count ({$request->pax}) exceeds available capacity for this time slot ({$remaining} left out of {$slot->capacity})."]);
        }

        $customerName = 'VIP Guest';
        $customerEmail = 'vip@longchamp.com';
        $customerPhone = '-';

        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
            if ($user) {
                $customerName = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
                if (empty($customerName)) {
                    $customerName = $user->name ?? 'VIP Guest';
                }
                $customerEmail = $user->email;
                $customerPhone = $user->number ?? '-';
            }
        } elseif ($request->filled('vip_name')) {
            $customerName = $request->vip_name;
        }

        $refNo = 'VIP-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_date_id' => $request->booking_date_id,
            'booking_slot_id' => $request->booking_slot_id,
            'reference_no' => $refNo,
            'customer_name' => $customerName,
            'vip_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'venue' => 'LONGCHAMP POP UP STORE THE GARDENS MALL',
            'is_vip' => true,
            'pax' => (int) $request->pax,
            'status' => 'Not Yet Attended',
        ]);

        // Increment booked_count on the slot
        $slot->increment('booked_count', (int) $request->pax);

        // Log history
        \App\Services\HistoryLogService::log(
            'CREATE_VIP_BOOKING',
            "Created VIP booking ({$refNo}) for {$customerName} (pax: {$request->pax})",
            'Booking',
            $booking->id
        );

        return redirect()->back()->with('success', 'VIP Reservation created successfully!');
    }

    public function markAttended($id)
    {
        $booking = Booking::with('bookingDate')->findOrFail($id);

        if ($booking->bookingDate && !\Carbon\Carbon::parse($booking->bookingDate->date)->isToday()) {
            $formattedDate = $booking->bookingDate->display_date ?? $booking->bookingDate->date;
            return redirect()->back()->with('error', "Cannot mark attendance for a booking scheduled on {$formattedDate}. Attendance can only be marked on the actual booking date.");
        }

        $booking->status = 'Attended';
        $booking->attended_at = now();
        $booking->save();

        // Log history
        \App\Services\HistoryLogService::log(
            'MARK_ATTENDED',
            "Marked VIP {$booking->customer_name} ({$booking->reference_no}) as ATTENDED",
            'Booking',
            $booking->id
        );

        return redirect()->back()->with('success', 'VIP marked as Attended!');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->bookingSlot) {
            $booking->bookingSlot->decrement('booked_count', max(1, $booking->pax));
        }
        $booking->delete();

        // Log history
        \App\Services\HistoryLogService::log(
            'DELETE_VIP_BOOKING',
            "Deleted VIP booking ({$booking->reference_no}) for {$booking->customer_name}",
            'Booking',
            $booking->id
        );

        return redirect()->back()->with('success', 'VIP Booking deleted successfully.');
    }

    /**
     * Update or Create a VIP Group Preset.
     */
    public function updateGroupPreset(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'original_key' => 'nullable|string',
            'name' => 'required|string|max:255',
            'entries' => 'required|array|min:1',
            'entries.*.date' => 'required|date_format:Y-m-d',
            'entries.*.start_time' => 'required|string',
            'entries.*.end_time' => 'nullable|string',
            'entries.*.pax' => 'required|integer|min:1|max:200',
        ]);

        // Validation Check: Prevent modification if a date & time slot is fully booked or if new pax is less than already booked count
        foreach ($request->input('entries') as $entry) {
            $dateStr = trim($entry['date'] ?? '');
            $startTime = trim($entry['start_time'] ?? '');
            $pax = (int) ($entry['pax'] ?? 1);

            if (empty($dateStr) || empty($startTime)) continue;

            if (strlen($startTime) === 5) {
                $startTime .= ':00';
            }

            $bookingDate = \App\Models\BookingDate::where('date', $dateStr)->first();
            if ($bookingDate) {
                $slot = \App\Models\BookingSlot::where('booking_date_id', $bookingDate->id)
                    ->where('start_time', 'LIKE', substr($startTime, 0, 5) . '%')
                    ->first();

                if ($slot) {
                    $startFmt = \Carbon\Carbon::parse($startTime)->format('g:i A');
                    $dateFmt = \Carbon\Carbon::parse($dateStr)->format('M d, Y');

                    if ($slot->capacity > 0 && $slot->booked_count >= $slot->capacity) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['entries' => "Modification not allowed: The time slot {$startFmt} on {$dateFmt} is fully booked ({$slot->booked_count}/{$slot->capacity})."]);
                    }

                    if ($pax < $slot->booked_count) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['entries' => "Modification not allowed: The time slot {$startFmt} on {$dateFmt} already has {$slot->booked_count} bookings, exceeding requested capacity ({$pax})."]);
                    }
                }
            }
        }

        \App\Services\VipGroupService::updateVipGroup(
            $request->input('original_key'),
            $request->input('name'),
            $request->input('entries')
        );

        // Ensure slots exist for all dates & times defined in the entries
        foreach ($request->input('entries') as $entry) {
            $dateStr = trim($entry['date']);
            $startTime = trim($entry['start_time']);
            $endTime = trim($entry['end_time'] ?? '');
            $pax = (int) ($entry['pax'] ?? 1);

            if (empty($dateStr) || empty($startTime)) continue;

            if (strlen($startTime) === 5) {
                $startTime .= ':00';
            }

            if (empty($endTime)) {
                $endTime = \Carbon\Carbon::parse($startTime)->addHour()->format('H:i:s');
            } elseif (strlen($endTime) === 5) {
                $endTime .= ':00';
            }

            $bookingDate = \App\Models\BookingDate::firstOrCreate(
                ['date' => $dateStr],
                ['is_available' => true]
            );

            $slot = \App\Models\BookingSlot::where('booking_date_id', $bookingDate->id)
                ->where('start_time', 'LIKE', substr($startTime, 0, 5) . '%')
                ->first();

            if ($slot) {
                $slot->update([
                    'capacity' => max($slot->capacity, $pax),
                    'end_time' => $endTime
                ]);
            } else {
                \App\Models\BookingSlot::create([
                    'booking_date_id' => $bookingDate->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'capacity' => $pax,
                    'booked_count' => 0,
                    'is_available' => true,
                ]);
            }
        }

        \App\Services\HistoryLogService::log(
            'UPDATE_VIP_GROUP_PRESET',
            "Updated VIP Group Preset '{$request->input('name')}'",
            'VipGroup',
            0
        );

        return redirect()->back()->with('success', "VIP Group Preset '{$request->input('name')}' updated successfully.");
    }

    /**
     * Delete a VIP Group Preset.
     */
    public function deleteGroupPreset(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->input('key');
        \App\Services\VipGroupService::deleteVipGroup($key);

        \App\Services\HistoryLogService::log(
            'DELETE_VIP_GROUP_PRESET',
            "Deleted VIP Group Preset '{$key}'",
            'VipGroup',
            0
        );

        return redirect()->back()->with('success', "VIP Group Preset '{$key}' deleted successfully.");
    }
}
