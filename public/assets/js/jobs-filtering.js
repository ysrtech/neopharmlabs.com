document.addEventListener('DOMContentLoaded', function() {
   const badges = document.querySelectorAll('.badge-filter');
   const searchInput = document.getElementById('searchJobs');
   const jobsContainer = document.getElementById('jobsContainer');
   
   if (!searchInput || !jobsContainer) {
      console.error('Elements not found');
      return;
   }
   
   const allJobs = Array.from(jobsContainer.querySelectorAll('.job-item'));
   const JOBS_PER_PAGE = 8;
   let currentPage = 1;
   
   let activeFilters = {
      department: 'all',
      location: 'all',
      jobtype: 'all',
      search: ''
   };
   
   function getVisibleJobs() {
      return allJobs.filter(job => {
         const jobDept = job.dataset.department ? job.dataset.department.toLowerCase() : '';
         const jobLocation = job.dataset.location ? job.dataset.location.toLowerCase() : '';
         const jobType = job.dataset.jobtype ? job.dataset.jobtype.toLowerCase() : '';
         const jobTitleEl = job.querySelector('.job-title');
         const jobDescEl = job.querySelector('.job-description');
         
         if (!jobTitleEl || !jobDescEl) return false;
         
         const jobTitle = jobTitleEl.textContent.toLowerCase();
         const jobDesc = jobDescEl.textContent.toLowerCase();
         
         const matchDept = activeFilters.department === 'all' || jobDept.includes(activeFilters.department);
         const matchLocation = activeFilters.location === 'all' || jobLocation === activeFilters.location;
         const matchJobType = activeFilters.jobtype === 'all' || jobType === activeFilters.jobtype;
         const matchSearch = activeFilters.search === '' || jobTitle.includes(activeFilters.search) || jobDesc.includes(activeFilters.search);
         
         return matchDept && matchLocation && matchJobType && matchSearch;
      });
   }
   
   function displayPage(page) {
      const visibleJobs = getVisibleJobs();
      const totalPages = Math.ceil(visibleJobs.length / JOBS_PER_PAGE);
      
      // Hide all jobs first
      allJobs.forEach(job => job.style.display = 'none');
      
      // Show jobs for current page
      const start = (page - 1) * JOBS_PER_PAGE;
      const end = start + JOBS_PER_PAGE;
      const jobsToShow = visibleJobs.slice(start, end);
      
      jobsToShow.forEach(job => job.style.display = 'block');
      
      // Update pagination
      updatePagination(page, totalPages);
      
      // Scroll to top of jobs list
      jobsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
   }
   
   function updatePagination(page, totalPages) {
      const paginationNumbers = document.getElementById('paginationNumbers');
      const prevBtn = document.getElementById('prevPage');
      const nextBtn = document.getElementById('nextPage');
      const paginationWrapper = document.getElementById('paginationWrapper');
      
      // Hide pagination if only 1 page or less
      if (totalPages <= 1) {
         paginationWrapper.style.display = 'none';
         return;
      } else {
         paginationWrapper.style.display = 'flex';
      }
      
      // Update prev/next buttons
      prevBtn.disabled = page === 1;
      nextBtn.disabled = page === totalPages;
      
      // Generate page numbers
      paginationNumbers.innerHTML = '';
      
      for (let i = 1; i <= totalPages; i++) {
         // Show first, last, current, and adjacent pages
         if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-number' + (i === page ? ' active' : '');
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
               currentPage = i;
               displayPage(i);
            });
            paginationNumbers.appendChild(pageBtn);
         } else if (i === page - 2 || i === page + 2) {
            // Show ellipsis
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination-ellipsis';
            ellipsis.textContent = '...';
            paginationNumbers.appendChild(ellipsis);
         }
      }
   }
   
   function filterJobs() {
      currentPage = 1; // Reset to first page when filtering
      displayPage(currentPage);
   }
   
   // Badge filters
   badges.forEach(badge => {
      badge.addEventListener('click', function() {
         const filterType = this.dataset.type;
         const filterValue = this.dataset.filter;
         
         // Update active state only for same type
         badges.forEach(b => {
            if (b.dataset.type === filterType && b.dataset.filter !== 'all') {
               b.classList.remove('active');
            }
         });
         
         // If clicking "View All", reset all filters
         if (filterValue === 'all') {
            activeFilters.department = 'all';
            activeFilters.location = 'all';
            activeFilters.jobtype = 'all';
            badges.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
         } else {
            // Remove "View All" active state
            badges.forEach(b => {
               if (b.dataset.filter === 'all') {
                  b.classList.remove('active');
               }
            });
            this.classList.add('active');
            activeFilters[filterType] = filterValue;
         }
         
         filterJobs();
      });
   });
   
   // Search input
   searchInput.addEventListener('input', function() {
      activeFilters.search = this.value.toLowerCase().trim();
      filterJobs();
   });
   
   // Pagination buttons
   document.getElementById('prevPage').addEventListener('click', () => {
      if (currentPage > 1) {
         currentPage--;
         displayPage(currentPage);
      }
   });
   
   document.getElementById('nextPage').addEventListener('click', () => {
      const visibleJobs = getVisibleJobs();
      const totalPages = Math.ceil(visibleJobs.length / JOBS_PER_PAGE);
      if (currentPage < totalPages) {
         currentPage++;
         displayPage(currentPage);
      }
   });
   
   // Initial display
   displayPage(1);
});
