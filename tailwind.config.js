const plugin = require('tailwindcss-animate');

module.exports = {
  content: [
    './index.html',
    './main.jsx',
    './src/Components/**/*.{js,jsx}',
    './src/Pages/**/*.{js,jsx}',
    "./projects/**/*.{html,js,jsx,ts,tsx}"
  ],
  theme: {
    extend: {
      fontFamily: {
        signature: ['Dancing Script', 'cursive'],
      }
    },
  },
  plugins: [plugin],
}
