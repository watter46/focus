import { Cursor } from "./cursor";

export class Timer {
    #remainingTime;
    #defaultTime;
    #intervalId;
    #cursor;

    constructor(defaultTime, remainingTime) { 
        this.#defaultTime = defaultTime;
        this.#remainingTime = remainingTime;

        this.#display(this.#remainingTime);

        this.#cursor = new Cursor();
        this.#cursor.updateCursorPosition(this.#remainingTime, this.#defaultTime);
    }

    getRemainingTime = () => {
        return this.#remainingTime;
    }

    start = () => {
        this.#intervalId = setInterval(() => this.#tick(), 1000);
    }

    stop = () => clearInterval(this.#intervalId);

    #complete = () => {
        clearInterval(this.#intervalId);

        Livewire.dispatch('complete-development');
    }

    #tick = () => {
        if (this.#remainingTime <= 0) {
            this.#complete();
            return;
        }

        this.#remainingTime--;

        this.#cursor.updateCursorPosition(this.#remainingTime, this.#defaultTime);
        this.#display(this.#remainingTime);
    }

    #display = (time) => {
        const timerEl = document.getElementById('countdown');

        const format = (time) => {
            const hours = Math.floor(time / 3600);
            const minutes = Math.floor((time - hours * 3600) / 60);
            const seconds = time - hours * 3600 - minutes * 60;

            const paddingZero = (times) => times < 10 ? `0${times}` : times;

            return `${paddingZero(hours)}:${paddingZero(minutes)}:${paddingZero(seconds)}`;
        }

        timerEl.innerHTML = format(time);
    }
}
