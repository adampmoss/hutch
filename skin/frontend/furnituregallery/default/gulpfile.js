/****************************
 *
 * Boilerplate gulpfile.js for custom Magento 1.9+ theme development
 * Authors: Adam Moss / Dan Long
 *
 * Suggested Theme Contents & Structure:
 *  /css
 *  /images
 *  /js
 *  /js/lib
 *  /scss
 *  .config.rb
 *  gulpfile.js
 *  package.json
 *
 * More Information:
 * http://www.creare.co.uk/magento-gulpfile-js-boilerplate
 ****************************/


// Plugins

var
    gulp = require('gulp'),
    compass = require('gulp-compass');
    autoprefixer = require('gulp-autoprefixer');
    imageop = require('gulp-image-optimization');
    uglify = require('gulp-uglify');
    concat = require('gulp-concat');
    rename = require('gulp-rename');
    minifyCSS = require('gulp-minify-css');


// Various Filepaths

var paths = {
    scss: ['scss/*.scss', 'scss/*/*.scss'],
    images: ['images/*.png','images/*.jpg','images/*.gif','images/*.jpeg', 'images/*/*.png','images/*/*.jpg','images/*/*.gif','images/*/*.jpeg'],
    js: ['js/*.js', 'js/*/*.js', '!js/*.min.js', '!js/*/*.min.js']
};


// Sass Compilation
// Compiles SCSS from your theme's ./scss directory and its fall-backs as declared in ./config.rb

gulp.task('compass', function() {
    gulp.src('scss/*.scss')
        .pipe(compass({
            config_file: 'config.rb',
            css: 'css',
            sass: 'scss'
        }))
        .pipe(autoprefixer())
        .pipe(minifyCSS())
        .pipe(gulp.dest('css'))
});


// JavaScript Minification
// Compresses JavaScript from your theme and saves a .min.js in the same ./js directory

gulp.task('js', function() {
    return gulp.src(paths.js)
        .pipe(uglify())
        .pipe(rename({
            extname: '.min.js'
        }))
        .pipe(gulp.dest('js'));
});


// JavaScript Concatenation
// Merges any JavaScript files within ./js/lib into one file called ./js/libraries.js

gulp.task('libraries', function() {
    return gulp.src('./js/lib/*.min.js')
        .pipe(concat('libraries.min.js'))
        .pipe(gulp.dest('./js'));
});


//Images
// Compresses image files from your theme's ./images directory

gulp.task('images', function() {
    gulp.src(paths.images).pipe(imageop({
        optimizationLevel: 5,
        progressive: true,
        interlaced: true
    }));
});


// Default task ran with gulp command

gulp.task('default', function() {
    gulp.watch(paths.scss, ['compass']);
    gulp.watch(paths.js, ['js']);
    gulp.watch(paths.js, ['libraries']);
    gulp.watch(paths.images, ['images']);
});