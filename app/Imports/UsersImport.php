<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;

class UsersImport implements ToCollection, WithHeadingRow
{
    protected $createdBy;
    protected $results = ['imported' => 0, 'skipped' => 0, 'errors' => []];

    public function __construct($createdBy = null)
    {
        $this->createdBy = $createdBy;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Try common header keys first
                $rsbsa = trim((string)($row['rsbsa_number'] ?? $row['rsbsa'] ?? $row['reference_number'] ?? $row['reference'] ?? ''));
                $rsbsa = \App\Models\User::normalizeRsbsaNumber($rsbsa);

                // Name detection: try common headers or fall back to positional
                $name = trim((string)($row['name'] ?? ($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '') ?? ''));
                if (empty($name)) {
                    // Some files have name in second column without headings
                    $vals = $row->toArray();
                    $vals = array_values($vals);
                    $name = isset($vals[1]) ? trim((string)$vals[1]) : (isset($vals[0]) ? trim((string)$vals[0]) : '');
                }

                if (empty($name)) {
                    $this->results['skipped']++;
                    continue;
                }

                $municipality = trim((string)($row['municipality'] ?? $row['location'] ?? ''));

                // Generate a fallback email if none exists
                $email = null;
                if (!empty($rsbsa)) {
                    $slug = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $rsbsa);
                    $email = strtolower($slug) . '@rsbsa.local';
                } else {
                    $slug = Str::slug($name);
                    $email = $slug . '.' . time() . '@noemail.local';
                }

                // Skip if user already exists by email or rsbsa
                if (User::where('email', $email)->exists() || ($rsbsa && User::where('rsbsa_number', $rsbsa)->exists())) {
                    $this->results['skipped']++;
                    continue;
                }

                $password = Str::random(10);

                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'Farmer',
                    'status' => 'Active',
                    'phone' => null,
                    'location' => $municipality ?: null,
                    'rsbsa_number' => $rsbsa ?: null,
                ]);

                $this->results['imported']++;
            } catch (\Throwable $e) {
                $this->results['errors'][] = $e->getMessage();
            }
        }
    }

    public function getResults()
    {
        return $this->results;
    }
}
