const gulp              = require('gulp');
const sass              = require('gulp-sass')(require('sass'));
const browserSync       = require('browser-sync').create();
const rename 		    = require('gulp-rename');
const cssnano	        = require('gulp-cssnano');
const sourcemaps        = require('gulp-sourcemaps');
const uglify            = require('gulp-uglify');
const plumber           = require('gulp-plumber');
const concat            = require('gulp-concat');

// Styles Task
function styles() {
    return gulp.src('resources/scss/**/*.scss')
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(cssnano())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/css'))
        .pipe(browserSync.stream());
}

// Watch Task
function watch() {
    browserSync.init({
        proxy: "127.0.0.1:8089",
        notify: false
    });
    
    gulp.watch('resources/scss/**/*.scss', styles);
    gulp.watch('resources/views/**/*.blade.php').on('change', browserSync.reload);
}

exports.styles = styles;
exports.watch = watch;
exports.default = styles;
