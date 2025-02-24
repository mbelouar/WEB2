document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('.container');
    const confettiCount = 100;
  
    for (let i = 0; i < confettiCount; i++) {
      createConfetti(container);
    }
  
    function createConfetti(container) {
      const confetti = document.createElement('div');
      confetti.classList.add('confetti');
      confetti.style.left = `${Math.random() * 100}vw`;
      confetti.style.animationDelay = `${Math.random()}s`;
      confetti.style.animationDuration = `${Math.random() * 3 + 2}s`; // Random duration
      confetti.style.backgroundColor = getRandomColor();
      confetti.style.setProperty('--random-x', `${Math.random() * 200 - 100}px`); // Random horizontal shift
      confetti.style.animation = 'fall linear forwards';
  
      container.appendChild(confetti);
  
      confetti.addEventListener('animationend', () => {
        confetti.remove();
      });
    }
  
    function getRandomColor() {
      const letters = '0123456789ABCDEF';
      let color = '#';
      for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
      }
      return color;
    }
  
    // Button click handlers (example)
    const modifierButton = document.querySelector('.modifier-button');
    const generatorButton = document.querySelector('.generator-button');

  });