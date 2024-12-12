// Nav Bar Javascript code

const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const navLinks = document.querySelector('.nav-links');

mobileMenuToggle.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

// Nav Bar Javascript code End


// Action Buttons In Job Details Java Script Code Start

function showTab(tabId, event) {
    // Hide all tab contents
    var contents = document.querySelectorAll('.tab-content');
    contents.forEach(function(content) {
        content.classList.remove('active');
    });

    // Remove active class from all tabs
    var tabs = document.querySelectorAll('.tab');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });

    // Show the clicked tab content
    document.getElementById(tabId).classList.add('active');
    // Add active class to the clicked tab
    event.target.classList.add('active');
}

// Action Buttons In Job Details Java Script Code End