# PHP server for processing PDF forms with PDFTK
I created some code for heroku that processes a PDF form that I converted to a fillable form with Acrobat. It's written in php, and being hosted on heroku.

## Heroku Buildpacks

- APT packages | `heroku-community/apt`
- Java Virtual Machine | `heroku/jvm`
- [PDFTK Java](https://github.com/codeforamerica/heroku-pdftk-java-buildpack) | `https://github.com/codeforamerica/heroku-pdftk-java-buildpack.git`
- PHP | `heroku/php`
