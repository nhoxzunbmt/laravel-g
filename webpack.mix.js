const { mix } = require('laravel-mix')

mix.js('resources/assets/js/app.js', 'public/js')

/*mix.copy([
    'resources/assets/bower/morris.js/morris.css',
    'resources/assets/bower/datatables/media/css/jquery.dataTables.min.css',
    'resources/assets/bower/jquery-toast-plugin/dist/jquery.toast.min.css',
    'resources/assets/bower/bootstrap-select/dist/css/bootstrap-select.min.css',
    'resources/assets/bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    'resources/assets/bower/bootstrap/dist/css/bootstrap.min.css',
    'resources/assets/css/font-awesome.min.css',
    'resources/assets/css/themify-icons.css',
    'resources/assets/css/animate.css',
    'resources/assets/css/simple-line-icons.css',	
    'resources/assets/css/linea-icon.css',	
    'resources/assets/css/pe-icon-7-stroke.css',
    'resources/assets/css/pe-icon-7-styles.css',
    'resources/assets/css/material-design-iconic-font.min.css',	
    'resources/assets/css/filter.css',        
    'resources/assets/bower/owl.carousel/dist/assets/owl.carousel.min.css',
    'resources/assets/bower/owl.carousel/dist/assets/owl.theme.default.min.css',
    'resources/assets/css/lightgallery.css',
    'resources/assets/bower/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
    'node_modules/select2/dist/css/select2.css'
], 'public/css/vendor.css')*/

mix.sass('resources/assets/sass/app.scss', 'public/css')

mix.sourceMaps()
mix.disableNotifications()

if (mix.config.inProduction) {
  mix.version()
}