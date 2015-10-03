'use strict';
module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		jsbanner:		'/*!\n' +
						' * <%= pkg.pluginName %> Scripts - Version <%= pkg.version %>\n' +
						' * \n' +
						' * Modifications and Additions to WPCF7 Scripts to work with CF7BS\n' +
						' * <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
						' */',
		pluginheader:	'/*\n' +
						'Plugin Name: <%= pkg.pluginName %>\n' +
						'Plugin URI: <%= pkg.homepage %>\n' +
						'Description: <%= pkg.description %>\n' +
						'Version: <%= pkg.version %>\n' +
						'Author: <%= pkg.author.name %>\n' +
						'Author URI: <%= pkg.author.url %>\n' +
						'License: <%= pkg.license.name %>\n' +
						'License URI: <%= pkg.license.url %>\n' +
						'Text Domain: bootstrap-for-contact-form-7\n' +
						'Domain Path: /languages/\n' +
						'*/',
		fileheader:		'/**\n' +
						' * @package CF7BS\n' +
						' * @version <%= pkg.version %>\n' +
						' * @author <%= pkg.author.name %> <<%= pkg.author.email %>>\n' +
						' */',

		clean: {
			scripts: [
				'assets/scripts.min.js'
			],
			translation: [
				'languages/bootstrap-for-contact-form-7.pot'
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
		},

		makepot: {
			translation: {
				options: {
					mainFile: 'bootstrap-for-contact-form-7.php',
					domainPath: '/languages',
					exclude: [ 'vendor/.*' ],
					potComments: 'Copyright (c) 2014-<%= grunt.template.today("yyyy") %> <%= pkg.author.name %>',
					potFilename: 'bootstrap-for-contact-form-7.pot',
					potHeaders: {
						'language-team': '<%= pkg.author.name %> <<%= pkg.author.email %>>',
						'last-translator': '<%= pkg.author.name %> <<%= pkg.author.email %>>',
						'project-id-version': '<%= pkg.name %> <%= pkg.version %>',
						'report-msgid-bugs-to': '<%= pkg.homepage %>',
						'x-generator': 'grunt-wp-i18n 0.5.3',
						'x-poedit-basepath': '.',
						'x-poedit-language': 'English',
						'x-poedit-country': 'UNITED STATES',
						'x-poedit-sourcecharset': 'uft-8',
						'x-poedit-keywordslist': '__;_e;_x:1,2c;_ex:1,2c;_n:1,2; _nx:1,2,4c;_n_noop:1,2;_nx_noop:1,2,3c;esc_attr__; esc_html__;esc_attr_e; esc_html_e;esc_attr_x:1,2c; esc_html_x:1,2c;',
						'x-poedit-bookmars': '',
						'x-poedit-searchpath-0': '.',
						'x-textdomain-support': 'yes'
					},
					type: 'wp-plugin'
				}
			}
		}

	});

	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-banner');
	grunt.loadNpmTasks('grunt-text-replace');
	grunt.loadNpmTasks('grunt-wp-i18n');

	grunt.registerTask('scripts', [
		'clean:scripts',
		'jshint',
		'uglify'
	]);

	grunt.registerTask('translation', [
		'clean:translation',
		'makepot:translation'
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
		'translation',
		'plugin'
	]);
};
