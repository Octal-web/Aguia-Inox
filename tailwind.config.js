import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.jsx",
    ],

    theme: {
        extend: {
            colors: {
                background: "oklch(1 0 0)",
                foreground: "oklch(0.145 0 0)",
                card: "oklch(1 0 0)",
                "card-foreground": "oklch(0.145 0 0)",
                popover: "oklch(1 0 0)",
                "popover-foreground": "oklch(0.145 0 0)",
                primary: "oklch(0.6067 0.1057 213.2951 / var(--tw-text-opacity, 1))",
                "primary-foreground": "oklch(0.985 0 0)",
                secondary: "oklch(0.2801 0.0457 245.3731 / var(--tw-text-opacity, 1))",
                "secondary-foreground": "oklch(1 0 0)",
                muted: "oklch(0.97 0 0)",
                "muted-foreground": "oklch(0.556 0 0)",
                accent: "oklch(0.97 0 0)",
                "accent-foreground": "oklch(0.205 0 0)",
                destructive: "oklch(0.577 0.245 27.325)",
                "destructive-foreground": "oklch(1 0 0)",
                border: "oklch(0.922 0 0)",
                input: "oklch(0.922 0 0)",
                ring: "oklch(0.708 0 0)",
                sidebar: "oklch(0.985 0 0)",
                "sidebar-foreground": "oklch(0.145 0 0)",
                "sidebar-primary": "oklch(0.205 0 0)",
                "sidebar-primary-foreground": "oklch(0.985 0 0)",
                "sidebar-accent": "oklch(0.97 0 0)",
                "sidebar-accent-foreground": "oklch(0.205 0 0)",
                "sidebar-border": "oklch(0.922 0 0)",
                "sidebar-ring": "oklch(0.708 0 0)",

                chart1: "oklch(0.81 0.1 252)",
                chart2: "oklch(0.62 0.19 260)",
                chart3: "oklch(0.55 0.22 263)",
                chart4: "oklch(0.49 0.22 264)",
                chart5: "oklch(0.42 0.18 266)",
            },
            fontFamily: {
                sans: [
                    "Montserrat",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                    "Apple Color Emoji",
                    "Segoe UI Emoji",
                    "Segoe UI Symbol",
                    "Noto Color Emoji",
                ],
                sora: [
                    "Sora",
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                    "Apple Color Emoji",
                    "Segoe UI Emoji",
                    "Segoe UI Symbol",
                    "Noto Color Emoji",
                ],
                admin: ["Inter", "sans-serif"],
            },
            container: {
                center: true,
                padding: '5%',
                screens: {
                    sm: '90%',
                    md: '90%',
                    lg: '90%',
                    xl: '90%',
                },
            },
            maxWidth: {
                'small': '58rem',
                'medium': '84rem',
                'large': '104rem',
                'x-large': '110rem',
            },
            borderRadius: {
                DEFAULT: "0.725rem",
            },
            boxShadow: {
                "2xs": "0 1px 3px 0px hsl(0 0% 0% / 0.05)",
                xs: "0 1px 3px 0px hsl(0 0% 0% / 0.05)",
                sm: "0 1px 3px 0px hsl(0 0% 0% / 0.1), 0 1px 2px -1px hsl(0 0% 0% / 0.1)",
                DEFAULT:
                    "0 1px 3px 0px hsl(0 0% 0% / 0.1), 0 1px 2px -1px hsl(0 0% 0% / 0.1)",
                md: "0 1px 3px 0px hsl(0 0% 0% / 0.1), 0 2px 4px -1px hsl(0 0% 0% / 0.1)",
                lg: "0 1px 3px 0px hsl(0 0% 0% / 0.1), 0 4px 6px -1px hsl(0 0% 0% / 0.1)",
                xl: "0 1px 3px 0px hsl(0 0% 0% / 0.1), 0 8px 10px -1px hsl(0 0% 0% / 0.1)",
                "2xl": "0 1px 3px 0px hsl(0 0% 0% / 0.25)",
            },
            keyframes: {
                'slide-in-bottom': {
                    '0%': { transform: 'translateY(100%)' },
                    '100%': { transform: 'translateY(0)' },
                },
                'fade-in-down': {
                    '0%': { opacity: '0', transform: 'translate3d(0,-100px,0)' },
                    '100%': { opacity: '1', transform: 'none' },
                },
                'fade-out-down': {
                    '0%': { opacity: '1', transform: 'none' },
                    '100%': { opacity: '0', transform: 'translate3d(0, 100px,0)' },
                }
            },
            animation: {
                'slide-in-bottom': 'slide-in-bottom 300ms ease-out',
                'fade-in-down': 'fade-in-down 200ms linear',
                'fade-out-down': 'fade-out-down 200ms linear'
            },
        },
    },

    plugins: [
        forms,
        function ({ addComponents }) {
            addComponents({
                "p + p": {
                    marginTop: "0.6rem",
                },
                "ul > li > p": {
                    display: "contents",
                },
                "strong, b": {
                    fontWeight: "bold",
                },
            });
        },
    ],
};
