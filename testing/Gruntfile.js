module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            options: {
                sourceMap: true,
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            build: {
                src: ['build/js/concatFiles.js'],
                dest: 'build/js/concatFiles.min.js'
            }
        },
        concat: {
            dist: {
                src: ['test_concat_1.js','test_concat_2.js'],
                dest: 'build/js/concatFiles.js'
            }
        },
        
        
//        // Concatenate/minify JS and CSS in files; replace references (preparation)
//        useminPrepare: {
//            html: [
//                'build/pages/page_home.php',
////                'build/pages/page_medocs.php',
////                'build/pages/page_dataportal.php'
//            ],
//            options: {
//                root: '/LastMileData/',
////                dest: '/LastMileData/'
////                root: '../',
//                dest: '../'
//            }
//        },
        
//        // Concatenate/minify JS and CSS in files; replace references (execution)
//        usemin: {
//            html: [
//                'build/pages/page_home.php',
////                'build/pages/page_medocs.php',
////                'build/pages/page_dataportal.php'
//            ]
//        },

        
        
        
        cssmin: {
            options: {
                sourceMap: true
            },
            target: {
                files: [{
                    expand: true,
//                    cwd: '/',
                    src: ['*.css', '!*.min.css'],
                    dest: 'build/css',
                    ext: '.min.css'
                }]
            }
        }
        
        
        
        
        
//        watch: {
//            options: {
//                livereload: true
//            }
//            scripts: {
//                files: ['/*.js'],
//                tasks: ['concat', 'uglify'],
//                options: {
//                    spawn: false,
//                }
//            }
//        }
    });

//    grunt.loadNpmTasks('grunt-contrib-useminPrepare');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
//    grunt.loadNpmTasks('grunt-contrib-usemin');
//    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default tasks
    grunt.registerTask('default', [
        'cssmin',
        'concat',
        'uglify',
//        'useminPrepare',
//        'cssmin:generated',
//        'concat:generated',
//        'uglify:generated',
//        'usemin'
    ]);
//    grunt.registerTask('default', ['cssmin','concat','uglify']);

};
