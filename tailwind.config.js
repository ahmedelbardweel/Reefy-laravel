/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#13ec13",
        "primary-dark": "#0ea80e",
        "primary-content": "#052e05",
        "background-light": "#f6f8f6",
        "background-dark": "#102210",
        "surface-light": "#ffffff",
        "surface-dark": "#1a2e1a",
        "text-main-light": "#111811",
        "text-main-dark": "#e0e6e0",
        "text-sub-light": "#618961",
        "text-sub-dark": "#8aa38a",
        "subtle-border": "#dbe6db",
        "subtle-border-dark": "#2a422a",
        "card-light": "#ffffff",
        "card-dark": "#162e16",
      },
      fontFamily: {
        "display": ["Space Grotesk", "Noto Sans Arabic", "sans-serif"],
        "body": ["Noto Sans Arabic", "sans-serif"],
      },
      borderRadius: {
        "none": "0",
        "sm": "0",
        "DEFAULT": "0",
        "md": "0",
        "lg": "0",
        "xl": "0",
        "2xl": "0",
        "3xl": "0",
        "full": "9999px",
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/container-queries'),
  ],
}
