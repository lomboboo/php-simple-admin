'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var runSequence = require('run-sequence');

require('require-dir')('./gulp-tasks');

gulp.task('sass', ['compile-vendors'], function() {
	return gulp.src('./scss/style.scss')
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(gulp.dest('./css'))
		.pipe(cssmin())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest('./css'))
		;
});

gulp.task('sass:watch', function() {
	gulp.watch('./scss/**/*.scss', ['sass']);
});

gulp.task('default', ['sass:watch']);
