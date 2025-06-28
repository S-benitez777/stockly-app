document.addEventListener('DOMContentLoaded', function() {
    // Profile image upload functionality
    const profileImage = document.querySelector('.large-profile-pic');
    const editPhotoLink = document.querySelector('.edit-photo-link');
    
    // Create a hidden file input
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.accept = 'image/*';
    fileInput.style.display = 'none';
    document.body.appendChild(fileInput);
    
    // Handle click on edit photo link
    editPhotoLink.addEventListener('click', function(e) {
      e.preventDefault();
      fileInput.click();
    });
    
    // Handle file selection
    fileInput.addEventListener('change', function(event) {
      if (event.target.files && event.target.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          profileImage.src = e.target.result;
        };
        
        reader.readAsDataURL(event.target.files[0]);
      }
    });
    
    // Edit field functionality
    const editButtons = document.querySelectorAll('.edit-btn');
    
    editButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        const fieldContainer = this.closest('.editable-field');
        const fieldValue = fieldContainer.querySelector('.field-value');
        const fieldType = fieldValue.dataset.field;
        const currentValue = fieldValue.textContent;
        
        // Create an input element
        const inputElement = document.createElement('input');
        inputElement.type = fieldType === 'correo' ? 'email' : 'text';
        inputElement.className = 'field-input';
        inputElement.value = currentValue;
        
        // Create buttons for confirming and canceling
        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'field-actions';
        
        const confirmBtn = document.createElement('button');
        confirmBtn.className = 'confirm-btn';
        confirmBtn.textContent = 'Confirmar';
        
        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'cancel-btn';
        cancelBtn.textContent = 'Cancelar';
        
        actionsDiv.appendChild(confirmBtn);
        actionsDiv.appendChild(cancelBtn);
        
        // Add elements to the field container
        fieldContainer.classList.add('edit-mode');
        fieldContainer.appendChild(inputElement);
        fieldContainer.appendChild(actionsDiv);
        
        // Focus the input
        inputElement.focus();
        
        // Handle confirm click
        confirmBtn.addEventListener('click', function() {
          const newValue = inputElement.value.trim();
          
          if (!newValue) {
            alert('Este campo no puede estar vacío');
            return;
          }
          
          if (fieldType === 'correo' && !validateEmail(newValue)) {
            alert('Por favor ingrese un correo electrónico válido');
            return;
          }
          
          // Update the field value
          fieldValue.textContent = newValue;
          
          // Remove the input and buttons
          cleanupEditMode(fieldContainer);
        });
        
        // Handle cancel click
        cancelBtn.addEventListener('click', function() {
          cleanupEditMode(fieldContainer);
        });
      });
    });
    
    // Helper function to clean up edit mode
    function cleanupEditMode(container) {
      container.classList.remove('edit-mode');
      
      const input = container.querySelector('.field-input');
      const actions = container.querySelector('.field-actions');
      
      if (input) input.remove();
      if (actions) actions.remove();
    }
    
    // Email validation helper
    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }
    
    // Change password link functionality
    const changePasswordLink = document.querySelector('.change-password-link');
    const passwordField = document.querySelector('.password-value');
    
    changePasswordLink.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Create password change modal/form (simplified for this example)
      const currentPassword = passwordField.textContent;
      
      // Create a simple prompt (in a real app, use a modal)
      const newPassword = prompt('Ingrese nueva contraseña:', '');
      if (newPassword && newPassword.trim() !== '') {
        passwordField.textContent = newPassword;
        alert('Contraseña actualizada. No olvide guardar los cambios.');
      }
    });
    
    // Save changes button functionality
    const saveChangesBtn = document.querySelector('.save-changes-btn');
    
    saveChangesBtn.addEventListener('click', function() {
      // In a real app, this would send data to a server
      // For this demo, just show a success message
      alert('Cambios guardados correctamente');
    });
    
    // Logout link functionality - ACTUALIZADO
    const logoutLink = document.querySelector('.logout-link');
    
    logoutLink.addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('¿Está seguro que desea cerrar sesión?')) {
        // In a real app, this would destroy the session
        window.location.href = 'inicio_de_sesion.html'; // Redirigir a la página de inicio de sesión
      }
    });
    
    // Sidebar logout link functionality - NUEVO
    const sidebarLogoutLink = document.querySelector('.menu-item[data-section="cerrar"]');
    
    if (sidebarLogoutLink) {
      sidebarLogoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('¿Está seguro que desea cerrar sesión?')) {
          window.location.href = 'inicio_de_sesion.html';
        }
      });
    }
    
    // Delete account link functionality
    const deleteLink = document.querySelector('.delete-link');
    
    deleteLink.addEventListener('click', function(e) {
      e.preventDefault();
      if (confirm('¿Está seguro que desea eliminar su cuenta? Esta acción no se puede deshacer.')) {
        if (confirm('¿REALMENTE está seguro? Todos sus datos serán eliminados permanentemente.')) {
          // In a real app, this would call an API to delete the account
          alert('Cuenta eliminada correctamente');
          window.location.href = 'inicio_de_sesion.html'; // Redirigir a la página de inicio de sesión
        }
      }
    });
    
    // Highlight current section in sidebar
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
      if (item.dataset.section === 'ajustes') {
        item.classList.add('active');
      } else {
        item.classList.remove('active');
      }
    });
});