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
                $rowArray = $row->toArray();
                $values = array_values($rowArray); // Get positional values
                
                // Extract RSBSA - try header keys first, then first column (position 0)
                $rsbsaRaw = trim((string)($row['rsbsa_number'] ?? $row['rsbsa'] ?? $row['reference_number'] ?? $row['reference'] ?? $values[0] ?? ''));
                
                // Handle "NO REFERENCE NUMBER" and similar placeholders
                if (stripos($rsbsaRaw, 'no reference') !== false) {
                    $rsbsa = null;
                } else {
                    $rsbsa = \App\Models\User::normalizeRsbsaNumber($rsbsaRaw);
                    // If normalization resulted in empty string, set to null
                    $rsbsa = empty($rsbsa) ? null : $rsbsa;
                }

                // Extract Name - try header keys, then fallback to position 1
                $name = trim((string)($row['name'] ?? $values[1] ?? ''));
                if (empty($name)) {
                    // Try firstname + lastname
                    $firstname = trim((string)($row['firstname'] ?? ''));
                    $lastname = trim((string)($row['lastname'] ?? ''));
                    $name = trim($firstname . ' ' . $lastname);
                }

                if (empty($name)) {
                    $this->results['skipped']++;
                    continue;
                }

                // Extract Location/Municipality - try header keys, then position 2
                $municipality = trim((string)($row['municipality'] ?? $row['location'] ?? $row['barangay'] ?? $values[2] ?? ''));

                // Generate email - prioritize RSBSA if available
                $email = null;
                if (!empty($rsbsa)) {
                    $slug = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $rsbsa);
                    $email = strtolower($slug) . '@rsbsa.local';
                } else {
                    // Create unique email from name + timestamp
                    $slug = Str::slug($name);
                    $email = $slug . '.' . Str::random(6) . '@noemail.local';
                }

                // Ensure email is unique
                $baseEmail = $email;
                $counter = 1;
                while (User::where('email', $email)->exists()) {
                    $email = str_replace('@', $counter . '@', $baseEmail);
                    $counter++;
                }

                // Skip if user already exists by RSBSA
                if ($rsbsa && User::where('rsbsa_number', $rsbsa)->exists()) {
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
                    'rsbsa_number' => $rsbsa,
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
