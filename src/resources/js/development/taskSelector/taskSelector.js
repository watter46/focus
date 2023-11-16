import Sortable from 'sortablejs';


window.taskSelectorSortable = () => setupTaskSelectorSortable();

const setupTaskSelectorSortable = () => {
    setupIncompleteTasksSortable();
    setupDevelopmentTasksSortable();
}

const setupDevelopmentTasksSortable = () => {
    const developmentTasks = document.getElementById('developmentTasks');
    
    if (!developmentTasks) return;

    Sortable.create(developmentTasks, {
        group: {
            name: "developmentTasks",
            put: ["incompleteTasks", "developmentTasks"]
        },
        onSort: function () {
            const selectedIds = this.options.store.get(this);

            Livewire.dispatch('setSelectedIdList', { selectedIdList: selectedIds });
        },
        store: {
            get: function (sortable) {
                return sortable.toArray();
            }
        },
        onStart: function () {
            const el = document.getElementById('incompleteTasks');
            
            expandDraggableArea(el);
        },
        chosenClass: 'chosen',
        animation: 300
    })
}

const setupIncompleteTasksSortable = () => {
    const incompleteTasksEl = document.getElementById('incompleteTasks');

    if (!incompleteTasksEl) return;

    Sortable.create(incompleteTasksEl, {
        group: {
            name: "incompleteTasks",
            put: ["incompleteTasks", "developmentTasks"]
        },
        onSort: function () {
            let ids = this.options.store.get(this)

            if (!ids.length) {
                incompleteTasksEl.textContent = '';
            }
        },
        store: {
            get: function (sortable) {
                return sortable.toArray();
            }
        },
        onStart: function () {
            const el = document.getElementById('developmentTasks');

            expandDraggableArea(el);
        },
        chosenClass: 'chosen',
        animation: 300
    })
}

const expandDraggableArea = (el) => {
    const bottomPx = el.parentElement.getBoundingClientRect().bottom;
    const topPx    = el.getBoundingClientRect().top;
    const heightPx = bottomPx - topPx - 2;

    el.style.height = `${heightPx}px`;
}