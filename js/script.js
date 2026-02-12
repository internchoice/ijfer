/**
 * IJFER - Indian Journals for Engineering and Research
 * Main JavaScript - Component loading and interactions
 */

// Get base path for component links (root vs subpages)
function getBasePath() {
  const path = window.location.pathname;
  if (path.includes('/pages/archives/') || path.includes('/pages/about/')) {
    return '../../';
  }
  return '';
}

// Load and inject component with base path replacement
async function loadComponent(elementId, componentPath) {
  const el = document.getElementById(elementId);
  if (!el) return;
  
  // Add loading class to show consistent appearance
  el.classList.add('component-placeholder');
  
  const base = getBasePath();
  try {
    const response = await fetch(componentPath);
    let html = await response.text();
    html = html.replace(/BASE/g, base);
    
    // Insert content with a smooth transition
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = html;
    
    // Apply the new content with smooth transition
    el.innerHTML = tempDiv.innerHTML;
    
    // Remove the loading class after content is loaded
    el.classList.remove('component-placeholder');
  } catch (err) {
    console.warn('Could not load component:', componentPath, err);
    el.classList.remove('component-placeholder');
  }
}

// Load all components
async function loadComponents() {
  const base = getBasePath();
  const compBase = base + 'components/';
  await Promise.all([
    loadComponent('header-placeholder', compBase + 'header.html'),
    loadComponent('navbar-placeholder', compBase + 'navbar.html'),
    loadComponent('footer-placeholder', compBase + 'footer.html')
  ]);
  setActiveNav();
  
  // Add class to body when components are loaded
  document.body.classList.add('components-loaded');
}

// Load sidebar component (optional - only on pages that need it)
async function loadSidebar() {
  const base = getBasePath();
  const el = document.getElementById('sidebar-placeholder');
  if (!el) return;
  try {
    const response = await fetch(base + 'components/sidebar.html');
    let html = await response.text();
    html = html.replace(/BASE/g, base);
    el.outerHTML = html;
  } catch (err) {
    console.warn('Could not load sidebar:', err);
  }
}

// Set active nav link based on current page
function setActiveNav() {
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.navbar-custom .nav-link').forEach(link => {
    // Skip dropdown toggle links
    if (link.classList.contains('dropdown-toggle')) return;
    
    const href = link.getAttribute('href');
    const linkPage = href ? href.replace(/.*\//, '').split('#')[0] : '';
    if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.html')) {
      link.classList.add('active');
      // Also add active class to parent dropdown if needed
      const dropdownParent = link.closest('.dropdown-menu');
      if (dropdownParent) {
        const dropdownToggle = link.closest('.dropdown').querySelector('.dropdown-toggle');
        if (dropdownToggle) {
          dropdownToggle.classList.add('active');
        }
      }
    } else {
      link.classList.remove('active');
    }
  });
}

document.addEventListener('DOMContentLoaded', async function() {
  // Load components if placeholders exist
  if (document.getElementById('header-placeholder')) {
    await loadComponents();
  }
  if (document.getElementById('sidebar-placeholder')) {
    await loadSidebar();
  }
  if (!document.getElementById('header-placeholder')) {
    setActiveNav();
  }
  
  // Contact form
  const contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Thank you for your message. We will respond within 24 hours.');
      contactForm.reset();
    });
  }

  // Paper submission form
  const submissionForm = document.getElementById('submissionForm');
  if (submissionForm) {
    submissionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Submission received! You will receive a Paper ID via email shortly.');
      submissionForm.reset();
    });
  }

  // Track paper button
  const trackPaperBtn = document.getElementById('trackPaperBtn');
  if (trackPaperBtn) {
    trackPaperBtn.addEventListener('click', function() {
      const input = document.getElementById('trackPaperId');
      const paperId = input ? input.value.trim() : '';
      if (paperId) {
        alert('Tracking Paper ID: ' + paperId + '\n\nStatus will be displayed once the system is connected.');
      } else {
        alert('Please enter your Paper ID.');
      }
    });
  }

  // Search buttons
  const searchBtn = document.getElementById('searchBtn');
  if (searchBtn) {
    searchBtn.addEventListener('click', function() {
      const query = document.getElementById('paperSearch')?.value || '';
      const year = document.getElementById('yearFilter')?.value || '';
      if (query || year) {
        alert('Search for: ' + (query || 'all') + (year ? ' | Year: ' + year : ''));
      } else {
        alert('Please enter search terms or select a year.');
      }
    });
  }
  
  // Handle dropdown menus
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      // Prevent default only if needed, but allow the bootstrap dropdown to work
      // We'll just make sure the dropdown works properly
      const isActive = this.parentElement.classList.contains('show');
      
      // Close other open dropdowns in the same navbar
      document.querySelectorAll('.navbar-nav .dropdown.show').forEach(dropdown => {
        if (dropdown !== this.parentElement) {
          dropdown.classList.remove('show');
          dropdown.querySelector('.dropdown-menu').classList.remove('show');
        }
      });
    });
  });
  
  // Close dropdowns when clicking outside
  document.addEventListener('click', function(event) {
    if (!event.target.closest('.navbar')) {
      document.querySelectorAll('.navbar-nav .dropdown.show').forEach(dropdown => {
        dropdown.classList.remove('show');
        dropdown.querySelector('.dropdown-menu').classList.remove('show');
      });
    }
  });
});
