<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RecordsExport implements FromArray, WithHeadings, WithColumnWidths
{
    protected $records;
    protected $headings;
    public function __construct(array $records)
    {
        $this->records = $records;
    }

    public function array(): array
    {
        return $this->records;
    }

    public function headings(): array
    {
        // Extract headings from the first record
        return $this->headings = array_keys($this->records[0]);
    }

    private function getColumnLetter($index) {
        $letter = '';
        while ($index >= 0) {
            $letter = chr($index % 26 + 65) . $letter;
            $index = floor($index / 26) - 1;
        }
        return $letter;
    }

    public function columnWidths(): array
    {
        $columnWidths = [];
        $data = $this->array();

        // Add headings to data for width calculation
        array_unshift($data, $this->headings);

        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                $columnIndex = $this->getColumnLetter(array_search($key, $this->headings));
                $width = strlen($value) + 2; // Add extra space for padding
                if (!isset($columnWidths[$columnIndex]) || $width > $columnWidths[$columnIndex]) {
                    $columnWidths[$columnIndex] = $width;
                }
            }
        }

        return array_map(function($width) {
            return $width > 255 ? 255 : $width;
        }, $columnWidths);
    }
}
