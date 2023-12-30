/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.php",
    "./story/**/*.php",
    "./public/**/*.{php,js}",
    "./profile/**/*.php",
    "./error/**/*.php",
    "./auth/**/*.php",
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  plugins: [],
}

