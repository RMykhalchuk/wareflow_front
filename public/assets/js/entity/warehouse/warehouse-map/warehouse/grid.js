import { createCard, CELL_SIZE } from './card.js';

const GRID_COLS = 16;
const GRID_ROWS = 12;

export function createGrid({ onDrop, onUpdate, onDelete, onNavigate, canNavigate, readonly }) {
  const wrapper = document.createElement('div');
  wrapper.className = 'map-area';

  const grid = document.createElement('div');
  grid.className = `wh-map-grid map-grid${!readonly ? ' edit-mode' : ''}`;
  grid.style.minWidth = GRID_COLS * CELL_SIZE + 'px';
  grid.style.height = GRID_ROWS * CELL_SIZE + 'px';
  wrapper.appendChild(grid);

  const empty = document.createElement('div');
  empty.className = 'map-empty';
  grid.appendChild(empty);

  const cardInstances = new Map();
  let currentReadonly = readonly;

  grid.addEventListener('dragover', e => {
    if (currentReadonly) return;
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
  });

  grid.addEventListener('drop', e => {
    if (currentReadonly) return;
    e.preventDefault();
    const elementId = e.dataTransfer.getData('text/plain');
    if (!elementId) return;
    const rect = grid.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const effectiveCols = Math.floor(rect.width / CELL_SIZE);
    const gridX = Math.max(0, Math.min(effectiveCols - 3, Math.floor(x / CELL_SIZE)));
    const gridY = Math.max(0, Math.min(GRID_ROWS - 2, Math.floor(y / CELL_SIZE)));
    onDrop(elementId, gridX, gridY);
  });

  function updateEmptyMessage() {
    empty.innerHTML = `
      <div style="text-align:center">
        <div style="width:56px;height:56px;border-radius:14px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
          <i class="bi bi-grid" style="font-size:24px;color:#ced4da"></i>
        </div>
        ${currentReadonly
          ? '<p style="color:#adb5bd;font-size:13px;margin:0">Карта порожня</p>'
          : '<p style="color:#adb5bd;font-size:13px;font-weight:500;margin:0">Перетягніть елементи сюди</p><p style="color:#ced4da;font-size:11px;margin:4px 0 0">або натисніть на елемент у списку</p>'
        }
      </div>
    `;
  }

  updateEmptyMessage();

  function setItems(items) {
    const currentIds = new Set(items.map(i => i.mapElement.id));

    cardInstances.forEach((instance, id) => {
      if (!currentIds.has(id)) {
        if (instance.el.parentNode) instance.el.parentNode.removeChild(instance.el);
        cardInstances.delete(id);
      }
    });

    items.forEach(({ mapElement, label }) => {
      if (cardInstances.has(mapElement.id)) {
        cardInstances.get(mapElement.id).update(mapElement, label);
      } else {
        const card = createCard({
          element: mapElement,
          label,
          gridCols: GRID_COLS,
          gridRows: GRID_ROWS,
          onUpdate: updates => onUpdate(mapElement.id, updates),
          onDelete: () => onDelete(mapElement.id),
          onNavigate: onNavigate ? () => onNavigate(mapElement.element_id) : null,
          canNavigate: !!canNavigate,
          readonly: currentReadonly,
        });
        cardInstances.set(mapElement.id, card);
        grid.appendChild(card.el);
      }
    });

    empty.style.display = items.length === 0 ? 'flex' : 'none';
  }

  function setReadonly(isReadonly) {
    currentReadonly = isReadonly;
    if (isReadonly) {
      grid.classList.remove('edit-mode');
      grid.style.backgroundSize = '';
    } else {
      grid.classList.add('edit-mode');
      grid.style.backgroundSize = `${CELL_SIZE}px ${CELL_SIZE}px`;
    }
    updateEmptyMessage();
    cardInstances.forEach(instance => instance.setReadonly(isReadonly));
  }

  return { el: wrapper, setItems, setReadonly };
}

export { GRID_COLS, GRID_ROWS };
