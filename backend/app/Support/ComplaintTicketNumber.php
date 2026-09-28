<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ComplaintTicketNumber
{
    public function next(string $complaintType): string
    {
        $typeCode = config('complaints.codes.'.$complaintType);

        if (! is_string($typeCode)) {
            throw new InvalidArgumentException('Jenis pengaduan tidak memiliki kode tiket.');
        }

        $prefix = 'BHP-'.$typeCode.'-'.now()->format('ymd').'-';

        $number = DB::transaction(function () use ($prefix): int {
            DB::table('complaint_ticket_sequences')->insertOrIgnore([
                'prefix' => $prefix,
                'next_number' => 1,
            ]);

            $sequence = DB::table('complaint_ticket_sequences')
                ->where('prefix', $prefix)
                ->lockForUpdate()
                ->first();

            $number = (int) $sequence->next_number;

            DB::table('complaint_ticket_sequences')
                ->where('prefix', $prefix)
                ->update(['next_number' => $number + 1]);

            return $number;
        }, 3);

        return $prefix.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
    }
}
