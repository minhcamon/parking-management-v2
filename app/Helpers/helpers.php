<?php

if (!function_exists('toast')) {
    /**
     * Flash a toast message to the session.
     *
     * @param string $message
     * @param string $type success|error|warning|info
     * @return void
     */
    function toast($message, $type = 'success')
    {
        session()->flash("toast_{$type}", $message);
    }
}

if (!function_exists('is_valid_vehicle_plate')) {
    /**
     * Validate Vietnam vehicle license plate (Car and Motorcycle).
     *
     * @param string $plate
     * @return bool
     */
    function is_valid_vehicle_plate($plate)
    {
        if (empty($plate)) {
            return false;
        }

        // Remove all spaces, dashes and dots for comparison if needed
        // but the regex below handles common formats too.
        
        // Regex explaining:
        // 1. [1-9][0-9]: Province code (11-99)
        // 2. [A-Z][0-9]?: Series (Cars use 1 letter, Motorcycles use 1 letter + 1 digit)
        //    Wait, actually some special ones exist like LD, DA... but let's stick to common.
        // 3. [-.\s]?: Separator
        // 4. [0-9]{4,5}: Number (4 or 5 digits)
        // 5. (\.[0-9]{2})?: Optional dot for new 5-digit format
        
        $pattern = '/^[1-9][0-9][A-Z][0-9]?[-.\s]?[0-9]{3,5}(\.[0-9]{2})?$/i';
        
        // Advanced check for special cases if needed, but this regex is a strong baseline.
        return preg_match($pattern, trim($plate)) === 1;
    }
}
