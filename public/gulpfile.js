'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cssmin = require('gulp-cssmin');
var rename = require('gulp-rename');
var runSequence = require('run-sequence');
var sourcemaps = require('gulp-sourcemaps');
const del = require('del');

gulp.task('clean:css', function () {
	del(['./css/*.{css,map}']);
});

gulp.task('sass:dev', function() {
	return gulp.src('./scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./css'))
		;
});
gulp.task('sass:prod', function() {
	return gulp.src('./scss/style.scss')
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(gulp.dest('./css'))
		.pipe(cssmin())
		.pipe(gulp.dest('./css'))
		;
});

gulp.task('sass:watch', function() {
	gulp.watch('./scss/**/*.scss', ['sass:dev']);
});

gulp.task('default', ['clean:css','sass:dev','sass:watch']);
gulp.task('prod', ['clean:css','sass:prod']);
