module.exports = function (grunt) {
    require("load-grunt-tasks")(grunt);
    var path = {
        "dev": "resources/assets/",
        "dist": "public/",
        "bower": "bower_components/"
    };
    var scripts = [
        //JQuery
        "<%= path.bower %>jquery/dist/**/jquery.min.js",
        //Bootstrap
        "<%= path.bower %>bootstrap/dist/**/bootstrap.min.js",
        //General JScript File
        "<%= path.dev %>js/main.js"
    ];

    var less = [
        //Configuration files
        "<%= path.dev %>styles/config/variables.less",
        "<%= path.dev %>styles/config/aliases.less",
        "<%= path.dev %>styles/config/midia-queries.less",
        "<%= path.dev %>styles/config/functions.less",
        //Vendors CSS and LESS files
        "<%= path.dev %>styles/vendors/**/*.*",
        //Base LESS files
        "<%= path.dev %>styles/base/*.less",
        //Layout files
        "<%= path.dev %>styles/layouts/*.less",
        //Component files
        "<%= path.dev %>styles/components/*.less",
        //Do not remove this line: this is the destination file and should not be imported.
        "!<%= path.dev %>styles/styles.less"
    ];

    var css = [
        //Bootstrap
        "<%= path.bower %>bootstrap/dist/**/bootstrap.css",
        //General CSS File
        "<%= path.dev %>styles.css"
    ];

    grunt.initConfig({
        path: path,
        scripts: scripts,
        css: css,
        concat: {
            js: {
                options: {
                    separator: ";\n"
                },
                src: scripts,
                dest: "<%= path.dist %>js/scripts.min.js"
            },
            css: {
                src: css,
                dest: "<%= path.dist %>css/styles.min.css"
            }
        },
        jshint: {
            files: ["Gruntfile.js", "<%= path.dev %>js/main.js"],
            options: {
                globals: {
                    jQuery: true
                }
            }
        },
        less_imports: {
            options: {
                banner: "// Compiled stylesheet. Do not modify. All changes are going to be lost."
            },
            site: {
                src: less,
                dest: '<%= path.dev %>styles/styles.less'
            }
        },
        less: {
            options: {
                compress: false
            },
            site: {
                files: {
                    "<%= path.dev %>styles.css": "<%= path.dev %>styles/styles.less"
                }
            }
        },
        uglify: {
            options: {
            },
            dist: {
                files: {
                    "<%= path.dist %>js/scripts.min.js": "<%= path.dist %>js/scripts.min.js"
                }
            }
        },
        cssmin: {
            options: {
            },
            dist: {
                files: {
                    "<%= path.dist %>css/styles.min.css": ["<%= path.dist %>css/styles.min.css"]
                }
            }
        },
        watch: {
            less: {
                files: ['<% path.dev %>styles/**/*.less'],
                tasks: ["less_imports", "less", "concat:css"],
                options: {
                    spawn: false,
                }
            }
        }
    });
    grunt.registerTask("w", ["watch:less"]);
    grunt.registerTask("l", ["less_imports", "less", "concat:css"]);
    grunt.registerTask("j", ["jshint", "concat:js"]);
    grunt.registerTask("build", ["less_imports", "less", "concat:css", "concat:js", "uglify", "cssmin"]);
};
