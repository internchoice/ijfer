/**
 * IJFER - Indian Journals for Engineering and Research
 * Main JavaScript - Component loading and interactions
 */

// Get base path for component links (root vs subpages)
function getBasePath() {
  const path = window.location.pathname;
  if (path.includes('/pages/')) {
    return '../../';
  }
  return '';
}

// Load and inject component with base path replacement
async function loadComponent(elementId, componentPath) {
  const el = document.getElementById(elementId);
  if (!el) return;
  const base = getBasePath();
  try {
    const response = await fetch(componentPath);
    let html = await response.text();
    html = html.replace(/BASE/g, base);
    el.outerHTML = html;
  } catch (err) {
    console.warn('Could not load component:', componentPath, err);
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
    // Add author functionality
    const addAuthorBtn = document.getElementById('addAuthorBtn');
    const authorsContainer = document.getElementById('authorsContainer');
    
    if (addAuthorBtn && authorsContainer) {
      addAuthorBtn.addEventListener('click', function() {
        const authorRow = document.createElement('div');
        authorRow.className = 'author-row';
        authorRow.innerHTML = `
          <input type="text" class="author-name" placeholder="Author Full Name" required>
          <input type="email" class="author-email" placeholder="Author Email ID" required>
          <input type="tel" class="author-phone" placeholder="Contact Number" required>
          <input type="text" class="author-institution" placeholder="College/Institute Name" required>
          <button type="button" class="btn btn-danger btn-sm remove-author">Remove</button>
        `;
        authorsContainer.appendChild(authorRow);
        
        // Add event listener to the remove button
        const removeBtn = authorRow.querySelector('.remove-author');
        removeBtn.addEventListener('click', function() {
          authorsContainer.removeChild(authorRow);
        });
      });
      
      // Add initial remove button to the first author row
      const firstAuthorRow = authorsContainer.querySelector('.author-row');
      if (firstAuthorRow) {
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-danger btn-sm remove-author';
        removeBtn.textContent = 'Remove';
        firstAuthorRow.appendChild(removeBtn);
        
        removeBtn.addEventListener('click', function() {
          authorsContainer.removeChild(firstAuthorRow);
        });
      }
    }
    
    // Auto-save form data to localStorage
    const autoSaveFields = ['researchArea', 'paperTitle', 'abstract', 'country'];
    
    autoSaveFields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('input', function() {
          const formData = {};
          autoSaveFields.forEach(id => {
            const elem = document.getElementById(id);
            if (elem) formData[id] = elem.value;
          });
          
          // Save authors too
          const authorRows = document.querySelectorAll('.author-row');
          const authorsData = [];
          authorRows.forEach((row, index) => {
            const name = row.querySelector('.author-name')?.value || '';
            const email = row.querySelector('.author-email')?.value || '';
            const phone = row.querySelector('.author-phone')?.value || '';
            const institution = row.querySelector('.author-institution')?.value || '';
            
            if (name || email || phone || institution) {
              authorsData.push({name, email, phone, institution});
            }
          });
          
          formData.authors = authorsData;
          localStorage.setItem('paperSubmissionDraft', JSON.stringify(formData));
        });
      }
    });
    
    // Restore saved form data if it exists
    window.addEventListener('load', function() {
      const savedData = localStorage.getItem('paperSubmissionDraft');
      if (savedData) {
        try {
          const formData = JSON.parse(savedData);
          
          // Restore basic fields
          Object.keys(formData).forEach(key => {
            if (autoSaveFields.includes(key)) {
              const field = document.getElementById(key);
              if (field) field.value = formData[key];
            }
          });
          
          // Restore authors
          if (formData.authors && formData.authors.length > 0) {
            const container = document.getElementById('authorsContainer');
            
            // Clear existing rows except the first one
            const existingRows = document.querySelectorAll('.author-row:not(:first-child)');
            existingRows.forEach(row => row.remove());
            
            // Fill first row
            const firstRow = document.querySelector('.author-row');
            if (firstRow && formData.authors[0]) {
              const author = formData.authors[0];
              firstRow.querySelector('.author-name')?.setAttribute('value', author.name);
              firstRow.querySelector('.author-email')?.setAttribute('value', author.email);
              firstRow.querySelector('.author-phone')?.setAttribute('value', author.phone);
              firstRow.querySelector('.author-institution')?.setAttribute('value', author.institution);
              
              // Set values correctly
              if (firstRow.querySelector('.author-name')) firstRow.querySelector('.author-name').value = author.name;
              if (firstRow.querySelector('.author-email')) firstRow.querySelector('.author-email').value = author.email;
              if (firstRow.querySelector('.author-phone')) firstRow.querySelector('.author-phone').value = author.phone;
              if (firstRow.querySelector('.author-institution')) firstRow.querySelector('.author-institution').value = author.institution;
            }
            
            // Add additional rows for remaining authors
            for (let i = 1; i < formData.authors.length; i++) {
              const author = formData.authors[i];
              const authorRow = document.createElement('div');
              authorRow.className = 'author-row';
              authorRow.innerHTML = `
                <input type="text" class="author-name" placeholder="Author Full Name" value="${author.name}" required>
                <input type="email" class="author-email" placeholder="Author Email ID" value="${author.email}" required>
                <input type="tel" class="author-phone" placeholder="Contact Number" value="${author.phone}" required>
                <input type="text" class="author-institution" placeholder="College/Institute Name" value="${author.institution}" required>
                <button type="button" class="btn btn-danger btn-sm remove-author">Remove</button>
              `;
              container.appendChild(authorRow);
              
              // Add event listener to the remove button
              const removeBtn = authorRow.querySelector('.remove-author');
              removeBtn.addEventListener('click', function() {
                container.removeChild(authorRow);
              });
            }
          }
          
          console.log('Restored saved form data');
        } catch (e) {
          console.error('Error restoring form data:', e);
        }
      }
    });
    
    // Progress indicator functionality
    function updateProgress(currentStep) {
      // Update progress bar
      const progressBar = document.querySelector('.progress-bar');
      const progressPercent = (currentStep / 4) * 100;
      progressBar.style.width = progressPercent + '%';
      progressBar.setAttribute('aria-valuenow', progressPercent);
      progressBar.textContent = `Step ${currentStep} of 4`;
      
      // Update step indicators
      const steps = document.querySelectorAll('.step');
      steps.forEach((step, index) => {
        step.classList.remove('active', 'completed');
        if (index + 1 < currentStep) {
          step.classList.add('completed');
        } else if (index + 1 === currentStep) {
          step.classList.add('active');
        }
      });
    }
    
    // Initialize progress indicator
    updateProgress(1);
    
    // Update progress as user fills the form
    document.getElementById('researchArea')?.addEventListener('change', function() {
      if (this.value) updateProgress(2);
    });
    
    document.getElementById('paperTitle')?.addEventListener('input', function() {
      if (this.value.trim().length > 10) updateProgress(2);
    });
    
    document.getElementById('abstract')?.addEventListener('input', function() {
      if (this.value.trim().length > 50) updateProgress(2);
    });
    
    // Form submission
    submissionForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Get form data
      const formData = new FormData(submissionForm);
      
      // Validate form
      const researchArea = document.getElementById('researchArea').value;
      const paperTitle = document.getElementById('paperTitle').value;
      const abstract = document.getElementById('abstract').value;
      const country = document.getElementById('country').value;
      const fileUpload = document.getElementById('fileUpload').files[0];
      
      if (!researchArea || !paperTitle || !abstract || !country || !fileUpload) {
        alert('Please fill in all required fields and upload your manuscript.');
        return;
      }
      
      // Get all authors
      const authorRows = document.querySelectorAll('.author-row');
      let allAuthorsValid = true;
      authorRows.forEach(row => {
        const inputs = row.querySelectorAll('input:not(.remove-author)');
        inputs.forEach(input => {
          if (!input.value.trim()) {
            allAuthorsValid = false;
          }
        });
      });
      
      if (!allAuthorsValid) {
        alert('Please fill in all author details for each author.');
        return;
      }
      
      // Simulate submission
      alert('Paper submission received! You will receive a Paper ID via email shortly.');
      submissionForm.reset();
      
      // Remove any dynamically added author rows except the first one
      const existingRows = document.querySelectorAll('.author-row:not(:first-child)');
      existingRows.forEach(row => row.remove());
      
      // Remove the remove button from the first row if it exists
      const firstRowButtons = document.querySelector('.author-row .remove-author');
      if (firstRowButtons) {
        firstRowButtons.remove();
      }
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
  
  // Mobile search functionality
  const mobileSearchBtn = document.getElementById('mobileSearchBtn');
  if (mobileSearchBtn) {
    mobileSearchBtn.addEventListener('click', function() {
      const query = document.getElementById('mobileSearchInput')?.value || '';
      if (query) {
        alert('Mobile search for: ' + query);
        // Close modal after search
        const modalElement = document.getElementById('searchModal');
        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
        modal.hide();
      } else {
        alert('Please enter search terms.');
      }
    });
  }
  
  // Allow Enter key to trigger search
  const mobileSearchInput = document.getElementById('mobileSearchInput');
  if (mobileSearchInput) {
    mobileSearchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        document.getElementById('mobileSearchBtn').click();
      }
    });
  }
  
  // Add back-to-top button functionality
  const backToTopButton = document.createElement('button');
  backToTopButton.id = 'backToTop';
  backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
  backToTopButton.title = 'Back to Top';
  backToTopButton.style.cssText = `
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary, #0B1F3B);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
  `;
  document.body.appendChild(backToTopButton);
  
  // Show/hide back-to-top button based on scroll position
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > 300) {
      backToTopButton.style.opacity = '1';
      backToTopButton.style.visibility = 'visible';
    } else {
      backToTopButton.style.opacity = '0';
      backToTopButton.style.visibility = 'hidden';
    }
  });
  
  // Scroll to top when button is clicked
  backToTopButton.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  
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
  
  // Footer newsletter subscription
  const newsletterForm = document.querySelector('.footer-newsletter');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const emailInput = this.querySelector('input[type="email"]');
      const email = emailInput.value.trim();
      
      if (email) {
        // Show success message
        const originalBtn = this.querySelector('button');
        const originalText = originalBtn.innerHTML;
        originalBtn.innerHTML = '<i class="fas fa-check"></i>';
        originalBtn.classList.remove('btn-primary');
        originalBtn.classList.add('btn-success');
        
        // Reset after 2 seconds
        setTimeout(() => {
          originalBtn.innerHTML = originalText;
          originalBtn.classList.remove('btn-success');
          originalBtn.classList.add('btn-primary');
          emailInput.value = '';
        }, 2000);
        
        console.log('Newsletter subscription:', email);
        // Here you would typically send the email to your backend
      }
    });
  }
});
