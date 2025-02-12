// JavaScript to control the loader timing and page content visibility
window.onload = function() {
  // Show the loader for 3 seconds
  setTimeout(function() {
    // Hide loader and show page content
    document.querySelector('.loader-container').style.display = 'none';
    window.location.href = "your-main-page.html"; // Redirect to your main page after 3 seconds
  }, 3000); // 3000ms = 3 seconds
};
