/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        light: {
          primary: '#3B556D',
          secondary: '#939597',
          success: '#679436',
          warning: '#E9C46A',
          danger: '#A7001E',
          info: '#9AC8EB',
          font: '#eeebeb',
        },
        dark: {
          primary: '#656c8c',
          secondary: '#d3d3d3',
          success: '#5D7052',
          warning: '#F5CB5C',
          danger: '#BD3100',
          info: '#137C8B',
          font: '#333533',
        },
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}