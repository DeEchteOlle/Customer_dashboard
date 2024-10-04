/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                customPurple: 'rgb(22, 17, 58)', // Custom color added
            },
        },
    },
    plugins: [],
}


