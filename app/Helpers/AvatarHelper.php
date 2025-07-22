<?php

namespace App\Helpers;

class AvatarHelper
{
    /**
     * Generate an avatar image with user's initials in SVG format
     *
     * @param string $name
     * @return string
     */
    public static function generateSvgAvatar1($name)
    {
    // Extract the initials of the first two letters of the first and second name
        $nameParts = explode(' ', $name);

    // Get the first letter of the first name and second name (if available)
        $initials = strtoupper(substr($nameParts[0], 0, 2) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 2) : ''));

    // Random background color
        $bgColor = sprintf('#%02x%02x%02x', rand(100, 255), rand(100, 255), rand(100, 255));

    // SVG markup for the avatar with adjusted 'y' position to ensure proper centering
        $svg = <<<SVG
        <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="50" fill="$bgColor" />
        <text x="50%" y="50%" font-size="40" text-anchor="middle" alignment-baseline="middle" fill="white" dy="8">$initials</text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function generateSvgAvatar($name)
    {
    // Extract the initials of the first two letters of the first and second name
        $nameParts = explode(' ', $name);

    // Get the first letter of the first name and second name (if available)
        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

    // Generate random colors for the gradient
        $color1 = sprintf('#%02x%02x%02x', rand(100, 255), rand(100, 255), rand(100, 255));
        $color2 = sprintf('#%02x%02x%02x', rand(100, 255), rand(100, 255), rand(100, 255));

    // SVG markup with gradient background
        $svg = <<<SVG
        <svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <!-- Define gradient -->
        <defs>
        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" style="stop-color:$color1;stop-opacity:1" />
        <stop offset="100%" style="stop-color:$color2;stop-opacity:1" />
        </linearGradient>
        </defs>
        
        <!-- Circle with gradient -->
        <circle cx="50" cy="50" r="50" fill="url(#grad1)" />
        
        <!-- Initials text -->
        <text x="50%" y="50%" font-size="40" text-anchor="middle" alignment-baseline="middle" fill="white" dy="15">$initials</text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }


    public static function generateSvgCover($name)
    {
    // Extract initials similar to avatar
        $nameParts = explode(' ', $name);
        $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

    // Generate random colors for background gradient
        $color1 = sprintf('#%02x%02x%02x', rand(100, 255), rand(100, 255), rand(100, 255));
        $color2 = sprintf('#%02x%02x%02x', rand(100, 255), rand(100, 255), rand(100, 255));

    // SVG dimensions for cover (e.g., 600x200)
        $svg = <<<SVG
        <svg width="600" height="200" xmlns="http://www.w3.org/2000/svg">
        <defs>
        <linearGradient id="coverGrad" x1="0%" y1="0%" x2="100%" y2="0%">
        <stop offset="0%" style="stop-color:$color1;stop-opacity:1" />
        <stop offset="100%" style="stop-color:$color2;stop-opacity:1" />
        </linearGradient>
        </defs>

        <rect width="100%" height="100%" fill="url(#coverGrad)" />
        
        <text x="50%" y="50%" font-size="60" text-anchor="middle" alignment-baseline="middle" fill="white" dy="20">$initials</text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function generateSvgLogo($name): string
    {
    // Extract initials from the name
        $nameParts = explode(' ', trim($name));
        $initials = strtoupper(
            substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
        );

    // Generate two gradient colors
        $color1 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));
        $color2 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));

    // Logo SVG — more compact and centered
        $svg = <<<SVG
        <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
        <defs>
        <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" stop-color="$color1" />
        <stop offset="100%" stop-color="$color2" />
        </linearGradient>
        </defs>

        <circle cx="100" cy="100" r="100" fill="url(#logoGrad)" />
        <text x="50%" y="50%" font-size="70" font-weight="bold"
        text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
        $initials
        </text>
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function generateSvgQrCode($name): string
    {
        // Extract initials from the name
        $nameParts = explode(' ', trim($name));
        $initials = strtoupper(
            substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
        );

        // Generate a very basic QR pattern using initials for simplicity
        $size = 10;  // Size of each square block
        $length = 21; // Length of the QR code (21x21 grid)

        // Generate random binary pattern
        $pattern = [];
        for ($row = 0; $row < $length; $row++) {
            for ($col = 0; $col < $length; $col++) {
                $pattern[$row][$col] = rand(0, 1);  // 0 or 1 to represent QR code blocks (black or white)
            }
        }

        // Convert the pattern to SVG
        $svg = '<svg width="210" height="210" xmlns="http://www.w3.org/2000/svg">';
        for ($row = 0; $row < $length; $row++) {
            for ($col = 0; $col < $length; $col++) {
                $color = ($pattern[$row][$col] == 1) ? 'black' : 'white';
                $svg .= "<rect x='" . ($col * $size) . "' y='" . ($row * $size) . "' width='$size' height='$size' fill='$color' />";
            }
        }

        // Add the initials text in the center (optional)
        $svg .= "<text x='50%' y='50%' font-size='40' text-anchor='middle' alignment-baseline='middle' fill='black' dy='15'>$initials</text>";

        // Close the SVG
        $svg .= '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function generateRandomAvatar($name, $gender = 'male'): string
    {
        // Extract initials from the name
        $nameParts = explode(' ', trim($name));
        $initials = strtoupper(
            substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
        );

        // Random color for background
        $color1 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));
        $color2 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));

        // Male or Female feature customizations
        $avatarElements = '';
        $fontSize = 70;
        
        if ($gender === 'female') {
            // Female-specific features: softer colors, maybe some long hair visualization
            $avatarElements = <<<SVG
                <circle cx="100" cy="100" r="100" fill="url(#femaleGrad)" />
                <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                      text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                    $initials
                </text>
                <!-- Additional female feature like longer hair (symbolic) -->
                <path d="M50,125 Q100,180 150,125" stroke="white" stroke-width="5" fill="none" />
            SVG;
        } else {
            // Male-specific features: a bit sharper, maybe shorter hairstyle or different strokes
            $avatarElements = <<<SVG
                <circle cx="100" cy="100" r="100" fill="url(#maleGrad)" />
                <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                      text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                    $initials
                </text>
                <!-- Male feature like short hairstyle (symbolic) -->
                <path d="M50,70 Q75,40 100,70 Q125,40 150,70" stroke="white" stroke-width="5" fill="none" />
            SVG;
        }

        // SVG Markup with Gradient
        $svg = <<<SVG
        <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="maleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="$color1" />
                    <stop offset="100%" stop-color="$color2" />
                </linearGradient>
                <linearGradient id="femaleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="$color1" />
                    <stop offset="100%" stop-color="$color2" />
                </linearGradient>
            </defs>

            $avatarElements
        </svg>
        SVG;

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    public static function generateEnhancedAvatar($name, $gender = 'male'): string
{
    // Extract initials from the name
    $nameParts = explode(' ', trim($name));
    $initials = strtoupper(
        substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    );

    // Random color for background
    $color1 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));
    $color2 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));

    // Male or Female feature customizations
    $avatarElements = '';
    $fontSize = 70;

    if ($gender === 'female') {
        // Female-specific features: softer colors, longer hair and facial features
        $avatarElements = <<<SVG
            <circle cx="100" cy="100" r="100" fill="url(#femaleGrad)" />
            <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                  text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                $initials
            </text>

            <!-- Long hair (symbolic) -->
            <path d="M50,125 Q100,180 150,125" stroke="white" stroke-width="5" fill="none" />
            
            <!-- Eyes -->
            <circle cx="75" cy="90" r="10" fill="white" />
            <circle cx="125" cy="90" r="10" fill="white" />
            <circle cx="75" cy="90" r="5" fill="black" />
            <circle cx="125" cy="90" r="5" fill="black" />

            <!-- Nose -->
            <path d="M100,110 Q95,125 105,125" stroke="white" stroke-width="3" fill="none" />

            <!-- Lips -->
            <path d="M90,140 Q100,150 110,140" stroke="white" stroke-width="3" fill="none" />
        SVG;
    } else {
        // Male-specific features: sharper features, shorter hair and facial elements
        $avatarElements = <<<SVG
            <circle cx="100" cy="100" r="100" fill="url(#maleGrad)" />
            <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                  text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                $initials
            </text>

            <!-- Short hairstyle (symbolic) -->
            <path d="M50,70 Q75,40 100,70 Q125,40 150,70" stroke="white" stroke-width="5" fill="none" />
            
            <!-- Eyes -->
            <circle cx="75" cy="90" r="10" fill="white" />
            <circle cx="125" cy="90" r="10" fill="white" />
            <circle cx="75" cy="90" r="5" fill="black" />
            <circle cx="125" cy="90" r="5" fill="black" />

            <!-- Nose -->
            <path d="M100,110 Q95,120 105,120" stroke="white" stroke-width="3" fill="none" />

            <!-- Lips -->
            <path d="M90,130 Q100,135 110,130" stroke="white" stroke-width="3" fill="none" />
        SVG;
    }

    // SVG Markup with Gradient
    $svg = <<<SVG
    <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="maleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="$color1" />
                <stop offset="100%" stop-color="$color2" />
            </linearGradient>
            <linearGradient id="femaleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="$color1" />
                <stop offset="100%" stop-color="$color2" />
            </linearGradient>
        </defs>

        $avatarElements
    </svg>
    SVG;

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

public static function generateAdvancedAvatar($name, $gender = 'male'): string
{
    // Extract initials from the name
    $nameParts = explode(' ', trim($name));
    $initials = strtoupper(
        substr($nameParts[0] ?? '', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
    );

    // Random color for background
    $color1 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));
    $color2 = sprintf('#%02x%02x%02x', rand(80, 200), rand(80, 200), rand(80, 200));

    // Male or Female specific elements
    $avatarElements = '';
    $fontSize = 70;

    // Adjust for gender-specific features
    if ($gender === 'female') {
        // Female features: Soft features, long hair, and oval face
        $avatarElements = <<<SVG
            <circle cx="100" cy="100" r="100" fill="url(#femaleGrad)" />
            <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                  text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                $initials
            </text>

            <!-- Face (oval) -->
            <ellipse cx="100" cy="100" rx="80" ry="100" fill="url(#femaleGrad)" />

            <!-- Ears -->
            <ellipse cx="35" cy="100" rx="20" ry="35" fill="#e0ac69" />
            <ellipse cx="165" cy="100" rx="20" ry="35" fill="#e0ac69" />

            <!-- Hair (long, wavy) -->
            <path d="M40,60 Q100,10 160,60" stroke="black" stroke-width="5" fill="none" />
            <path d="M40,70 Q100,20 160,70" stroke="black" stroke-width="5" fill="none" />

            <!-- Eyes -->
            <circle cx="75" cy="90" r="12" fill="white" />
            <circle cx="125" cy="90" r="12" fill="white" />
            <circle cx="75" cy="90" r="5" fill="black" />
            <circle cx="125" cy="90" r="5" fill="black" />

            <!-- Nose -->
            <path d="M100,120 Q95,130 105,130" stroke="black" stroke-width="3" fill="none" />

            <!-- Lips -->
            <path d="M90,145 Q100,155 110,145" stroke="black" stroke-width="3" fill="none" />
        SVG;
    } else {
        // Male features: Stronger features, short hair, and a more square face
        $avatarElements = <<<SVG
            <circle cx="100" cy="100" r="100" fill="url(#maleGrad)" />
            <text x="50%" y="50%" font-size="$fontSize" font-weight="bold"
                  text-anchor="middle" alignment-baseline="central" fill="white" dy="10">
                $initials
            </text>

            <!-- Face (round/square) -->
            <rect x="20" y="30" width="160" height="140" rx="20" ry="20" fill="url(#maleGrad)" />

            <!-- Ears -->
            <ellipse cx="35" cy="100" rx="20" ry="35" fill="#e0ac69" />
            <ellipse cx="165" cy="100" rx="20" ry="35" fill="#e0ac69" />

            <!-- Hair (short and messy) -->
            <path d="M50,60 Q100,30 150,60" stroke="black" stroke-width="5" fill="none" />
            <path d="M50,70 Q100,40 150,70" stroke="black" stroke-width="5" fill="none" />

            <!-- Eyes -->
            <circle cx="75" cy="90" r="12" fill="white" />
            <circle cx="125" cy="90" r="12" fill="white" />
            <circle cx="75" cy="90" r="5" fill="black" />
            <circle cx="125" cy="90" r="5" fill="black" />

            <!-- Nose -->
            <path d="M100,115 Q95,125 105,125" stroke="black" stroke-width="3" fill="none" />

            <!-- Lips -->
            <path d="M90,135 Q100,140 110,135" stroke="black" stroke-width="3" fill="none" />
        SVG;
    }

    // SVG Markup with Gradient
    $svg = <<<SVG
    <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="maleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="$color1" />
                <stop offset="100%" stop-color="$color2" />
            </linearGradient>
            <linearGradient id="femaleGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="$color1" />
                <stop offset="100%" stop-color="$color2" />
            </linearGradient>
        </defs>

        $avatarElements
    </svg>
    SVG;

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

}
