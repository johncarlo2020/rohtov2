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

        // 6. Public Schedule & Date Availability breakdown
        $eventDates = [];
        $startDate = \Carbon\Carbon::parse('2026-09-30');
        $maxDbDate = BookingDate::max('date');
        if ($maxDbDate instanceof \Carbon\Carbon || $maxDbDate instanceof \DateTimeInterface) {
            $maxDbDate = $maxDbDate->format('Y-m-d');
        }
        $endDateStr = ($maxDbDate && $maxDbDate > '2026-10-17') ? (string)$maxDbDate : '2026-10-17';
        $endDate = \Carbon\Carbon::parse($endDateStr);

        $dbDates = BookingDate::with(['slots' => function ($q) {
            $q->orderBy('start_time');
        }])
        ->whereBetween('date', ['2026-09-30', $endDateStr])
        ->get()
        ->keyBy(function ($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
        });

        $curr = $startDate->copy();
        while ($curr->lte($endDate)) {
            $dStr = $curr->format('Y-m-d');
            $bDate = $dbDates->get($dStr);

            $isAvailable = $bDate ? (bool) $bDate->is_available : true;
            $closedByDefault = in_array($dStr, ['2026-10-04', '2026-10-05', '2026-10-06', '2026-10-11', '2026-10-12', '2026-10-18']);
            if (!$bDate && $closedByDefault) {
                $isAvailable = false;
            }

            $slotsList = [];
            if ($bDate && $bDate->relationLoaded('slots')) {
                foreach ($bDate->slots as $s) {
                    $slotsList[] = [
                        'id' => $s->id,
                        'start_time' => substr($s->start_time, 0, 5),
                        'end_time' => substr($s->end_time, 0, 5),
                        'display_time' => $s->display_time,
                        'capacity' => $s->capacity,
                        'booked_count' => $s->booked_count,
                    ];
                }
            }

            $eventDates[] = [
                'date' => $dStr,
                'display_date' => strtoupper($curr->format('D')) . ', ' . $curr->format('j M Y'),
                'is_available' => $isAvailable,
                'slots' => $slotsList,
            ];

            $curr->addDay();
        }

        return view('admin.vip.index', compact('users', 'bookingDates', 'bookingSlots', 'publicCounts', 'vipBookings', 'vipGroups', 'fullSlots', 'eventDates'));
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
        if (auth()->check() && auth()->user()->hasRole('staff')) {
            return redirect()->back()->with('error', 'Unauthorized action. Staff members cannot delete VIP bookings.');
        }

        $booking = Booking::with('bookingSlot')->findOrFail($id);
        if ($booking->bookingSlot) {
            $decrementPax = max(1, (int)($booking->pax ?? 1));
            $newCount = max(0, $booking->bookingSlot->booked_count - $decrementPax);
            $booking->bookingSlot->update(['booked_count' => $newCount]);
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
        if (auth()->check() && auth()->user()->hasRole('staff')) {
            return redirect()->back()->with('error', 'Unauthorized action. Staff members cannot modify VIP group presets.');
        }

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

        $originalKey = $request->input('original_key');
        $oldGroups = \App\Services\VipGroupService::getVipGroups();

        if (empty($originalKey) && isset($oldGroups[$request->input('name')])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['name' => "VIP Group Preset '{$request->input('name')}' already exists! Duplicate preset creation is not allowed."]);
        }

        $oldGroup = (!empty($originalKey) && isset($oldGroups[$originalKey])) ? $oldGroups[$originalKey] : null;

        $oldSlots = [];
        if ($oldGroup && isset($oldGroup['schedules'])) {
            foreach ($oldGroup['schedules'] as $dateStr => $slots) {
                foreach ($slots as $s) {
                    $oldSlots[] = [
                        'date' => $dateStr,
                        'start_time' => substr($s['start_time'], 0, 5),
                    ];
                }
            }
        }

        \App\Services\VipGroupService::updateVipGroup(
            $request->input('original_key'),
            $request->input('name'),
            $request->input('entries')
        );

        // Track new slots submitted in this request
        $newSlots = [];

        // Ensure slots exist for all dates & times defined in the entries
        foreach ($request->input('entries') as $entry) {
            $dateStr = trim($entry['date']);
            $startTime = trim($entry['start_time']);
            $endTime = trim($entry['end_time'] ?? '');
            $pax = (int) ($entry['pax'] ?? 1);

            if (empty($dateStr) || empty($startTime)) continue;

            $newSlots[] = [
                'date' => $dateStr,
                'start_time' => substr($startTime, 0, 5),
            ];

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

            // 1. Try exact start_time match
            $slot = \App\Models\BookingSlot::where('booking_date_id', $bookingDate->id)
                ->where('start_time', 'LIKE', substr($startTime, 0, 5) . '%')
                ->first();

            // 2. Fallback: check if a slot covering this time range exists
            if (!$slot) {
                $slot = \App\Models\BookingSlot::where('booking_date_id', $bookingDate->id)
                    ->where('start_time', '<=', $startTime)
                    ->where('end_time', '>=', $endTime)
                    ->first();
            }

            if ($slot) {
                $targetCapacity = max($pax, $slot->booked_count);
                $updateData = [];

                if ($slot->capacity !== $targetCapacity) {
                    $updateData['capacity'] = $targetCapacity;
                }

                // Only adjust end_time if start_time matches
                if (substr($slot->start_time, 0, 5) === substr($startTime, 0, 5) && substr($slot->end_time, 0, 5) !== substr($endTime, 0, 5)) {
                    $updateData['end_time'] = $endTime;
                }

                if (!empty($updateData)) {
                    $slot->update($updateData);
                }
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

        // Identify slots removed from this preset and clean up unbooked orphan DB records
        $removedSlots = [];
        foreach ($oldSlots as $old) {
            $stillExists = false;
            foreach ($newSlots as $new) {
                if ($new['date'] === $old['date'] && $new['start_time'] === $old['start_time']) {
                    $stillExists = true;
                    break;
                }
            }
            if (!$stillExists) {
                $removedSlots[] = $old;
            }
        }
        $this->cleanupOrphanSlots($removedSlots);

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
        if (auth()->check() && auth()->user()->hasRole('staff')) {
            return redirect()->back()->with('error', 'Unauthorized action. Staff members cannot delete VIP group presets.');
        }

        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->input('key');
        $allGroups = \App\Services\VipGroupService::getVipGroups();
        $targetGroup = $allGroups[$key] ?? null;

        $removedSlots = [];
        if ($targetGroup && isset($targetGroup['schedules'])) {
            foreach ($targetGroup['schedules'] as $dateStr => $slots) {
                foreach ($slots as $s) {
                    $removedSlots[] = [
                        'date' => $dateStr,
                        'start_time' => substr($s['start_time'], 0, 5),
                    ];
                }
            }
        }

        \App\Services\VipGroupService::deleteVipGroup($key);

        $this->cleanupOrphanSlots($removedSlots);

        \App\Services\HistoryLogService::log(
            'DELETE_VIP_GROUP_PRESET',
            "Deleted VIP Group Preset '{$key}'",
            'VipGroup',
            0
        );

        return redirect()->back()->with('success', "VIP Group Preset '{$key}' deleted successfully.");
    }

    /**
     * Toggle date availability (Block / Unblock Date).
     */
    public function toggleDateAvailability(\Illuminate\Http\Request $request)
    {
        if (auth()->check() && auth()->user()->hasRole('staff')) {
            return redirect()->back()->with('error', 'Unauthorized action. Staff members cannot block or unblock event dates.');
        }

        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'is_available' => 'required|boolean',
        ]);

        $dateStr = $request->input('date');
        $isAvailable = (bool) $request->input('is_available');

        $bookingDate = BookingDate::firstOrCreate(
            ['date' => $dateStr],
            ['is_available' => true]
        );

        $bookingDate->is_available = $isAvailable;
        $bookingDate->save();

        $statusText = $isAvailable ? 'UNBLOCKED (OPEN)' : 'BLOCKED (CLOSED)';

        \App\Services\HistoryLogService::log(
            'TOGGLE_DATE_AVAILABILITY',
            "Set date {$dateStr} availability status to {$statusText}",
            'BookingDate',
            $bookingDate->id
        );

        return redirect()->back()->with('success', "Date {$dateStr} is now {$statusText} for bookings.");
    }

    /**
     * Update public schedule slots and Pax capacity for a date.
     */
    public function updatePublicSlots(\Illuminate\Http\Request $request)
    {
        if (auth()->check() && auth()->user()->hasRole('staff')) {
            return redirect()->back()->with('error', 'Unauthorized action. Staff members cannot modify public schedule slots.');
        }

        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'is_available' => 'nullable|boolean',
            'is_create' => 'nullable|boolean',
            'entries' => 'nullable|array',
            'entries.*.start_time' => 'required|string',
            'entries.*.end_time' => 'nullable|string',
            'entries.*.pax' => 'required|integer|min:1|max:200',
        ]);

        $dateStr = $request->input('date');
        $isAvailable = $request->has('is_available') ? (bool) $request->input('is_available') : true;
        $entries = $request->input('entries', []);

        // Duplicate Check: Prevent creating an event date if it already exists in the system with configured slots
        if ($request->boolean('is_create') || $request->input('is_create') === '1') {
            $existingSlots = BookingSlot::whereHas('bookingDate', function ($q) use ($dateStr) {
                $q->where('date', $dateStr);
            })->exists();

            if ($existingSlots) {
                $formattedDate = \Carbon\Carbon::parse($dateStr)->format('M d, Y');
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['date' => "Cannot create event date: Event date {$formattedDate} already exists in the schedule! Duplicate creation is not allowed. Please modify the existing event date instead."]);
            }
        }

        $bookingDate = BookingDate::firstOrCreate(
            ['date' => $dateStr],
            ['is_available' => true]
        );
        $bookingDate->is_available = $isAvailable;
        $bookingDate->save();

        $existingSlots = BookingSlot::where('booking_date_id', $bookingDate->id)->get();
        $updatedSlotIds = [];

        foreach ($entries as $entry) {
            $startTime = trim($entry['start_time'] ?? '');
            $endTime = trim($entry['end_time'] ?? '');
            $pax = (int) ($entry['pax'] ?? 1);

            if (empty($startTime)) continue;

            if (strlen($startTime) === 5) {
                $startTime .= ':00';
            }

            if (empty($endTime)) {
                $endTime = \Carbon\Carbon::parse($startTime)->addHour()->format('H:i:s');
            } elseif (strlen($endTime) === 5) {
                $endTime .= ':00';
            }

            $slot = BookingSlot::where('booking_date_id', $bookingDate->id)
                ->where('start_time', 'LIKE', substr($startTime, 0, 5) . '%')
                ->first();

            if ($slot) {
                $targetCapacity = max($pax, $slot->booked_count);
                $slot->update([
                    'capacity' => $targetCapacity,
                    'end_time' => $endTime,
                ]);
                $updatedSlotIds[] = $slot->id;
            } else {
                $newSlot = BookingSlot::create([
                    'booking_date_id' => $bookingDate->id,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'capacity' => $pax,
                    'booked_count' => 0,
                    'is_available' => true,
                ]);
                $updatedSlotIds[] = $newSlot->id;
            }
        }

        // Clean up unbooked slots for this date that were removed from the form
        foreach ($existingSlots as $oldSlot) {
            if (!in_array($oldSlot->id, $updatedSlotIds) && $oldSlot->booked_count == 0) {
                $oldSlot->delete();
            }
        }

        \App\Services\HistoryLogService::log(
            'UPDATE_PUBLIC_SCHEDULE_SLOTS',
            "Updated public schedule slots for date {$dateStr}",
            'BookingDate',
            $bookingDate->id
        );

        return redirect()->back()->with('success', "Public schedule slots and Pax capacity for {$dateStr} updated successfully.");
    }

    /**
     * Helper to clean up unbooked booking_slots that are no longer used by any VIP preset or operating session.
     */
    private function cleanupOrphanSlots(array $removedSlots): void
    {
        if (empty($removedSlots)) return;

        $activeGroups = \App\Services\VipGroupService::getVipGroups();

        foreach ($removedSlots as $r) {
            $dateStr = $r['date'];
            $startTime5 = $r['start_time'];

            // 1. Check if any OTHER active VIP group preset uses this (date, start_time)
            $usedByOtherGroup = false;
            foreach ($activeGroups as $g) {
                if (isset($g['schedules'][$dateStr])) {
                    foreach ($g['schedules'][$dateStr] as $s) {
                        if (substr($s['start_time'], 0, 5) === $startTime5) {
                            $usedByOtherGroup = true;
                            break 2;
                        }
                    }
                }
            }

            if ($usedByOtherGroup) continue;

            // 2. Check if active operating session uses this time slot
            $dayOfWeek = \Carbon\Carbon::parse($dateStr)->dayOfWeekIso;
            $opHour = \App\Models\OperatingHour::where('day_of_week', $dayOfWeek)->where('is_open', true)->first();
            $usedByOperatingHour = false;
            if ($opHour) {
                $usedByOperatingHour = $opHour->sessions()
                    ->where('is_active', true)
                    ->where('start_time', 'LIKE', $startTime5 . '%')
                    ->exists();
            }

            if ($usedByOperatingHour) continue;

            // 3. Find and delete unbooked booking_slot in database if booked_count == 0
            $bookingDate = \App\Models\BookingDate::where('date', $dateStr)->first();
            if ($bookingDate) {
                $slot = \App\Models\BookingSlot::where('booking_date_id', $bookingDate->id)
                    ->where('start_time', 'LIKE', $startTime5 . '%')
                    ->first();

                if ($slot && $slot->booked_count == 0) {
                    $slot->delete();
                }
            }
        }
    }
}
