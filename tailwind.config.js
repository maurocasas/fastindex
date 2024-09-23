/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.{blade.php,php,html,js}",
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
    ],
    daisyui: {
        themes: [
            {
                mytheme: {
                    primary: "#EE8679",
                    secondary: "#F8D2C9",
                    accent: "#5BA2D0",
                    neutral: "#151726",
                    "base-100": "#07070f",
                    info: "#94B8FF",
                    success: "#33ddbe",
                    warning: "#f6c33f",
                    error: "#F87272",
                },
            },
        ],
        // themes: ['dark']
    },
    theme: {
        fontFamily: {
            sans: ['Inter', 'sans-serif']
        },
        extend: {},
    },
    plugins: [
    ],
}

