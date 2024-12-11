particlesJS('particles-js', {
    "particles": {
      "number": {
        "value": 150, // Number of stars (particles)
        "density": {
          "enable": true,
          "value_area": 800 // Density of particles (this controls the spread of stars)
        }
      },
      "color": {
        "value": "#ffffff" // Star color (white)
      },
      "shape": {
        "type": "circle", // Stars will be circles
        "stroke": {
          "width": 0, // No stroke for stars
          "color": "#000000"
        }
      },
      "opacity": {
        "value": 0.5, // The opacity of the stars
        "random": true, // Make some stars dimmer
        "anim": {
          "enable": true,
          "speed": 1, // Speed of opacity change
          "opacity_min": 0.1
        }
      },
      "size": {
        "value": 3, // The size of each star (particle)
        "random": true, // Make some stars larger and some smaller
        "anim": {
          "enable": true,
          "speed": 0.5, // Speed of size change (twinkling effect)
          "size_min": 1
        }
      },
      "line_linked": {
        "enable": false, // Disable lines between particles (stars are isolated)
      },
      "move": {
        "enable": true,
        "speed": 0.2, // Slow particle movement to simulate stars in the background
        "direction": "none", // Stars should not have a fixed movement direction
        "random": true, // Some stars move randomly
        "straight": false,
        "out_mode": "out", // Stars move out of bounds if they reach the edge
        "bounce": false
      }
    },
    "interactivity": {
      "detect_on": "canvas",
      "events": {
        "onhover": {
          "enable": true,
          "mode": "repulse" // Repulse particles when the mouse hovers over
        },
        "onclick": {
          "enable": true,
          "mode": "push" // Add particles when the user clicks on the background
        }
      }
    },
    "retina_detect": true // Enable retina displays for higher resolution
  });
  