document.addEventListener("DOMContentLoaded", function() {

  fetch('components/header.html')
    .then((response) => response.text())
    .then((data) => {
      document.querySelector("header").innerHTML = data;
    })
    .catch((error) => console.error('Error loading the header:', error));

  fetch('components/footer.html')
    .then((response) => response.text())
    .then((data) => {
      document.querySelector("footer").innerHTML = data;
      
      const currentPageUrl = window.location.href;

      const htmlValidatorLink = document.getElementById('html-validator');
      if (htmlValidatorLink) {
        htmlValidatorLink.href = `https://validator.w3.org/nu/?doc=${currentPageUrl}`;
      }

      const cssPath = 'styles/styles.css';
      const cssUrl = new URL(cssPath, currentPageUrl).href;

      const cssValidatorLink = document.getElementById('css-validator');
      if (cssValidatorLink) {
        cssValidatorLink.href = `https://jigsaw.w3.org/css-validator/validator?uri=${cssUrl}`;
      }
    })
    .catch((error) => console.error('Error loading the footer:', error));
});