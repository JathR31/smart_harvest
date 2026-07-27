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
        $processedRsbsas = []; // Track RSBSAs in current import to prevent duplicates within same file
        $processedEmails = []; // Track emails in current import
        
        foreach ($rows as $row) {
            try {
                $rowArray = $row->toArray();
                $values = array_values($rowArray); // Get positional values
                
                // Accept the common RSBSA column names produced by uploaded DA user datasets.
                // WithHeadingRow turns labels such as "RSBSA Reference No." into
                // snake-cased keys, so aliases are more reliable than a fixed column name.
                $rsbsaRaw = trim((string) $this->firstRowValue($rowArray, [
                    'rsbsa_number',
                    'rsbsa_no',
                    'rsbsa',
                    'rsbsa_reference_number',
                    'rsbsa_reference_no',
                    'reference_number',
                    'reference_no',
                    'reference',
                ], $values[0] ?? ''));
                
                // Handle "NO REFERENCE NUMBER" and similar placeholders
                if (stripos($rsbsaRaw, 'no reference') !== false) {
                    $rsbsa = null;
                } else {
                        $rsbsa = \App\Models\User::normalizeRsbsaNumber($rsbsaRaw);
                    // If normalization resulted in empty string, set to null
                    $rsbsa = empty($rsbsa) ? null : $rsbsa;
                }

                // Extract Name - try the headers used by farmer registries, then fallback to position 1.
                $name = trim((string) $this->firstRowValue($rowArray, [
                    'name',
                    'full_name',
                    'farmer_name',
                    'name_of_farmer',
                ], $values[1] ?? ''));
                if (empty($name)) {
                    // Try firstname + lastname
                    $firstname = trim((string) $this->firstRowValue($rowArray, ['firstname', 'first_name'], ''));
                    $lastname = trim((string) $this->firstRowValue($rowArray, ['lastname', 'last_name', 'surname'], ''));
                    $name = trim($firstname . ' ' . $lastname);
                }

                if (empty($name)) {
                    $this->results['skipped']++;
                    continue;
                }

                // Extract Location/Municipality - try header keys, then position 2.
                $municipality = trim((string) $this->firstRowValue($rowArray, [
                    'municipality',
                    'city_municipality',
                    'municipality_city',
                    'location',
                    'barangay',
                    'address',
                ], $values[2] ?? ''));

                // CHECK 1: Skip if RSBSA already processed in this import (duplicate in file)
                if ($rsbsa && in_array($rsbsa, $processedRsbsas)) {
                    $this->results['skipped']++;
                    continue;
                }

                // CHECK 2: Skip if RSBSA already exists in database
                if ($rsbsa && User::whereIn('rsbsa_number', User::rsbsaLookupValues($rsbsa))->exists()) {
                    $this->results['skipped']++;
                    continue;
                }

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

                // CHECK 3: Skip if email already processed in this import
                if (in_array($email, $processedEmails)) {
                    $this->results['skipped']++;
                    continue;
                }

                // CHECK 4: Ensure email is unique in database and current import
                $baseEmail = $email;
                $counter = 1;
                while (User::where('email', $email)->exists() || in_array($email, $processedEmails)) {
                    $email = str_replace('@', $counter . '@', $baseEmail);
                    $counter++;
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

                // Track this RSBSA and email as processed
                if ($rsbsa) {
                    $processedRsbsas[] = $rsbsa;
                }
                $processedEmails[] = $email;

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

    /**
     * Get the first populated value from a heading-normalized spreadsheet row.
     *
     * @param array<string, mixed> $row
     * @param array<int, string> $keys
     */
    private function firstRowValue(array $row, array $keys, mixed $fallback = null): mixed
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && trim((string) $row[$key]) !== '') {
                return $row[$key];
            }
        }

        return $fallback;
    }
}
