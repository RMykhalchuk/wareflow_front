const mix = require('laravel-mix')
const exec = require('child_process').exec
require('dotenv').config()
const glob = require('glob')
const path = require('path')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
    (glob.sync('resources/' + query) || []).forEach(f => {
        f = f.replace(/[\\\/]+/g, '/')
        cb(f, f.replace('resources', 'public'))
    })
}

const sassOptions = {
    precision: 5,
    includePaths: ['node_modules', 'resources/assets/']
}

// plugins Core stylesheets
mixAssetsDir('scss/base/plugins/**/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
    // mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.min.css'), { sassOptions })
)

// pages Core stylesheets
mixAssetsDir('scss/base/pages/**/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
    // mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.min.css'), { sassOptions })
)

// Core stylesheets
mixAssetsDir('scss/base/core/**/!(_)*.scss', (src, dest) =>
    mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.css'), { sassOptions })
    //mix.sass(src, dest.replace(/(\\|\/)scss(\\|\/)/, '$1css$2').replace(/\.scss$/, '.min.css'), { sassOptions })
)

// script js
mixAssetsDir('js/scripts/**/*.js', (src, dest) => mix.scripts(src, dest))

// assets js - webpack entry points (entity, grid pages, modules)
// utils/, localization/, grid/components/ are native ES modules imported directly
// by blade templates — they must be copied as-is to preserve native export syntax

// todo with document and tasks
mixAssetsDir('assets/js/**/*.js', (src, dest) => {
    if (
        !src.includes('/_unused/') &&
        !src.endsWith('assets/js/scripts.js') &&
        !src.includes('/assets/js/utils/') &&
        !src.includes('/assets/js/localization/') &&
        !src.includes('/assets/js/grid/components/') &&
        !src.endsWith('/entity/panels/checkUrl.js') &&
        !src.endsWith('/entity/sku/packaging-tree-table.js') &&
        !src.endsWith('/entity/sku/kits-table.js') &&
        !src.includes('/entity/document/') &&
        !src.includes('/grid/document/') &&
        !src.includes('/grid/tasks/') &&
        !src.includes('/entity/tasks/') &&
        !src.includes('/entity/warehouse/warehouse-map/')

) {
        mix.js(src, dest)
    }
})

// assets js - native ES module utilities (copy as-is to preserve export syntax)
mix.copyDirectory('resources/assets/js/utils', 'public/assets/js/utils')
mix.copyDirectory('resources/assets/js/localization', 'public/assets/js/localization')
mix.copyDirectory('resources/assets/js/grid/components', 'public/assets/js/grid/components')

// assets js - legacy global scripts (no export, loaded as plain <script> in blade)
mix.copy('resources/assets/js/entity/panels/checkUrl.js', 'public/assets/js/entity/panels/checkUrl.js')

// assets js - native ES modules inside entity/ (copy as-is to preserve export syntax)
mix.copy('resources/assets/js/entity/sku/packaging-tree-table.js', 'public/assets/js/entity/sku/packaging-tree-table.js')
mix.copy('resources/assets/js/entity/sku/kits-table.js', 'public/assets/js/entity/sku/kits-table.js')
mix.copyDirectory('resources/assets/js/entity/document', 'public/assets/js/entity/document')
mix.copyDirectory('resources/assets/js/grid/document', 'public/assets/js/grid/document')
mix.copyDirectory('resources/assets/js/grid/tasks', 'public/assets/js/grid/tasks')
mix.copyDirectory('resources/assets/js/entity/tasks', 'public/assets/js/entity/tasks')
mix.copyDirectory('resources/assets/js/entity/warehouse/warehouse-map/', 'public/assets/js/entity/warehouse/warehouse-map/')

// mixAssetsDir('js/scripts/**/*.js', (src, dest) =>
//     mix.scripts(src, dest.replace(/\.js$/, '.min.js'))
// )

/*
 |--------------------------------------------------------------------------
 | Application assets
 |--------------------------------------------------------------------------
 */

mixAssetsDir('vendors/js/**/*.js', (src, dest) => mix.scripts(src, dest))
mixAssetsDir('vendors/css/**/*.css', (src, dest) => mix.copy(src, dest))

// mixAssetsDir('vendors/js/**/*.js', (src, dest) =>
//     mix.scripts(src, dest.replace(/\.js$/, '.min.js'))
// )
// mixAssetsDir('vendors/css/**/*.css', (src, dest) =>
//     mix.copy(src, dest.replace(/\.css$/, '.min.css'))
// )

mixAssetsDir('vendors/**/**/images', (src, dest) => mix.copy(src, dest))
mixAssetsDir('vendors/css/editors/quill/fonts/', (src, dest) => mix.copy(src, dest))
mixAssetsDir('fonts', (src, dest) => mix.copy(src, dest))
mixAssetsDir('fonts/**/**/*.css', (src, dest) => mix.copy(src, dest))
// mixAssetsDir('fonts/**/**/*.css', (src, dest) =>
//     mix.copy(src, dest.replace(/\.css$/, '.min.css'))
// )
mix.copyDirectory('resources/images', 'public/images')
mix.copyDirectory('resources/data', 'public/data')

mix
    .js('resources/js/core/app-menu.js', 'public/js/core')
    .js('resources/js/core/app.js', 'public/js/core')
    .js('resources/assets/js/scripts.js', 'public/js/core')
    .react('resources/assets/js/react/app.jsx', 'public/js/react/containers-bundle.js')
    .sass('resources/scss/base/themes/dark-layout.scss', 'public/css/base/themes', {sassOptions})
    .sass('resources/scss/base/themes/bordered-layout.scss', 'public/css/base/themes', {sassOptions})
    .sass('resources/scss/base/themes/semi-dark-layout.scss', 'public/css/base/themes', {sassOptions})
    .sass('resources/scss/core.scss', 'public/css', {sassOptions})
    .sass('resources/scss/overrides.scss', 'public/css', {sassOptions})
    .sass('resources/scss/base/custom-rtl.scss', 'public/css-rtl', {sassOptions})
    .sass('resources/assets/scss/style-rtl.scss', 'public/css-rtl', {sassOptions})
    .sass('resources/assets/scss/style.scss', 'public/css', {sassOptions})

    // .js('resources/js/core/app-menu.js', 'public/js/core/app-menu.min.js')
    // .js('resources/js/core/app.js', 'public/js/core/app.min.js')
    // .js('resources/assets/js/scripts.js', 'public/js/core/scripts.min.js')
    //
    // // Themes
    // .sass('resources/scss/base/themes/dark-layout.scss', 'public/css/base/themes/dark-layout.min.css', { sassOptions })
    // .sass('resources/scss/base/themes/bordered-layout.scss', 'public/css/base/themes/bordered-layout.min.css', { sassOptions })
    // .sass('resources/scss/base/themes/semi-dark-layout.scss', 'public/css/base/themes/semi-dark-layout.min.css', { sassOptions })
    //
    // // Core styles
    // .sass('resources/scss/core.scss', 'public/css/core.min.css', { sassOptions })
    // .sass('resources/scss/overrides.scss', 'public/css/overrides.min.css', { sassOptions })
    //
    // // RTL styles
    // .sass('resources/scss/base/custom-rtl.scss', 'public/css-rtl/custom-rtl.min.css', { sassOptions })
    // .sass('resources/assets/scss/style-rtl.scss', 'public/css-rtl/style-rtl.min.css', { sassOptions })
    // .sass('resources/assets/scss/style.scss', 'public/css/style.min.css', { sassOptions })


mix.then(() => {
    if (process.env.MIX_CONTENT_DIRECTION === 'rtl') {
        let command = `node ${path.resolve('node_modules/rtlcss/bin/rtlcss.js')} -d -e ".css" ./public/css/ ./public/css/`
        exec(command, function (err, stdout, stderr) {
            if (err !== null) {
                console.log(err)
            }
        })
    }
})

/*
 |--------------------------------------------------------------------------
 | Browsersync Reloading
 |--------------------------------------------------------------------------
 |
 | BrowserSync can automatically monitor your files for changes, and inject your changes into the browser without requiring a manual refresh.
 | You may enable support for this by calling the mix.browserSync() method:
 | Make Sure to run `php artisan serve` and `yarn watch` command to run Browser Sync functionality
 | Refer official documentation for more information: https://laravel.com/docs/9.x/mix#browsersync-reloading
 */

mix.browserSync('http://127.0.0.1/')

// Enable versioning (cache busting) only in production
if (mix.inProduction()) {
    mix.version()
} else {
    // source maps в dev (щоб в браузері бачив оригінальний код при дебагу, а не мініфікований)
    mix.sourceMaps()
}
