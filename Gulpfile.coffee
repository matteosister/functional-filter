gulp = require('gulp')
exec = require('child_process').exec

gulp.task 'phpspec', (cb) ->
    exec 'bin/phpspec run --format=dot', (err, stdout) ->
        if (err)
            console.log err
        console.log stdout
        cb()

gulp.task 'watch', (event) ->
    watcher = gulp.watch ['**/*.php'], ['phpunit']
    watcher.on 'change', (event) ->

gulp.task 'default', ['watch']