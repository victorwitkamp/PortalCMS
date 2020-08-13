/*
 * Copyright Victor Witkamp (c) 2020.
 */
const { src, dest, parallel } = require('gulp');
// const less = require('gulp-less');
const minifyCSS = require('gulp-csso');
const concat = require('gulp-concat');
const autoprefixer = require('autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const minify = require('gulp-minify');
const rename = require('gulp-rename');

// function css() {
//     return src('portal/includes/css/*.css')
//         // .pipe(less())
//         .pipe(minifyCSS())
//         .pipe(dest('portal/dist/css/'))
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
    'node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css'
  ], {
    base: 'node_modules/'
  })
  // .pipe(less())
  // .pipe(minifyCSS())
    .pipe(dest('portal/dist/'))
}

function dataTablesCss () {
  return src([
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
    'node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css',
    'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.css'
  ], {
    base: 'node_modules/'
  })
    .pipe(sourcemaps.init())
    .pipe(concat('dataTables.css'))
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('portal/dist/merged/'))
}

function dataTablesMinCss () {
  return src([
    'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
    'node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css',
    'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.css'
  ], {
    base: 'node_modules/'
  })
    .pipe(sourcemaps.init())
    .pipe(concat('dataTables.css'))
    .pipe(postcss([autoprefixer()]))
    .pipe(minifyCSS())
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('portal/dist/merged/'))
}

function dataTablesJs () {
  return src([
    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-select/js/dataTables.select.min.js',
    'node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js',
    'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
    'node_modules/datatables.net-buttons/js/dataTables.buttons.min.js',
    'node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.js'
  ], {
    base: 'node_modules/'
  })
    .pipe(sourcemaps.init())
    .pipe(concat('dataTables.js'))
    .pipe(minify({
      ext: {
        min: '.min.js'
      }
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('portal/dist/merged/'))
}

function fullcalendarMinCss () {
  return src([
    'node_modules/@fullcalendar/core/main.min.css',
    'node_modules/@fullcalendar/list/main.min.css',
    'node_modules/@fullcalendar/bootstrap/main.min.css',
    'node_modules/@fullcalendar/daygrid/main.min.css'
  ], {
    base: 'node_modules/'
  })
    .pipe(concat('fullcalendar.min.css'))
    .pipe(sourcemaps.init())
    .pipe(postcss([autoprefixer()]))
    .pipe(minifyCSS())
    .pipe(sourcemaps.write('.'))
    .pipe(dest('portal/dist/merged/'))
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
    .pipe(concat('fullcalendar.css'))
    .pipe(sourcemaps.init())
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('.'))
    .pipe(dest('portal/dist/merged/'))
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
    .pipe(sourcemaps.init())
    .pipe(concat('fullcalendar.js'))
    .pipe(minify({
      ext: {
        min: '.min.js'
      }
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('portal/dist/merged/'))
}

function js () {
  return src([
    'node_modules/moment/min/moment.min.js',
    'node_modules/moment/locale/nl.js',
    'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/cookieconsent/build/cookieconsent.min.js',
    'node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js',
    'node_modules/bs-custom-file-input/dist/bs-custom-file-input.min.js'
  ], {
    base: 'node_modules/'
  })
    .pipe(dest('portal/dist/'))
}

function woff () {
  return src('node_modules/**/*.woff', {
    base: 'node_modules/'
  })
    .pipe(dest('portal/dist/'))
}
function woff2 () {
  return src('node_modules/**/*.woff2', {
    base: 'node_modules/'
  })
    .pipe(dest('portal/dist/'))
}
function ttf () {
  return src('node_modules/**/*.ttf', {
    base: 'node_modules/'
  })
    .pipe(dest('portal/dist/'))
}
exports.js = js;
exports.css = css;
exports.woff = woff;
exports.woff2 = woff2;
exports.ttf = ttf;
exports.default = parallel(js, css, dataTablesJs, dataTablesCss, dataTablesMinCss, fullcalendarJs, fullcalendarCss, fullcalendarMinCss, woff, woff2, ttf);
