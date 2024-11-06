const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
	prefix: '', // For WeNoM
	darkMode: 'class',
	content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/Pages/Auth/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'chalk': "url('@/Images/chalk.jpg')",
            },
			colors: {
				grey: '#f5f5f5',
                sortingblue: '#329cd5',
			},
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
};
