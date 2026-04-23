/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: ['class', '[data-theme="dark"]'],
    theme: {
        extend: {
            colors: {
                main: 'var(--bg-color)',

                accent: 'var(--accent-primary)',
                'accent-primary': 'var(--accent-primary)',
                'accent-secondary': 'var(--accent-secondary)',
                glass: {
                    bg: 'var(--glass-bg)',
                    border: 'var(--glass-border)',
                },
                nav: {
                    hover: 'var(--nav-hover-bg)',
                },
                header: {
                    border: 'var(--header-border)',
                },
                profile: {
                    hover: 'var(--profile-hover-border)',
                },
                card: {
                    shadow: 'var(--card-shadow)',
                    'hover-shadow': 'var(--card-hover-shadow)',
                    'hover-border': 'var(--card-hover-border)',
                },
                dropdown: {
                    bg: 'var(--dropdown-bg)',
                    hover: 'var(--dropdown-hover)',
                },
                input: {
                    bg: 'var(--input-bg)',
                    border: 'var(--input-border)',
                    'focus-border': 'var(--input-focus-border)',
                    'focus-shadow': 'var(--input-focus-shadow)',
                    text: 'var(--input-text)',
                    placeholder: 'var(--input-placeholder)',
                }
            },
            textColor: {
                main: 'var(--text-main)',
                muted: 'var(--text-muted)',
            },
            fontFamily: {
                sans: ['"Be Vietnam Pro"', 'sans-serif'],
                inter: ['Inter', 'sans-serif'],
                outfit: ['Outfit', 'monospace'],
            },
            transitionDuration: {
                DEFAULT: '300ms',
                'speed': 'var(--transition-speed)',
            },
            spacing: {
                'sidebar': 'var(--sidebar-width)',
            }
        },
    },
    plugins: [],
};
