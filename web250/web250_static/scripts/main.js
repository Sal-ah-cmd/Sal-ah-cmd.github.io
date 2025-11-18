document.addEventListener("DOMContentLoaded", function() {
  const cacheBuster = "?v=" + new Date().getTime(); 
  const PageBaseUrl = "https://sal-ah-cmdgithubio.42web.io/";

  fetch('components/header.php' + cacheBuster)
    .then((response) => (response.text())) 
    .then((data) => {
      document.querySelector("header").innerHTML = data;
    })
    .catch((error) => console.error('Error loading the header:', error)); 

  fetch('components/footer.php' + cacheBuster)
    .then((response) => (response.text())) 
    .then((data) => {
      document.querySelector("footer").innerHTML = data;

      const currentPageFileName = window.location.pathname.split("/").pop();

      const htmlValidatorLink = document.getElementById('html-validator');
      if (htmlValidatorLink) {
        htmlValidatorLink.href = `https://validator.w3.org/nu/?doc=${PageBaseUrl}web250_static/${currentPageFileName}`;
      }

      const cssValidatorLink = document.getElementById('css-validator');
      if (cssValidatorLink) {
        cssValidatorLink.href = `http://jigsaw.w3.org/css-validator/validator?uri=${PageBaseUrl}web250_static/styles/styles.css`;
      }
    })
    .catch((error) => console.error('Error loading the footer:', error)); 
});