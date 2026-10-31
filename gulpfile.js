/**
 * Gulp build do tema Neblina.
 * Compila styles/scss/ -> assets/css/ com minificação.
 * Comandos:
 *   gulp sass    -> build único
 *   gulp watch   -> fica observando mudanças
 *   gulp         -> build + watch (default)
 */
const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');

const paths = {
  scss: 'styles/scss/**/*.scss',
  cssDest: 'assets/css'
};

function compileSass() {
  return gulp.src(paths.scss)
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(gulp.dest(paths.cssDest));
}

function watchFiles() {
  gulp.watch(paths.scss, compileSass);
}

exports.sass = compileSass;
exports.watch = watchFiles;
exports.default = gulp.series(compileSass, watchFiles);
