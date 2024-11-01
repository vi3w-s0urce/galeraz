/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {},
    fontFamily: {
      sans: ['"Poppins"', 'sans-serif'],
      mondwest: ['Mondwest', 'sans-serif'],
      hack: ['Hack', 'sans-serif'],
    }
  },
  plugins: [],
}

