module.exports = function (grunt) {
    require("load-grunt-tasks")(grunt);
    var path = {
        "dev": {
            "site": "resources/assets/",
            "admin": "resources/assets/admin/"
        },
        "dist": "public/",
        "bower": "bower_components/"
    };
    var scripts = {
        "site": [
            //JQuery
            "<%= path.bower %>jquery/dist/**/jquery.min.js",
            //Bootstrap
            "<%= path.bower %>bootstrap/dist/**/bootstrap.min.js",
            //JQuery Input Mask
            "<%= path.bower %>jquery.inputmask/dist/jquery.inputmask.bundle.js",
            //Picturefill
            "<%= path.bower %>picturefill/dist/picturefill.min.js",
            //Timetable
            "<%= path.bower %>timetable.js/dist/**/timetable.js",
            //Bootstrap Timepicker
            "<%= path.bower %>bootstrap-timepicker/js/bootstrap-timepicker.js",
            //Non-Bower Vendors Files
            "<%= path.dev.site %>js/vendors/**/*.js",
            //General JScript Functions File
            "<%= path.dev.site %>js/functions.js",
            //General JScript File
            "<%= path.dev.site %>js/main.js",
            //Custom Components Files
            "<%= path.dev.site %>js/components/**/*.js"
        ],
        "admin": [
            //JQuery
            "<%= path.bower %>jquery/dist/**/jquery.min.js",
            //Bootstrap
            "<%= path.bower %>bootstrap/dist/**/bootstrap.min.js",
            //JQuery Input Mask
            "<%= path.bower %>jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js",
            //Timetable
            "<%= path.bower %>timetable.js/dist/**/timetable.js",
            //Bootstrap Timepicker
            "<%= path.bower %>bootstrap-timepicker/js/bootstrap-timepicker.js",
            //Non-Bower Vendors Files
            "<%= path.dev.admin %>js/vendors/**/*.js",
            //General JScript Functions File
            "<%= path.dev.site %>js/functions.js",
            //General JScript File
            "<%= path.dev.admin %>js/main.js",
            //Custom Components Files
            "<%= path.dev.site %>js/components/**/*.js"
        ]
    };

    var less = {
        "site": [
            //Configuration files
            "<%= path.dev.site %>styles/config/variables.less",
            "<%= path.dev.site %>styles/config/aliases.less",
            "<%= path.dev.site %>styles/config/media-queries.less",
            "<%= path.dev.site %>styles/config/functions.less",
            //Non-Bower vendors CSS and LESS files
            "<%= path.dev.site %>styles/vendors/**/*.*",
            //Base LESS files
            "<%= path.dev.site %>styles/base/*.less",
            //Layout files
            "<%= path.dev.site %>styles/layouts/*.less",
            //Component files
            "<%= path.dev.site %>styles/components/*.less",
            //Do not remove this line: this is the destination file and should not be imported.
            "!<%= path.dev.site %>styles/styles.less"
        ],
        "admin": [
            //Configuration files
            "<%= path.dev.admin %>styles/config/variables.less",
            "<%= path.dev.admin %>styles/config/aliases.less",
            "<%= path.dev.admin %>styles/config/media-queries.less",
            "<%= path.dev.admin %>styles/config/functions.less",
            //Non-Bower vendors CSS and LESS files
            "<%= path.dev.admin %>styles/vendors/**/*.*",
            //Base LESS files
            "<%= path.dev.admin %>styles/base/*.less",
            //Layout files
            "<%= path.dev.admin %>styles/layouts/*.less",
            //Component files
            "<%= path.dev.admin %>styles/components/*.less",
            //Do not remove this line: this is the destination file and should not be imported.
            "!<%= path.dev.admin %>styles/styles.less"
        ]
    };
    var css = {
        "site": [
            //Bootstrap
            "<%= path.bower %>bootstrap/dist/**/bootstrap.css",
            //Bootstrap Timepicker
            "<%= path.bower %>bootstrap-timepicker/css/timepicker.less",
            //Bower files
            "<%= path.bower %>timetable.js/dist/**/timetablejs.css",
            //General CSS File
            "<%= path.dev.site %>styles.css"
        ],
        "admin": [
            //Bootstrap
            "<%= path.bower %>bootstrap/dist/**/bootstrap.css",
            //Bootstrap Timepicker
            "<%= path.bower %>bootstrap-timepicker/css/timepicker.less",
            //Bower files
            "<%= path.bower %>timetable.js/dist/**/timetablejs.css",
            //General CSS File
            "<%= path.dev.admin %>styles.css"
        ]
    };

    grunt.initConfig({
        "path": path,
        "scripts": scripts,
        "css": css,
        "concat": {
            "jsSite": {
                "options": {
                    "separator": ";\n"
                },
                "src": scripts.site,
                "dest": "<%= path.dist %>js/scripts.min.js"
            },
            "jsAdmin": {
                "options": {
                    "separator": ";\n"
                },
                "src": scripts.admin,
                "dest": "<%= path.dist %>js/adm_scripts.min.js"
            },
            "cssSite": {
                "src": css.site,
                "dest": "<%= path.dist %>css/styles.min.css"
            },
            "cssAdmin": {
                "src": css.admin,
                "dest": "<%= path.dist %>css/adm_styles.min.css"
            }
        },
        "jshint": {
            "options": {
                "globals": {
                    "jQuery": true
                }
            },
            "files": ["Gruntfile.js", "<%= path.dev.site %>js/main.js", "<%= path.dev.admin %>js/main.js"],
        },
        "less_imports": {
            "options": {
                "banner": "// Compiled stylesheet. Do not modify. All changes are going to be lost."
            },
            "site": {
                "src": less.site,
                "dest": '<%= path.dev.site %>styles/styles.less'
            },
            "admin": {
                "src": less.admin,
                "dest": '<%= path.dev.admin %>styles/styles.less'
            }
        },
        "less": {
            "options": {
                compress: false
            },
            "site": {
                "files": {
                    "<%= path.dev.site %>styles.css": "<%= path.dev.site %>styles/styles.less"
                }
            },
            "admin": {
                "files": {
                    "<%= path.dev.admin %>styles.css": "<%= path.dev.admin %>styles/styles.less"
                }
            }
        },
        "uglify": {
            "options": {
            },
            "site": {
                files: {
                    "<%= path.dist %>js/scripts.min.js": "<%= path.dist %>js/scripts.min.js"
                }
            },
            "admin": {
                files: {
                    "<%= path.dist %>js/adm_scripts.min.js": "<%= path.dist %>js/adm_scripts.min.js"
                }
            }
        },
        "cssmin": {
            "options": {
            },
            "site": {
                "files": {
                    "<%= path.dist %>css/styles.min.css": ["<%= path.dist %>css/styles.min.css"]
                }
            },
            "admin": {
                "files": {
                    "<%= path.dist %>css/adm_styles.min.css": ["<%= path.dist %>css/adm_styles.min.css"]
                }
            }
        }

    });
    grunt.registerTask("l:s", ["less_imports:site", "less:site", "concat:cssSite"]);
    grunt.registerTask("l:a", ["less_imports:admin", "less:admin", "concat:cssAdmin"]);
    grunt.registerTask("j:s", ["jshint", "concat:jsSite"]);
    grunt.registerTask("j:a", ["jshint", "concat:jsAdmin"]);
    grunt.registerTask("build:s", ["less_imports:site", "less:site", "concat:cssSite", "concat:jsSite", "uglify:site", "cssmin:site"]);
    grunt.registerTask("build:a", ["less_imports:admin", "less:admin", "concat:cssAdmin", "concat:jsAdmin", "uglify:admin", "cssmin:admin"]);
};
