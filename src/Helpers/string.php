<?php

/**
 * Convert a string to "snake_case" format.
 *
 * This function takes a string and converts it to the "snake_case" format, which is a naming convention where
 * words are separated by underscores and all characters are in lowercase. Any non-alphanumeric characters, except
 * underscores, are treated as word separators and are replaced with underscores.
 *
 * @param string $str The input string to be converted to "snake_case."
 *
 * @return string The input string in "snake_case" format.
 */
function snake_case(string $str): string
{
    $snake_cased = []; // An array to store characters in "snake_case" format.
    $skip = [' ', '-', '_', '/', '\\', '|', ',', '.', ';', ':']; // Characters to be skipped during conversion.

    $i = 0; // Variable to iterate through the characters in the string.

    while ($i < strlen($str)) { // Loop to iterate through each character of the string.
        $last = count($snake_cased) > 0 ? $snake_cased[count($snake_cased) - 1] : null; // Get the last character added to the array.

        $character = $str[$i++]; // Get the next character in the string.

        if (ctype_upper($character)) { // If the character is an uppercase letter.
            if ($last !== '_') { // If the last character is not an underscore.
                $snake_cased[] = '_'; // Add an underscore to separate words.
            }
            $snake_cased[] = strtolower($character); // Convert the uppercase letter to lowercase and add it to the array.
        } elseif (ctype_lower($character)) { // If the character is a lowercase letter.
            $snake_cased[] = $character; // Add the lowercase letter to the array.
        } elseif (in_array($character, $skip)) { // If the character is in the array of characters to skip.
            if ($last !== '_') { // If the last character is not an underscore.
                $snake_cased[] = '_'; // Add an underscore to separate words.
            }
            while ($i < strlen($str) && in_array($str[$i], $skip)) { // Skip repeated characters from the array of characters to skip.
                $i++;
            }
        }
    }

    if ($snake_cased[0] == '_') { // If the first character is an underscore.
        $snake_cased[0] = ''; // Remove the underscore.
    }

    if ($snake_cased[count($snake_cased) - 1] == '_') { // If the last character is an underscore.
        $snake_cased[count($snake_cased) - 1] = ''; // Remove the underscore.
    }

    return implode($snake_cased); // Convert the array into a string and return it.
}
