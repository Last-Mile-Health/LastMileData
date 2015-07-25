module.exports = function(grunt) {

    // Clear and repopulate "build" directory, based on "src" directory

    // !!!!! Add filerev and manifest !!!!!

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        
        clean: {
            // Clear all files from "build" directory
            initial: [
                'build/**/*'
            ],
            // Clear js/css files that have been concatenated/minified
            second: [
                'build/js/page_dataportal.js',
                'build/js/page_medocs.js',
                'build/js/LMD_dataPortal.js',
                'build/js/LMD_dimpleHelper.js',
                'build/css/page_dataportal.css',
                'build/css/page_medocs.css'
            ]
        },
        
        // Copy all files from "src" --> "build"
        copy: {
            initial: {
                files: [
                    {
                        expand:true,
                        cwd:'src',
                        src:['**/*','.htaccess'],
                        dest:'build' }
                ]
            }
        },
        
        // Concatenate/minify JS and CSS in files; replace references (preparation)
        useminPrepare: {
            html: [
                'build/pages/page_home.php',
//                'build/pages/page_medocs.php',
//                'build/pages/page_dataportal.php'
            ],
            options: {
                root: '/LastMileData/',
//                dest: '/LastMileData/'
//                root: '../',
                dest: '../'
            }
        },
        
        // Concatenate/minify JS and CSS in files; replace references (execution)
        usemin: {
            html: [
                'build/pages/page_home.php',
//                'build/pages/page_medocs.php',
//                'build/pages/page_dataportal.php'
            ]
        },

        // Turn on source mapping and apply banner for JS minification
        uglify: {
            options: {
                sourceMap: true,
                banner: '/*! <%= pkg.name %> build: <%= grunt.template.today("yyyy-mm-dd hh:dd:ss") %> */\n'
            },
            main: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: [
                        'js/LMD_fileSystemHelper.js',
                        'js/formHelper.js',
                        'js/fhwForms.js',
                        'js/formValidate.js',
                        'js/loadContents.js',
                        'js/LMD_accessControl.js',
                        'js/deqa.js',
                        'js/leafletMap.js'
                    ],
                    dest: 'build/'
                }]
            }
        },

        // Turn on source mapping for CSS minification
        cssmin: {
            options: {
                sourceMap: true
            },
            deqa: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: [
                        'css/page_deqa.css',
                        'css/fhwForms.css'
                    ],
                    dest: 'build/'
                }]
            }
        },

//        // !!!!! Task description !!!!!
//        filerev: {
//            main: {
//                src: 'src/js/concatFiles.min.js'
////                dest: 'src/js'
//            }
//        },
        
        // Minify HTML files
        htmlmin: {
            main: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: [
                        'forms/*.html',
                        'forms/old/*.html',
                        '!forms/fac_msh01_mesh.html', // !!!!! getting an error; debug !!!!!
                        'fragments_portal/*.html',
                        'pages/*.php',
                        'pages/*.html'
                    ],
                    dest: 'build/'
                }]
            },
            options: {
                collapseBooleanAttributes: true,
                collapseWhitespace: true,
                minifyCSS: true,
                removeAttributeQuotes: true,
                removeComments: true,
                removeRedundantAttributes: true
            }
        },

        // Dynamically create the AppCache manifest file
        manifest: {
            main: {
                src: [
                    'lib/bootstrap-3.2.0-dist/css/*.css',
                    'lib/bootstrap-3.2.0-dist/js/*.js',
                    'lib/jquery-ui-1.11.1/*.{css,js}',
                    'lib/jquery-ui-1.11.1/images/ui-icons*.png',
                    'lib/jquery.min.js',
                    'build/images/*.{gif,png}',
                    'build/css/fhwForms/css',
                    'build/css/page_deqa.css',
                    'build/forms/*.html',
                    'build/forms/old/*.html',
                    'build/pages/header_bootstrap.html',
                    'build/pages/page_deqa.html',
                    'build/pages/tool_viewLocalRecords.html',
                    'build/js/LMD_fileSystemHelper.js',
                    'build/js/LMD_accessControl.js',
                    'build/js/deqa.js',
                    'build/js/fhwForms.js',
                    'build/js/formHelper.js',
                    'build/js/formValidate.js',
                    'build/js/loadContents.js'
                ],
                dest: 'build/lastmiledata.appcache',
                options: {
                    verbose: false
                }
            }
        }
        
    });

    // Automatically run "grunt.loadNpmTasks" for all plugins
    require('load-grunt-tasks')(grunt);
    
    // Register default tasks
    grunt.registerTask('default', [
        'clean:initial',
        'copy:initial',
        'useminPrepare',
        'concat',
        'cssmin',
        'uglify',
//        'filerev',
        'usemin',
        'htmlmin',
        'clean:second',
        'manifest'
    ]);

};
