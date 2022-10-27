// Load Gulp
const { src, dest, task, series, watch, parallel } = require('gulp');

// NodeJS
const path = require('path');
const fs = require('fs');

// CSS related plugins
const sass         = require('gulp-sass')(require('sass'));
const autoprefixer = require( 'gulp-autoprefixer' );
const minifycss    = require( 'gulp-uglifycss' );

// JS related plugins
const concat       = require( 'gulp-concat' );
const uglify       = require( 'gulp-uglify' );
const babelify     = require( 'babelify' );
const browserify   = require( 'browserify' );
const source       = require( 'vinyl-source-stream' );
const buffer       = require( 'vinyl-buffer' );
const stripDebug   = require( 'gulp-strip-debug' );

// Utility plugins
const rename       = require( 'gulp-rename' );
const sourcemaps   = require( 'gulp-sourcemaps' );
const notify       = require( 'gulp-notify' );
const plumber      = require( 'gulp-plumber' );
const options      = require( 'gulp-options' );
const gulpif       = require( 'gulp-if' );

// Browers related plugins - not using it now
const browsersync = require('browser-sync').create();

// Project related variables
const projectURL   = 'https://test.dev';

// Style files
const styleAdmin   = 'src/scss/admin.scss';
const styleForm    = 'src/scss/form.scss';
const styleSlider  = 'src/scss/slider.scss';
const styleAuth    = 'src/scss/auth.scss';
const styleURL     = './assets/css';
const mapURL       = './';

// Script files
const jsSRC        = 'src/js/';
const jsAdmin      = 'admin.js';
const jsForm       = 'form.js';
const jsSlider     = 'slider.js';
const jsAuth       = 'auth.js';
const jsFiles      = [jsAdmin, jsForm, jsSlider, jsAuth]
const jsURL        = './assets/js';

// Watch files
const styleWatch   = 'src/scss/**/*.scss';
const jsWatch      = 'src/js/**/*.js';
const phpWatch     = '**/*.php';

// BrowserSync
function browserSyncServe(done) {
  browserSync.init({
    server: {
      baseDir: './dist/'
    }
  });
  done();
}

function browserSyncReload() {
  return browsersync.reload;
}

// Plumber
function triggerPlumber( src, url ) {
  return src( src )
    .pipe( plumber() )
    .pipe( dest( url ) );
}

function processCSS() {
  return src( [styleAdmin, styleForm, styleSlider, styleAuth] )
    .pipe( sourcemaps.init() )
    .pipe( sass({
      errLogToConsole: true,
      outputStyle: 'compressed'
    }) )
    .on( 'error', console.error.bind( console ) )
    .pipe( autoprefixer(/*{ browsers: [ 'last 2 versions', '> 5%', 'Firefox ESR' ] }*/) )
    .pipe( rename({
      extname: '.min.css'
    }) )
    .pipe( sourcemaps.write( mapURL ) )
    .pipe( dest( styleURL ) )
    /*.pipe( browserSync.stream() )*/;
};

function processJS(done) {
  jsFiles.map( function( entry ) {
    return browserify({
      entries: [jsSRC + entry]
    })
    .transform( babelify, { presets: [ '@babel/preset-env' ] } )
    .bundle()
    .pipe( source( entry ) )
    .pipe( rename({
      extname: '.min.js'
    }) )
    .pipe( buffer() )
    .pipe( gulpif( options.has( 'production' ), stripDebug() ) )
    .pipe( sourcemaps.init({ loadMaps: true }) )
    .pipe( uglify() )
    .pipe( sourcemaps.write( '.' ) )
    .pipe( dest( jsURL ) )
    /*.pipe( browserSync.stream() ); */
  });

  done();
};

function watchFiles() {
  // watch(paths.scss + '**/*.scss', parallel(css))
  // .on('change', browserSyncReload());
  watch( styleWatch, parallel( processCSS ) );
  watch( jsWatch, parallel( processJS ) );
}

const watching = parallel(watchFiles/*, browserSync*/);

exports.js = processJS;
exports.css = processCSS;
exports.default = parallel( processJS , processCSS );
exports.watch = watching;