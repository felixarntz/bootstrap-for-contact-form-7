'use strict';
module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jsbanner: '/*!\n' +
              ' * <%= pkg.pluginName %> Scripts - Version <%= pkg.version %>\n' +
              ' * \n' +
              ' * Modifications and Additions to WPCF7 Scripts to work with CF7BS\n' +
              ' * <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
              ' */',
    pluginheader: '/*\n' +
                  'Plugin Name: <%= pkg.pluginName %>\n' +
                  'Plugin URI: <%= pkg.homepage %>\n' +
                  'Description: <%= pkg.description %>\n' +
                  'Version: <%= pkg.version %>\n' +
                  'Author: <%= pkg.author.name %>\n' +
                  'Author URI: <%= pkg.author.url %>\n' +
                  'License: <%= pkg.license.name %>\n' +
                  'License URI: <%= pkg.license.url %>\n' +
                  '*/',
    fileheader: '/**\n' +
                ' * @package CF7BS\n' +
                ' * @version <%= pkg.version %>\n' +
                ' * @author <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
                ' */',

    clean: {
      scripts: [
        'assets/scripts.min.js'
      ]
    },

    jshint: {
      options: {
        jshintrc: 'assets/.jshintrc'
      },
      src: [
        'assets/scripts.js'
      ]
    },

    uglify: {
      options: {
        preserveComments: 'some',
        report: 'min'
      },
      dist: {
        src: 'assets/scripts.js',
        dest: 'assets/scripts.min.js'
      }
    },

    usebanner: {
      options: {
        position: 'top',
        banner: '<%= jsbanner %>'
      },
      files: {
        src: [
          'assets/scripts.min.js'
        ]
      }
    },

    replace: {
      header: {
        src: [
          'bootstrap-for-contact-form-7.php'
        ],
        overwrite: true,
        replacements: [{
          from: /((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/,
          to: '<%= pluginheader %>'
        }]
      },
      version: {
        src: [
          'bootstrap-for-contact-form-7.php',
          'modifications.php',
          'classes/*.php',
          'modules/*.php'
        ],
        overwrite: true,
        replacements: [{
          from: /\/\*\*\s+\*\s@package\s[^*]+\s+\*\s@version\s[^*]+\s+\*\s@author\s[^*]+\s\*\//,
          to: '<%= fileheader %>'
        }]
      }
    }

  });
  
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-banner');
  grunt.loadNpmTasks('grunt-text-replace');

  grunt.registerTask('scripts', [
    'clean:scripts',
    'jshint',
    'uglify'
  ]);

  grunt.registerTask('plugin', [
    'usebanner',
    'replace:version',
    'replace:header'
  ]);

  grunt.registerTask('default', [
    'scripts'
  ]);

  grunt.registerTask('build', [
    'scripts',
    'plugin'
  ]);
};
