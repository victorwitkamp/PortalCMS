module.exports = function (grunt) {
    grunt.initConfig({
        autoprefixer: {
            dist: {
                files: {
                    'portal/includes/css/style_pref.css': 'portal/includes/css/style.css',
                    'portal/includes/css/LoginNewStyle_pref.css': 'portal/includes/css/LoginNewStyle.css'
                }
            }
        },
        watch: {
            styles: {
                files: ['portal/includes/css/style.css','portal/includes/css/LoginNewStyle.css'],
                tasks: ['autoprefixer']
            }
        }
    });
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-watch');
};
