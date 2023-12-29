/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
  "./resources/**/*.blade.php",
  "./resources/**/*.js",
  "./resources/**/*.vue",
],
safelist: [
  'bg-pink-200',
  'bg-pink-500',
  'bg-purple-200',
  'bg-purple-500',
  'bg-cyan-200',
  'bg-cyan-500',
],
  theme: {
    extend: {
      colors: {
        cyan: {
          '100': '#E0FFFF',
          '200': '#AFEEEE',
          '300': '#7FFFD4',
          '400': '#40E0D0',
          '500': '#00CED1',
        },
        pink: {
          '100': '#FFC0CB',
          '200': '#FFB6C1',
          '300': '#FF69B4',
          '400': '#FF1493',
          '500': '#DB7093',
        },
        brown: {
          50: '#fdf8f6',
          100: '#f2e8e5',
          200: '#eaddd7',
          300: '#e0cec7',
          400: '#d2bab0',
          500: '#bfa094',
          600: '#a18072',
          700: '#977669',
          800: '#846358',
          900: '#43302b',
        },
        purple: {
          '100': '#E6E6FA',
          '200': '#D8BFD8',
          '300': '#DDA0DD',
          '400': '#DA70D6',
          '500': '#BA55D3',
        }

      },
    },
  },
  plugins: [],
}
