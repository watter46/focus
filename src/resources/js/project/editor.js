window.Editor = class Editor {
    static UNCHECKED_COMMAND = '- [ ] ';
    static CHECKED_COMMAND   = '- [|] ';

    prepend(el) {
        this.content  = el.value;
        this.position = el.selectionStart;

        const content  = this.newContent();
        const position = this.newPosition();
        
        el.value = content;
        
        el.focus();
        el.setSelectionRange(position, position);
    }

    newContent() {
        const lines = this.content.split('\n');

        if (this.hasCommand()) {
            const prependLineIndex = this.lineIndex() + 1;
            lines.splice(prependLineIndex, 0, Editor.UNCHECKED_COMMAND);
            return lines.join('\n');
        }

        return lines
            .map((content, index) => {
                if (index !== this.lineIndex()) {
                    return content;
                }
                return Editor.UNCHECKED_COMMAND + content;
            })
            .join('\n');
    }

    newPosition() {
        if (this.hasCommand()) {
            return this.position + Editor.CHECKED_COMMAND.length + 1;
        }

        return this.position + Editor.UNCHECKED_COMMAND.length;
    }

    hasCommand() {
        const lines = this.content.split('\n');
        const line  = lines[this.lineIndex()];

        const hasChecked   = line.startsWith(Editor.CHECKED_COMMAND);
        const hasUnchecked = line.startsWith(Editor.UNCHECKED_COMMAND);

        return hasChecked || hasUnchecked;
    }

    lineIndex() {
        return this.content.substring(0, this.position).split('\n').length - 1;
    }
}