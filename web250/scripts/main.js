document.addEventListener("DOMContentLoaded", function() {

  fetch('components/header.html')
    .then(response => response.text())
    .then(data => {
      document.querySelector("header").innerHTML = data;
    })
    .catch(error => console.error('Error loading the header:', error));

  fetch('components/footer.html')
    .then(response => response.text())
    .then(data => {
      document.querySelector("footer").innerHTML = data;
      
      const githubPagesBaseUrl = "https://Sal-ah-cmd.github.io/web250/";
      const currentPageFileName = window.location.pathname.split("/").pop();

      const htmlValidatorLink = document.getElementById('html-validator');
      if (htmlValidatorLink) {
        htmlValidatorLink.href = `https://validator.w3.org/nu/?doc=${githubPagesBaseUrl}${currentPageFileName}`;
      }

      const cssValidatorLink = document.getElementById('css-validator');
      if (cssValidatorLink) {
        cssValidatorLink.href = `http://jigsaw.w3.org/css-validator/validator?uri=${githubPagesBaseUrl}styles/styles.css`;
      }
    })
    .catch(error => console.error('Error loading the footer:', error));
});