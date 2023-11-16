export class Cursor {
    #path;
    #cursorEl;

    constructor() {
        this.#path     = document.getElementById('path');
        this.#cursorEl = document.getElementById('cursor');
    }

    updateCursorPosition = (remainingTime, defaultTime) => {
        const pathLength = this.#path.getTotalLength();

        const cursorHeight = this.#cursorEl.offsetHeight;
        const cursorWidth  = this.#cursorEl.offsetWidth;

        let progress     = 1 - remainingTime / defaultTime;
        let strokeLength = pathLength * progress;
        let point        = this.#path.getPointAtLength(strokeLength);

        this.#cursorEl.style.top  = `${point.y - (cursorHeight / 2)}px`;
        this.#cursorEl.style.left = `${point.x - (cursorWidth / 2)}px`;
    }
}