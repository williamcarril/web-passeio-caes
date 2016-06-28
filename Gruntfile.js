module.exports = function(grunt) {
    require("load-grunt-tasks")(grunt);
    var path = {
        "dev": "resources/assets/",
        "dist": "public/",
        "npm": "node_modules/",
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
    var css = [
        //Bootstrap
        "<%= path.bower %>bootstrap/dist/**/bootstrap.css",
        //General CSS File
        "<%= path.dev %>style.css"
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
                dest: "<%= path.dist %>css/style.min.css"
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
        less: {
            options: {
                compress: true
            },
            site: {
                files: {
                    "<%= path.dev %>style.css": ["<%= path.dev %>less/main.less"]
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
                    "<%= path.dist %>css/style.min.css": ["<%= path.dist %>css/style.min.css"]
                }
            }
        }
        // ,
        // watch: {
        //     files: ['<%= jshint.files %>'],
        //     tasks: ['jshint']
        // }
    });
    grunt.registerTask("l", ["less", "concat:css"]);
    grunt.registerTask("j", ["jshint", "concat:js"]);
    grunt.registerTask("build", ["less", "concat:css", "concat:js", "uglify", "cssmin"]);
};
