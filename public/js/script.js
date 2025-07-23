document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.createElement('div');
        overlay.className = 'overlay';
        document.body.appendChild(overlay);
        
        function toggleSidebar() {
          sidebar.classList.toggle('active');
          overlay.classList.toggle('active');
          
          // Simple toggle without changing icon
          mobileMenuToggle.classList.toggle('active');
        }
        
        mobileMenuToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
      });