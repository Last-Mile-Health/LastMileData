module.exports = function(grunt) {
    
    // !!!!! Description here
    // !!!!! Description here
    // !!!!! Description here
    
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        clean: {
            // Clear all files from "build" directory
            initial: [
                'build/**/*'
            ]
//            ],
//            // !!!!!
//            second: [
//                'src/js/concatFiles.min.js',
//            ]
        },
        
        // !!!!! Task description !!!!!
        copy: {
            // Copy all files from "src" --> "build"
            initial: {
                files: [
                    {expand:true, cwd:'src', src:['**/*','.htaccess'], dest:'build'}
                ]
            }
//            },
//            test: {
//                src: 'src/js/test_index.html',
//                dest: 'src/js/test_index_BUILD.html'
//            },
        },
        
        // !!!!! Task description !!!!!
        useminPrepare: {
            html: [
                'build/php-html/pages/page_home.php',
                'build/php-html/pages/page_medocs.php',
                'build/php-html/pages/page_deqa.html',
                'build/php-html/pages/page_dataportal.php'
            ],
            options: {
                root: '../',
                dest: '../'
            }
        },
        
        // !!!!! Task description !!!!!
        uglify: {
            options: {
                sourceMap: true,
                banner: '/*! <%= pkg.name %> build: <%= grunt.template.today("yyyy-mm-dd hh:dd:ss") %> */\n'
            }
        },

        // !!!!! Task description !!!!!
        filerev: {
            main: {
                src: 'src/js/concatFiles.min.js'
//                dest: 'src/js'
            }
        },
        
        // !!!!! Task description !!!!!
        usemin: {
            html: [
                'build/php-html/pages/page_home.php',
                'build/php-html/pages/page_medocs.php',
                'build/php-html/pages/page_deqa.html',
                'build/php-html/pages/page_dataportal.php'
            ]
        },

        // !!!!! Task description !!!!!
        htmlmin: {
            main: {
                files: {
                    'build/php-html/pages/page_deqa.html': 'build/php-html/pages/page_deqa.html',
                    'build/php-html/pages/page_dataportal.php': 'build/php-html/pages/page_dataportal.php'
                },
                options: {
                    collapseBooleanAttributes: true,
                    collapseWhitespace: true,
                    removeAttributeQuotes: true,
                    removeComments: true,
                    removeRedundantAttributes: true
                }
            }
        },

        // !!!!! Task description !!!!!
        manifest: {
            main: {
                src: [
                    "src/js/*.js",
                    "src/forms",
                    "src/php",
                    "src/images/*.png"
                ],
                dest: 'src/lastmiledata_generated.appcache',
                options: {
//                    basePath: 'src',
                    verbose: false
                }
            }
        }
        
//        'string-replace': {
//            dist: {
//                files: {
//                    'src/js/test_index2.html': 'src/js/test_index.html'
//                },
//                options: {
//                    replacements: [
//                        {
//                            pattern: '!!!!! old !!!!!',
//                            replacement: '????? new ?????'
//                        }
//                    ]
//                }
//            }
//        },


//        
////        // Concatenate javascript files
////        concat: {
////            dist: {
////                src: ['src/js/LMD_accessControl.js','src/js/LMD_dataPortal.js'],
//////                dest: 'src/js/concatFiles.js'
////                dest: '.tmp/concatFiles.js'
////            }
////        },
////        // Minify javascript files
////        uglify: {
////            options: {
////                sourceMap: true,
////                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
////            },
////            build: {
//////                src: ['src/js/concatFiles.js'],
////                src: ['.tmp/concatFiles.js'],
////                dest: 'src/js/concatFiles.min.js'
////            }
////        }
////        cssmin: {
////            options: {
////                sourceMap: true
////            },
////            target: {
////                files: [{
////                    expand: true,
//////                    cwd: '/',
////                    src: ['*.css', '!*.min.css'],
////                    dest: ['build/css'],
////                    ext: '.min.css'
////                }]
////            }
////        }
////        
////        
////        
////        watch: {
////            options: {
////                livereload: true
////            }
////            scripts: {
////                files: ['/*.js'],
////                tasks: ['concat', 'uglify'],
////                options: {
////                    spawn: false,
////                }
////            }
////        }
    });
//
////    grunt.loadNpmTasks('grunt-contrib-useminPrepare');
//



    // Automatically run "grunt.loadNpmTasks" for all plugins
    require('load-grunt-tasks')(grunt);
    
    // Register default tasks
    grunt.registerTask('default', [
        'clean:initial',
        'copy:initial',
        'useminPrepare',
        'concat:generated',
        'cssmin:generated',
        'uglify:generated',
////        'filerev',
        'usemin',
        'htmlmin'
//        'manifest'
    ]);

};
