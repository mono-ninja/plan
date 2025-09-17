'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('plan-features-list');
  const addBtn = document.getElementById('add-feature');

  let draggedItem = null;

  list.addEventListener('dragstart', (e) => {
    if (e.target && e.target.matches('.feature-item')) {
      draggedItem = e.target;
      e.target.classList.add('dragging');
    }
  });

  list.addEventListener('dragend', (e) => {
    if (draggedItem) {
      draggedItem.classList.remove('dragging');
      draggedItem = null;
    }
  });

  list.addEventListener('dragover', (e) => {
    e.preventDefault();
    const afterElement = getDragAfterElement(list, e.clientY);
    if (draggedItem) {
      if (afterElement == null) {
        list.appendChild(draggedItem);
      } else {
        list.insertBefore(draggedItem, afterElement);
      }
    }
  });

  function getDragAfterElement(container, y) {
    const draggableElements = [...container.querySelectorAll('.feature-item:not(.dragging)')];
    return draggableElements.reduce((closest, child) => {
      const box = child.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;
      if (offset < 0 && offset > closest.offset) {
        return { offset: offset, element: child };
      } else {
        return closest;
      }
    }, { offset: Number.NEGATIVE_INFINITY }).element;
  }

  addBtn.addEventListener('click', () => {
    const li = document.createElement('li');
    li.className = 'feature-item';
    li.setAttribute('draggable', 'true');
    li.innerHTML = '<span class="handle"><span class="dashicons dashicons-move" aria-hidden="true"></span></span>' +
        '<input type="text" class="input-feature" name="features[]" value=""> ' +
        '<button type="button" class="button remove-feature">-</button>';
    list.appendChild(li);
  });

  list.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-feature')) {
      e.preventDefault();
      e.target.closest('li').remove();
    }
  });

});