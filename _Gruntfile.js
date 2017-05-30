"use strict";

module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
	jshint: {
		options: {
			jshintrc: '.jshintrc',
			reporter: require('jshint-stylish')
		},
		all: {
			src: [
				'Gruntfile.js',
				'resources/assets/js/*.js',
			]
		}
	},
    sass: {                              // Task 
		dist: {                            // Target 
		  options: {                       // Target options 
			style: 'expanded'
		  },
		  files: {                         
			'public/css/app.css': 'resources/assets/sass/app.scss',       // 'destination': 'source' 
		  }
		}
    },
	
	watch: {
        src: {
		files: ['resources/assets/sass/app.scss'],
            tasks: ['sass:dist'],
            options: {
                spawn: false,
				livereload: 12344
            }
        }		
    },
	 connect: {
		server: {
		  options: {
			port: 9000,
			hostname: '0.0.0.0',
			base: '',
			open:true
		  }
		}
	},
	  
});

  // Load all plugins.
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-connect');
  
  // Register all Tasks.
  grunt.registerTask('default',  ['sass:dist','jshint:all','connect:server','watch:src']);
  grunt.registerTask('sass-compile',  ['sass:dist','jshint:all','connect:server','watch:src']);
};