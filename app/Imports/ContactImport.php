<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ContactImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
         foreach ($rows as $key => $row) {
            if ($row['phone'])
            {
                Contact::firstOrCreate(
                    [
                        'name' => $row['name'] ?? 'Demo',
                        'phone' => $row['phone'],
                    ]
                );
            }

        }
    }
}
