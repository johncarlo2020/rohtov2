<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class VipGroupService
{
    protected static string $fileName = 'vip_groups.json';

    /**
     * Default initial VIP Groups configuration.
     */
    public static function getDefaultVipGroups(): array
    {
        return [
            'KOL AND MEDIA INFLUENCER' => [
                'key' => 'KOL AND MEDIA INFLUENCER',
                'name' => 'KOL AND MEDIA INFLUENCER',
                'pax_summary' => '80 Pax',
                'date_summary' => 'Sep 30',
                'badge' => 'Sep 30',
                'schedules' => [
                    '2026-09-30' => [
                        ['start_time' => '11:00', 'end_time' => '12:00', 'pax' => 20],
                        ['start_time' => '12:00', 'end_time' => '13:00', 'pax' => 20],
                        ['start_time' => '13:00', 'end_time' => '14:00', 'pax' => 20],
                        ['start_time' => '14:00', 'end_time' => '15:00', 'pax' => 20],
                    ],
                ],
                'breakdown_details' => [
                    '30 Sep: 11:00 AM - 3:00 PM (4 x 20 pax = 80 pax)',
                ]
            ],
            'LONGCHAMP VIC' => [
                'key' => 'LONGCHAMP VIC',
                'name' => 'LONGCHAMP VIC',
                'pax_summary' => '20 Pax',
                'date_summary' => 'Sep 30',
                'badge' => 'Sep 30',
                'schedules' => [
                    '2026-09-30' => [
                        ['start_time' => '15:00', 'end_time' => '16:00', 'pax' => 10],
                        ['start_time' => '16:00', 'end_time' => '17:00', 'pax' => 10],
                    ]
                ],
                'breakdown_details' => [
                    '30 Sep: 3:00 PM - 5:00 PM (2 x 10 pax = 20 pax)',
                ]
            ],
            'THE GARDENS EMERALD MEMBER' => [
                'key' => 'THE GARDENS EMERALD MEMBER',
                'name' => 'THE GARDENS EMERALD MEMBER',
                'pax_summary' => '24 Pax',
                'date_summary' => 'Sep 30 & Oct 1',
                'badge' => 'Sep 30 & Oct 1',
                'schedules' => [
                    '2026-09-30' => [
                        ['start_time' => '17:00', 'end_time' => '18:00', 'pax' => 6],
                        ['start_time' => '18:00', 'end_time' => '19:00', 'pax' => 6],
                    ],
                    '2026-10-01' => [
                        ['start_time' => '15:00', 'end_time' => '16:00', 'pax' => 6],
                        ['start_time' => '16:00', 'end_time' => '17:00', 'pax' => 6],
                    ],
                ],
                'breakdown_details' => [
                    '30 Sep: 5:00 PM - 7:00 PM (2 x 6 pax = 12 pax)',
                    '1 Oct: 3:00 PM - 5:00 PM (2 x 6 pax = 12 pax)',
                ]
            ],
            'MAYBANK PREMIUM CUSTOMER' => [
                'key' => 'MAYBANK PREMIUM CUSTOMER',
                'name' => 'MAYBANK PREMIUM CUSTOMER',
                'pax_summary' => '30 Pax',
                'date_summary' => 'Sep 30',
                'badge' => 'Sep 30',
                'schedules' => [
                    '2026-09-30' => [
                        ['start_time' => '19:00', 'end_time' => '20:00', 'pax' => 10],
                        ['start_time' => '20:00', 'end_time' => '21:00', 'pax' => 10],
                        ['start_time' => '21:00', 'end_time' => '22:00', 'pax' => 10],
                    ]
                ],
                'breakdown_details' => [
                    '30 Sep: 7:00 PM - 10:00 PM (3 x 10 pax = 30 pax)',
                ]
            ],
            'PIN PRESTIGE' => [
                'key' => 'PIN PRESTIGE',
                'name' => 'PIN PRESTIGE',
                'pax_summary' => '12 Pax',
                'date_summary' => 'Oct 1',
                'badge' => 'Oct 1',
                'schedules' => [
                    '2026-10-01' => [
                        ['start_time' => '13:00', 'end_time' => '14:00', 'pax' => 6],
                        ['start_time' => '14:00', 'end_time' => '15:00', 'pax' => 6],
                    ]
                ],
                'breakdown_details' => [
                    '1 Oct: 1:00 PM - 3:00 PM (2 x 6 pax = 12 pax)',
                ]
            ],
            'PRIVATE SHOPPING SESSION: FERHAT' => [
                'key' => 'PRIVATE SHOPPING SESSION: FERHAT',
                'name' => 'PRIVATE SHOPPING SESSION: FERHAT',
                'pax_summary' => '30 Pax',
                'date_summary' => 'Oct 9',
                'badge' => 'Oct 9',
                'schedules' => [
                    '2026-10-09' => [
                        ['start_time' => '17:30', 'end_time' => '20:00', 'pax' => 30],
                    ]
                ],
                'breakdown_details' => [
                    '9 Oct: 5:30 PM - 8:00 PM (30 pax)',
                ]
            ],
            'GLAM READER' => [
                'key' => 'GLAM READER',
                'name' => 'GLAM READER',
                'pax_summary' => '20 Pax',
                'date_summary' => 'Oct 13 & Oct 14',
                'badge' => 'Oct 13 & Oct 14',
                'schedules' => [
                    '2026-10-13' => [
                        ['start_time' => '11:00', 'end_time' => '13:00', 'pax' => 10],
                    ],
                    '2026-10-14' => [
                        ['start_time' => '11:00', 'end_time' => '12:00', 'pax' => 5],
                        ['start_time' => '12:00', 'end_time' => '13:00', 'pax' => 5],
                    ],
                ],
                'breakdown_details' => [
                    '13 Oct: 11:00 AM - 1:00 PM (10 pax)',
                    '14 Oct: 11:00 AM - 1:00 PM (2 x 5 pax = 10 pax)',
                ]
            ],
        ];
    }

    /**
     * Get single source of truth for VIP Group configurations.
     */
    public static function getVipGroups(): array
    {
        if (Storage::exists(self::$fileName)) {
            $json = Storage::get(self::$fileName);
            $decoded = json_decode($json, true);
            if (is_array($decoded) && !empty($decoded)) {
                return $decoded;
            }
        }

        $defaults = self::getDefaultVipGroups();
        self::saveVipGroups($defaults);
        return $defaults;
    }

    /**
     * Save VIP Groups array to persistent storage.
     */
    public static function saveVipGroups(array $groups): void
    {
        Storage::put(self::$fileName, json_encode($groups, JSON_PRETTY_PRINT));
    }

    /**
     * Create or update a VIP Group Preset dynamically.
     */
    public static function updateVipGroup(?string $originalKey, string $name, array $entries): void
    {
        $groups = self::getVipGroups();

        $schedules = [];
        $totalPax = 0;
        $dateLabels = [];
        $breakdowns = [];

        foreach ($entries as $entry) {
            $date = trim($entry['date'] ?? '');
            $startTime = trim($entry['start_time'] ?? '');
            $endTime = trim($entry['end_time'] ?? '');
            $pax = max(1, (int) ($entry['pax'] ?? 1));

            if (empty($date) || empty($startTime)) continue;

            if (empty($endTime)) {
                $endTime = Carbon::parse($startTime)->addHour()->format('H:i');
            }

            if (strlen($startTime) === 8) $startTime = substr($startTime, 0, 5);
            if (strlen($endTime) === 8) $endTime = substr($endTime, 0, 5);

            if (!isset($schedules[$date])) {
                $schedules[$date] = [];
            }

            $schedules[$date][] = [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'pax' => $pax,
            ];

            $totalPax += $pax;

            $dObj = Carbon::parse($date);
            $dayNum = $dObj->format('j');
            $monStr = $dObj->format('M');
            $dateLabels[$date] = "{$dayNum} {$monStr}";
        }

        ksort($dateLabels);
        $uniqueDates = array_values($dateLabels);
        $dateSummary = count($uniqueDates) > 0 ? implode(' & ', $uniqueDates) : 'N/A';
        $paxSummary = "{$totalPax} Pax";

        foreach ($schedules as $dateStr => $slots) {
            $dObj = Carbon::parse($dateStr);
            $dFormatted = $dObj->format('j M');
            $slotTexts = [];
            foreach ($slots as $s) {
                $startFmt = Carbon::parse($s['start_time'])->format('g:i A');
                $endFmt = Carbon::parse($s['end_time'])->format('g:i A');
                $slotTexts[] = "{$startFmt} - {$endFmt} ({$s['pax']} pax)";
            }
            $breakdowns[] = "{$dFormatted}: " . implode(', ', $slotTexts);
        }

        $newGroup = [
            'key' => $name,
            'name' => $name,
            'pax_summary' => $paxSummary,
            'date_summary' => $dateSummary,
            'badge' => $dateSummary,
            'schedules' => $schedules,
            'breakdown_details' => $breakdowns,
        ];

        if (!empty($originalKey) && $originalKey !== $name && isset($groups[$originalKey])) {
            unset($groups[$originalKey]);
        }

        $groups[$name] = $newGroup;
        self::saveVipGroups($groups);
    }

    /**
     * Delete a VIP Group Preset.
     */
    public static function deleteVipGroup(string $key): void
    {
        $groups = self::getVipGroups();
        if (isset($groups[$key])) {
            unset($groups[$key]);
            self::saveVipGroups($groups);
        }
    }

    /**
     * Extract schedule map for JS frontend consumption.
     */
    public static function getScheduleMap(): array
    {
        $groups = self::getVipGroups();
        $map = [];

        foreach ($groups as $key => $group) {
            $map[$key] = $group['schedules'];
        }

        return $map;
    }
}
