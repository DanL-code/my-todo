
// check box action
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.check_box');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const form = this.closest('form');
            // Get the current order
            const urlParams = new URLSearchParams(window.location.search);
            let order = urlParams.get('order') || 'asc';

            // Add sorting order to form data
            const sortOrderInput = document.createElement('input');
            sortOrderInput.type = 'hidden';
            sortOrderInput.name = 'order';
            sortOrderInput.value = order;
            form.appendChild(sortOrderInput);
            form.submit();
        });
    });
});

// desplay edit text field and save change btn
function showEditForm(todoId, task, date) {
    //Back to the top
    window.scrollTo(0, 0);
    // Hide the add todo form
    document.getElementById('addTodoSection').style.display = 'none';
    // Set the values in the edit form
    document.getElementById('edit_form_id').value = todoId;
    document.getElementById('edit_form_task').value = task;
    document.getElementById('edit_form_date').value = date;
    // Show the edit form
    document.getElementById('edit_form').style.display = 'block';
    document.getElementById('edit_form_btn').setAttribute('name', 'save_edited_todo');
}

// stay at the same position when tick the checkbox and delete todos
document.addEventListener('DOMContentLoaded', function () {
    var scrollPosition = localStorage.getItem('scrollposition');
    if (scrollPosition) {
        window.scrollTo(0, scrollPosition);
        localStorage.removeItem('scrollposition');
    }
});
window.addEventListener('beforeunload', function () {
    localStorage.setItem('scrollposition', window.scrollY);
});

// change theme colour and store the picked theme
function changeThemeColour(theme) {
    const body = document.body;
    body.classList.remove('springTheme', 'summerTheme', 'autumnTheme', 'xmasTheme', 'defaultTheme');
    body.classList.add(`${theme}Theme`);
    localStorage.setItem('theme', theme);
}
document.addEventListener('DOMContentLoaded', function () {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        changeThemeColour(savedTheme);
    }
});



