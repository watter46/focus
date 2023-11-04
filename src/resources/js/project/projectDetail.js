import Sortable from 'sortablejs';

window.sortable = (el) => sortable(el);
window.check    = (el) => check(el);

const sortable = (el) => {
    const sortableEl = el.querySelector('#sortable');

    if (!sortableEl) return;
    
    Sortable.create(sortableEl, {
        handle: '.handle',
        animation: 300,
        onUpdate: () => reorder(sortableEl)
    });
}

const reorder = (el) => {    
    const componentId = el.closest('#taskList').getAttribute('wire:id');

    Livewire.find(componentId)
            .call('reorder', createNewContents(el));
}

const check = (el) => {
    const componentId = el.closest('#taskList').getAttribute('wire:id');

    Livewire.find(componentId)
            .call('updateCheckbox', createNewContents(el));
}

const getComponentId = (el) => {
    return el.closest('#task').getAttribute('wire:id');
}

const createNewContents = (el) => {
    const taskEl     = el.closest('#task')
    const contentEls = [...taskEl.querySelectorAll('#taskText')];
                            
    const result = contentEls.map(taskEl => {
        const UNCHECKED_COMMAND = '- [ ] ';
        const CHECKED_COMMAND   = '- [|] ';

        const isCommand = (el) => el.tagName === 'SPAN';
        const isChecked = (el) => el.previousElementSibling.checked;
                    
        const text = taskEl.innerText;

        if (isCommand(taskEl)) {
            return isChecked(taskEl)
                ? CHECKED_COMMAND   + text
                : UNCHECKED_COMMAND + text;
        }
    
        return taskEl.innerText;
    }).join('\n');

    return result;
}