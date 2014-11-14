module.exports = (grunt)->
    grunt.initConfig
        coffee:
            all:
                options:
                    bare: true
                expand: true,
                cwd:    'app/assets/coffeescript',
                src:    '**/*.coffee',
                dest:   'public/js/assets',
                ext:    '.js'
        watch:
            coffee:
                files: '**/*.coffee',
                tasks: ['coffee']
            sass:
                files: '**/*.scss',
                tasks: ['compass']

        uglify:
            all:
                files:[
                    expand: true,
                    cwd:    'public/js/assets',
                    src:    '*.js',
                    dest:   'public/js/assets'
                ]
        compass:
            all:
                options:
                    sassDir: 'app/assets/sass',
                    cssDir:  'public/css/assets',
                    importPath: 'app/assets/sass/import',
                    imagesDir: 'public/img',
                    httpImagesPath: '/img',
                    fontsPath: 'public/fonts',
                    httpFontsPath: '/fonts',
                    environment: 'production'

    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-uglify'
    grunt.loadNpmTasks 'grunt-contrib-compass'

    grunt.registerTask 'default', ['coffee', 'uglify', 'compass']