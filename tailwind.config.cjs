const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./templates/**/*.{twig,js}"],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Karla', 'Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'zebrelina-light-blue': '#C1E3E9',
                'zebrelina-dark-blue':  '#79B3C5',
                'zebrelina-light-yellow':  '#F8F2D3',
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
