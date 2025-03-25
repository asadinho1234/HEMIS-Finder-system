document.addEventListener('DOMContentLoaded', function() {
    // Check if we should use AJAX for form submission
    const useAjax = true; // Set to false to use traditional form submission
    
    if (useAjax) {
        const form = document.querySelector('.search-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                searchStudent();
            });
        }
    }
    
    function searchStudent() {
        const form = document.querySelector('.search-form');
        const studentId = form.querySelector('input[name="student_id"]').value.trim();
        
        // Get or create error message element
        let errorDiv = document.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            form.after(errorDiv);
        }
        
        // Remove existing result
        const existingResult = document.querySelector('.result-section');
        if (existingResult) {
            existingResult.remove();
        }
        
        // Validate input
        if (!studentId) {
            errorDiv.textContent = 'Please enter a Student ID';
            errorDiv.style.display = 'block';
            return;
        }
        
        // Show loading state
        const button = form.querySelector('button');
        const originalText = button.innerHTML;
        button.innerHTML = 'Searching...';
        button.disabled = true;
        
        // Hide error message
        errorDiv.style.display = 'none';
        
        // Make AJAX request
        fetch(`search.php?student_id=${encodeURIComponent(studentId)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Show error message
                    errorDiv.textContent = data.error;
                    errorDiv.style.display = 'block';
                } else if (data.success && data.student) {
                    // Create and display result
                    displayStudentResult(data.student);
                }
            })
            .catch(error => {
                errorDiv.textContent = 'An error occurred while processing your request';
                errorDiv.style.display = 'block';
                console.error('Error:', error);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }
    
    function displayStudentResult(student) {
        // Create result section
        const resultSection = document.createElement('div');
        resultSection.className = 'result-section';
        
        // Get status badge HTML
        const statusBadge = getStatusBadge(student.graduated);
        
        // Create result HTML
        resultSection.innerHTML = `
            <div class="student-header">
                <h3 class="student-name">${student.name}</h3>
                ${statusBadge}
            </div>
            
            <div class="student-details">
                <div class="detail-item">
                    <p class="detail-label">Student ID</p>
                    <p class="detail-value">${student.student_id}</p>
                </div>
                
                <div class="detail-item">
                    <p class="detail-label">HEMIS Number</p>
                    <p class="detail-value hemis">${student.hemis_number}</p>
                </div>
                
                <div class="detail-item">
                    <p class="detail-label">Degree Program</p>
                    <p class="detail-value">${student.degree_program}</p>
                </div>
            </div>
        `;
        
        // Add result to page
        document.querySelector('.card').appendChild(resultSection);
    }
    
    function getStatusBadge(status) {
        let badgeClass = '';
        let statusText = '';
        
        switch(status) {
            case 'learning':
                badgeClass = 'badge-learning';
                statusText = 'Learning';
                break;
            case 'graduate':
                badgeClass = 'badge-graduate';
                statusText = 'Graduated';
                break;
            case 'closed':
                badgeClass = 'badge-closed';
                statusText = 'Closed';
                break;
            default:
                badgeClass = 'badge-learning';
                statusText = 'Learning';
        }
        
        return `<span class="badge ${badgeClass}">${statusText}</span>`;
    }
});