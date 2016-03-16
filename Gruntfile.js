module.exports = function(grunt) {
    
    grunt.initConfig({
        
        pkg: grunt.file.readJSON('package.json'),
        
        // Clear all files/subdirectories from "build" directory
        clean: {
            main: [
                'build/**/*',
                'build/**/.htaccess'
            ]
        },
        
        // Copy the directory structure and files from "src" --> "build", excluding directory branches
        // with "archive" in their names.
        copy: {
            main: {
                files: [
                    {
                        expand:true,
                        cwd:'src',
                        src: ['**/*', '**/.htaccess', '!**/archive/**'],
                        dest:'build' }
                ]
            }
        },
        
        // Minify JS files (turn on source mapping and apply timestamp banner)
        uglify: {
            options: {
                sourceMap: true,
                banner: '/*! LAST MILE DATA build: <%= grunt.template.today("yyyy-mm-dd hh:dd:ss") %> */\n'
            },
            main: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: ['js/*.js'],
                    dest: 'build/'
                }]
            }
        },

        // Minify CSS files (turn on source mapping)
        cssmin: {
            options: {
                sourceMap: true
            },
            main: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: ['css/*.css'],
                    dest: 'build/'
                }]
            }
        },

        // Add revision numbers to JS/CSS files; these will automatically change when the file contents change
        //   e.g. loadContents.js --> loadContents.aj3o48xo.js
        filerev: {
            main: {
                src: [
                    'build/js/*.js',
                    'build/css/*.css'
                ]
            }
        },

        // Replace references to the filerev'd JS/CSS files
        //   e.g. <script src='../js/loadContents.js'> --> <script src='../js/loadContents.aj3o48xo.js'>
        filerev_replace: {
            options: {
                assets_root: 'build/'
            },
            main: {
              src: [
                  'build/pages/*',
                  'build/forms/*.html',
                  'build/forms/old/*.html',
                  'build/fragments_portal/*'
              ]
            }
        },

        // Minify HTML/PHP files
        htmlmin: {
            options: {
                collapseBooleanAttributes: true,
//                collapseWhitespace: true, // !!!!! this currently breaks PHP !!!!!
//                preserveLineBreaks: true, // !!!!! this currently breaks PHP !!!!!
                minifyCSS: true,
                removeAttributeQuotes: true,
                removeComments: true,
                removeRedundantAttributes: true
            },
            main: {
                files: [{
                    expand: true,
                    cwd: 'build/',
                    src: [
                        'forms/*.html',
                        'forms/old/*.html',
                        '!forms/fac_msh01_mesh.html', // !!!!! temporarily excluding because this file causes an error; debug !!!!!
                        'fragments_portal/*.html',
                        'pages/*.{php,html}'
                    ],
                    dest: 'build/'
                }]
            }
        },

        // Dynamically create the AppCache manifest file
        manifest: {
            options: {
                verbose: false,
            },
            main: {
                src: [
                    'lib/bootstrap-3.2.0-dist/css/*.css',
                    'lib/bootstrap-3.2.0-dist/js/*.js',
                    'lib/jquery-ui-1.11.1/*.{css,js}',
                    'lib/jquery-ui-1.11.1/images/ui-icons*.png',
                    'lib/jquery.min.js',
                    'build/images/*.{gif,png}',
                    'build/css/fhwForms.*.css',
                    'build/css/page_deqa.*.css',
                    'build/forms/*.html',
                    'build/forms/old/*.html',
                    'build/pages/header_bootstrap.html',
                    'build/pages/page_deqa.html',
                    'build/pages/tool_viewLocalRecords.html',
                    'build/js/LMD_fileSystemHelper.*.js',
                    'build/js/LMD_utilities.*.js',
                    'build/js/page_deqa.*.js',
                    'build/js/fhwForms.*.js',
                    'build/js/formHelper.*.js',
                    'build/js/formValidate.*.js',
                    'build/js/loadContents.*.js'
                ],
                dest: 'lastmiledata.appcache'
            }
        }
    });

    // Automatically run "grunt.loadNpmTasks" for all plugins
    require('load-grunt-tasks')(grunt);
    
    // Register default tasks
    grunt.registerTask('default', [
        'clean',
        'copy',
        'uglify',
        'cssmin',
        'filerev',
        'filerev_replace',
        'htmlmin',
        'manifest'
    ]);

};
