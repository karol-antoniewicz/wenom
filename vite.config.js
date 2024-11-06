import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import * as os from 'os'



import Icons from "unplugin-icons/vite"
import IconsResolver from 'unplugin-icons/resolver'
import Components from 'unplugin-vue-components/vite'

export default defineConfig({

	esbuild: {
		minify: false,
		minifySyntax: false
	},
    // server: {
    //     // host: 'https://webnotenmanager.test',
    //     host: 'localhost',
    //     port: 5173,
    // },
    server: {
        https: false,
		host: process.env.LARAVEL_SAIL ? Object.values(os.networkInterfaces()).flat().find(info => info?.internal === false)?.address : undefined,
	},

    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.ts',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
		Components({
			globs: ["src/components/**/!(*story.vue)*.vue"],
			dirs: ['src/components'],

			resolvers: [
				IconsResolver({
					prefix: false,
					enabledCollections: ['ri'],

				})
			],
		}),
		Icons({
			autoInstall: false,
            scales: {
                ri: true,
                mdi: false,
                // Weitere Icon-Sets hier aktivieren, wenn benötigt
            },
            compiler: 'vue3',
		})
    ],

    resolve: {
        dedupe: ["vue"],
        alias: {
            '@': '/resources/js',
        }
    }
});
