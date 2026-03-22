"use strict";

var path = require("path");
var Transform = require("stream").Transform;
var gulp = require("gulp");
var plumber = require("gulp-plumber");
var sourcemap = require("gulp-sourcemaps");
var rename = require("gulp-rename");
var sass = require("gulp-sass");
var postcss = require("gulp-postcss");
var autoprefixer = require("autoprefixer");
var htmlmin = require("gulp-htmlmin");
var csso = require("gulp-csso");
var uglify = require("gulp-uglify");
var server = require("browser-sync").create();
var imagemin = require("gulp-imagemin");
var webp = require("gulp-webp");
var svgstore = require("gulp-svgstore");
var posthtml = require("gulp-posthtml");
var include = require("posthtml-include");
var del = require("del");

/**
 * Append cache-bust query string to main.css/js URLs in HTML (build output).
 * @param {string} version Query value, e.g. build timestamp.
 * @returns {Transform}
 */
function cacheBustHtml(version) {
  var stream = new Transform({ objectMode: true });
  stream._transform = function (file, encoding, callback) {
    if (file.isNull()) {
      callback(null, file);
      return;
    }
    if (file.isStream()) {
      callback(new Error("cacheBustHtml: streaming is not supported"));
      return;
    }
    if (path.extname(file.path).toLowerCase() !== ".html") {
      callback(null, file);
      return;
    }
    var html = file.contents.toString("utf8");
    html = html.replace(
      /(\/css\/style-min\.css)(\?[^"'>\s]*)?/g,
      "$1?v=" + version
    );
    html = html.replace(
      /(\/js\/main\.js)(\?[^"'>\s]*)?/g,
      "$1?v=" + version
    );
    file.contents = Buffer.from(html, "utf8");
    callback(null, file);
  };
  return stream;
}

// для css
gulp.task("css", function () {
  return gulp.src("source/sass/style.scss")
    .pipe(plumber())
    .pipe(sourcemap.init())
    .pipe(sass())
    .pipe(postcss([
      autoprefixer()
    ]))
    .pipe(csso())
    .pipe(rename("style-min.css"))
    .pipe(sourcemap.write("."))
    .pipe(gulp.dest("build/css"))
    .pipe(server.stream());
});

// для html
gulp.task("html", function () {
  var assetVersion = String(Date.now());
  return gulp.src("source/*.html")
  .pipe(posthtml([
    include()
  ]))
  .pipe(htmlmin({ collapseWhitespace: true }))
  .pipe(cacheBustHtml(assetVersion))
  // .pipe(rename("*-min.html"))
  .pipe(gulp.dest("build"));
});

// для js
gulp.task("js", function () {
  return gulp.src("source/js/**/*.js")
  .pipe(uglify())
  // .pipe(rename("*-min.js"))
  .pipe(gulp.dest("build/js"));
});

// для запуска локального сервера
gulp.task("server", function () {
  server.init({
    server: "build/",
    notify: false,
    open: true,
    cors: true,
    ui: false
  });

  gulp.watch("source/sass/**/*.{scss,sass}", gulp.series("css"));
  gulp.watch("source/img/icon-svg-*.svg", gulp.series("sprite", "html", "refresh"));
  gulp.watch("source/*.html", gulp.series("html", "refresh"));
  gulp.watch("source/js/**/*.js", gulp.series("js", "refresh"));

});

// для перезагрузки локального сервера
gulp.task("refresh", function(done) {
  server.reload();
  done();
});

// для копирования файлов в продакшн папку
gulp.task("copy", function() {
  return gulp.src([
    "source/fonts/**/*.{woff,woff2}",
    "source/img/**",
    "source/js/**",
    "source/*.ico"
  ], {
    base: "source"
  })
  .pipe(gulp.dest("build"));
});

// для оптимизации изображений
gulp.task("images", function() {
  return gulp.src("source/img/**/*.{png,jpg,svg}")
  .pipe(imagemin([
    imagemin.optipng({optimizationLevel: 3}),
    imagemin.jpegtran({progressive: true}),
    imagemin.svgo()
  ]))
  .pipe(gulp.dest("source/img"));
});

// для перевода картинок в webp
gulp.task("webp", function() {
  return gulp.src("source/img/**/*.{png,jpg}")
  // .pipe(webp({quality: 80}))
  .pipe(gulp.dest("source/img"));
});

// для создания svg спрайта
gulp.task("sprite", function() {
  return gulp.src("source/img/**/icon-svg-*.svg")
  .pipe(svgstore({
    inlineSvg: true
  }))
  .pipe(rename("sprite.svg"))
  .pipe(gulp.dest("build/img"));
});

gulp.task("clean", function() {return del("build");});
gulp.task("build", gulp.series("clean", "copy", "css", "webp", "sprite", "html", "js"));
gulp.task("start", gulp.series("build", "server"));
