const { src, dest, parallel } = require('gulp');
// const pug = require('gulp-pug');
// const less = require('gulp-less');
const minifyCSS = require('gulp-csso');
const concat = require('gulp-concat');

// function html() {
//     return src('client/templates/*.pug')
//         .pipe(pug())
//         .pipe(dest('build/html'))
// }

// function css() {
//     return src('public/includes/css/*.css')
//         // .pipe(less())
//         .pipe(minifyCSS())
//         .pipe(dest('public/dist/css/'))
// }

function css () {
  return src([
    'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
    'node_modules/bootswatch/dist/**/bootstrap.min.css',
    'node_modules/@fullcalendar/core/main.min.css',
    'node_modules/@fullcalendar/list/main.min.css',
    'node_modules/@fullcalendar/bootstrap/main.min.css',
    'node_modules/@fullcalendar/daygrid/main.min.css',
    'node_modules/cookieconsent/build/cookieconsent.min.css',
    'node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css',
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css'
  ], {
    base: 'node_modules/'
  })
  // .pipe(less())
  // .pipe(minifyCSS())
    .pipe(dest('public/dist/'))
}

function fullcalendarCss () {
  return src([
    'node_modules/@fullcalendar/core/main.min.css',
    'node_modules/@fullcalendar/list/main.min.css',
    'node_modules/@fullcalendar/bootstrap/main.min.css',
    'node_modules/@fullcalendar/daygrid/main.min.css'
  ], {
    base: 'node_modules/'
  })
  // .pipe(less())
    .pipe(concat('fullcalendar.min.css'))
    .pipe(minifyCSS())
    .pipe(dest('public/dist/merged/@fullcalendar/'))
}

function js () {
  return src([
    'node_modules/moment/min/moment.min.js',
    'node_modules/moment/locale/nl.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/cookieconsent/build/cookieconsent.min.js',
    'node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js',
    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js'
  ], {
    base: 'node_modules/'
  })
    .pipe(dest('public/dist/'))
}

function fullcalendarJs () {
  return src([
    'node_modules/@fullcalendar/core/main.min.js',
    'node_modules/@fullcalendar/list/main.min.js',
    'node_modules/@fullcalendar/bootstrap/main.min.js',
    'node_modules/@fullcalendar/daygrid/main.min.js',
    'node_modules/@fullcalendar/interaction/main.min.js',
    'node_modules/@fullcalendar/core/locales/nl.js'
  ], {
    base: 'node_modules/'
  })
    .pipe(concat('fullcalendar.min.js'))
    .pipe(dest('public/dist/merged/@fullcalendar/'))
}

function woff () {
  return src('node_modules/**/*.woff', {
    base: 'node_modules/'
  })
    .pipe(dest('public/dist/'))
}
function woff2 () {
  return src('node_modules/**/*.woff2', {
    base: 'node_modules/'
  })
    .pipe(dest('public/dist/'))
}
function ttf () {
  return src('node_modules/**/*.ttf', {
    base: 'node_modules/'
  })
    .pipe(dest('public/dist/'))
}

exports.js = js;
exports.css = css;
exports.woff = woff;
exports.woff2 = woff2;
exports.ttf = ttf;
exports.default = parallel(js, css, fullcalendarJs, fullcalendarCss, woff, woff2, ttf);
