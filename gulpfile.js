var elixir = require('laravel-elixir');
    elixir.config.sourcemaps = false;
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

/*elixir(function(mix) {
    mix.sass('app.scss');
});
*/
/*
elixir(function(mix) {
    mix.styles([
        "site.css",
        "plan.css"
    ], "public/assets/css");
});
*/
elixir(function(mix) {
        mix.version(['assets/css/admin.css',  'assets/css/index.css',  'assets/css/login.css',  'assets/css/plan.css', 
                     'assets/css/site.css','assets/js/admin.admin.js',    'assets/js/admin.idea.js',  'assets/js/app.index.js',  'assets/js/app.plan.js',
          'assets/js/admin.balance.js',  'assets/js/admin.user.js',  'assets/js/app.js',        'assets/js/app.report.js']);
        });
