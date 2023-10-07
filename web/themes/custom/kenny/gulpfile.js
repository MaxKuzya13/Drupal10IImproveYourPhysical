var gulp = require('gulp');
var sass = require('gulp-sass')(require('sass'));
var autoprefixer = require('gulp-autoprefixer');

// Source Sass files
var SASS = 'scss';
// Output directory
var CSS = 'css';

// Building scss.
gulp.task('scss', done => {
  gulp.src(SASS + '/**/*.scss')
    .pipe(sass.sync({}).on('error', sass.logError))
    .pipe(autoprefixer({}))
    .pipe(gulp.dest(CSS));
  done();
});

// Build task: 'gulp build'
gulp.task('build', gulp.series('scss'));
// Build task: 'gulp watch'
gulp.task('watch', function(){
  return gulp.watch(SASS + '/**/*.scss', gulp.series('scss'));
});

// Default task: 'gulp'
gulp.task('default', gulp.series('build', 'watch'));
