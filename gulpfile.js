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
	return gulp.src('./public/scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./public/css'))
		;
});
gulp.task('sass:prod', function() {
	return gulp.src('./public/scss/style.scss')
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(gulp.dest('./public/css'))
		.pipe(cssmin())
		.pipe(gulp.dest('./public/css'))
		;
});

gulp.task('sass:watch', function() {
	gulp.watch('./public/scss/**/*.scss', ['sass:dev']);
});

gulp.task('default', ['clean:css','sass:dev','sass:watch']);
gulp.task('prod', ['clean:css','sass:prod']);
