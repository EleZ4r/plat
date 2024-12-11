particlesJS('particles.js', {
    "particles": {
      "number": {
        "value": 80, // Number of particles
        "density": {
          "enable": true,
          "value_area": 800 // Density of particles
        }
      },
      "color": {
        "value": "#ff6600" // Particle color (you can change this)
      },
      "shape": {
        "type": "circle", // Particle shape
        "stroke": {
          "width": 0,
          "color": "#000000"
        }
      },
      "opacity": {
        "value": 0.5, // Particle opacity
        "random": true,
        "anim": {
          "enable": true,
          "speed": 1,
          "opacity_min": 0.1
        }
      },
      "size": {
        "value": 5, // Particle size
        "random": true,
        "anim": {
          "enable": true,
          "speed": 2,
          "size_min": 1
        }
      },
      "line_linked": {
        "enable": true, // Enable particle linking
        "distance": 150, // Distance at which particles connect
        "color": "#ff6600", // Line color
        "opacity": 0.4,
        "width": 1
      },
      "move": {
        "enable": true,
        "speed": 2,
        "direction": "none", // Particle movement direction
        "random": true,
        "straight": false,
        "out_mode": "out",
        "bounce": false
      }
    },
    "interactivity": {
      "detect_on": "canvas",
      "events": {
        "onhover": {
          "enable": true,
          "mode": "repulse" // Particle interaction on hover
        },
        "onclick": {
          "enable": true,
          "mode": "push" // Particle interaction on click
        }
      }
    },
    "retina_detect": true
  });