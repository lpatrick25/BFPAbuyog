<?php

namespace App\Traits;

trait HasFullName
{
    /**
     * Get the full name of the model.
     */
    public function getFullName(): string
    {
        // Extract the first letter of the middle name if it exists
        $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)) . '.' : '';

        // Trim spaces and format the name correctly
        return trim(
            sprintf(
                '%s %s %s %s',
                $this->first_name,
                $middleInitial,  // Use the middle initial
                $this->last_name,
                $this->extension_name ?? ''
            )
        );
    }
}
